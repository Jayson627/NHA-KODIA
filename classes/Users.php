<?php
require_once('../config.php');

class Users extends DBConnection {
    private $settings;

    public function __construct(){
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
    }

    public function __destruct(){
        parent::__destruct();
    }

    public function save_users(){
        if(!isset($_POST['status']) && $this->settings->userdata('login_type') == 1){
            $_POST['status'] = 1;
        }
        extract($_POST);
        $oid = $id;
        $data = '';

        // If old password is set, verify it
        if(isset($oldpassword)){
            if(!password_verify($oldpassword, $this->settings->userdata('password'))){
                return 4;  // Password mismatch
            }
        }

        // Check if the email is already taken
        $chk = $this->conn->query("SELECT * FROM `users` where email ='{$email}' ".($id > 0 ? " and id!= '{$id}' " : ""))->num_rows;
        if($chk > 0){
            return 3;  // Email already exists
            exit;
        }

        // Prepare the data for insertion or update
        foreach($_POST as $k => $v){
            if(in_array($k, array('firstname', 'middlename', 'lastname', 'email', 'type'))){
                if(!empty($data)) $data .= " , ";
                $data .= " {$k} = '{$v}' ";
            }
        }

        // Hash the password with Argon2i
        if(!empty($password)){
            $password = password_hash($password, PASSWORD_ARGON2I);
            if(!empty($data)) $data .= " , ";
            $data .= " `password` = '{$password}' ";
        }

        // Generate a random ID if not set
        if (empty($id)) {
            $id = bin2hex(random_bytes(8));  // Generates a unique 16-character random ID
        }

        if(empty($oid)){
            $qry = $this->conn->query("INSERT INTO users set {$data}, id = '{$id}'");
            if($qry){
                $this->settings->set_flashdata('success', 'User Details successfully saved.');
                $resp['status'] = 1;
            } else {
                $resp['status'] = 2;  // Error in insert
            }
        } else {
            $qry = $this->conn->query("UPDATE users set $data where id = '{$id}'");
            if($qry){
                $this->settings->set_flashdata('success', 'User Details successfully updated.');
                if($id == $this->settings->userdata('id')){
                    foreach($_POST as $k => $v){
                        if($k != 'id'){
                            if(!empty($data)) $data .= " , ";
                            $this->settings->set_userdata($k, $v);
                        }
                    }
                }
                $resp['status'] = 1;
            } else {
                $resp['status'] = 2;  // Error in update
            }
        }

        // Handle avatar upload
        if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
            $fname = 'uploads/avatar-'.$id.'.png';
            $dir_path = base_app . $fname;
            $upload = $_FILES['img']['tmp_name'];
            $type = mime_content_type($upload);
            $allowed = array('image/png', 'image/jpeg');

            if(!in_array($type, $allowed)){
                $resp['msg'] .= " But Image failed to upload due to invalid file type.";
            } else {
                $new_height = 200;
                $new_width = 200;

                list($width, $height) = getimagesize($upload);
                $t_image = imagecreatetruecolor($new_width, $new_height);
                imagealphablending($t_image, false);
                imagesavealpha($t_image, true);
                $gdImg = ($type == 'image/png') ? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
                imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                if($gdImg){
                    if(is_file($dir_path)) unlink($dir_path);
                    $uploaded_img = imagepng($t_image, $dir_path);
                    imagedestroy($gdImg);
                    imagedestroy($t_image);
                } else {
                    $resp['msg'] .= " But Image failed to upload due to unknown reason.";
                }
            }

            if(isset($uploaded_img)){
                $this->conn->query("UPDATE users set `avatar` = CONCAT('{$fname}', '?v=', unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}' ");
                if($id == $this->settings->userdata('id')){
                    $this->settings->set_userdata('avatar', $fname);
                }
            }
        }

        if(isset($resp['msg'])){
            $this->settings->set_flashdata('success', $resp['msg']);
        }

        return $resp['status'];
    }

    public function delete_users(){
        extract($_POST);
        $avatar = $this->conn->query("SELECT avatar FROM users where id = '{$id}'")->fetch_array()['avatar'];
        $qry = $this->conn->query("DELETE FROM users where id = '{$id}'");
        if($qry){
            $avatar = explode("?", $avatar)[0];
            $this->settings->set_flashdata('success', 'User Details successfully deleted.');
            if(is_file(base_app . $avatar))
                unlink(base_app . $avatar);
            $resp['status'] = 'success';
        } else {
            $resp['status'] = 'failed';
        }
        return json_encode($resp);
    }

    public function save_employee(){
        if(!empty($_POST['password'])){
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_ARGON2I);  // Hash password
        } else {
            unset($_POST['password']);
        }

        extract($_POST);
        $data = '';
        
        // Generate a random employee ID if not set
        if (empty($id)) {
            $id = bin2hex(random_bytes(8));  // Generates a unique 16-character random ID
        }

        $chk = $this->conn->query("SELECT * FROM `employee_list` where code ='{$code}' ".($id > 0 ? " and id!= '{$id}' " : ""))->num_rows;
        if($chk > 0){
            $resp['status'] = 'failed';
            $resp['msg'] = ' Employee Code already exists.';
        } else {
            foreach($_POST as $k => $v){
                if(!in_array($k, array('id'))){
                    if(!empty($data)) $data .= " , ";
                    $data .= " {$k} = '{$v}' ";
                }
            }
            if(empty($id)){
                $sql = "INSERT INTO employee_list set {$data}, id = '{$id}'";
            } else {
                $sql = "UPDATE employee_list set {$data} where id = '{$id}' ";
            }

            $save = $this->conn->query($sql);
            if($save){
                $eid = empty($id) ? $this->conn->insert_id : $id;
                $resp['status'] = 'success';
                if(empty($id)) $resp['msg'] = ' Employee successfully added';
                else $resp['msg'] = ' Employee details successfully saved';

                // Handle avatar upload for employee
                if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
                    $fname = 'uploads/employee-'.$eid.'.png';
                    $dir_path = base_app . $fname;
                    $upload = $_FILES['img']['tmp_name'];
                    $type = mime_content_type($upload);
                    $allowed = array('image/png', 'image/jpeg');

                    if(!in_array($type, $allowed)){
                        $resp['msg'] .= " But Image failed to upload due to invalid file type.";
                    } else {
                        $new_height = 200;
                        $new_width = 200;

                        list($width, $height) = getimagesize($upload);
                        $t_image = imagecreatetruecolor($new_width, $new_height);
                        imagealphablending($t_image, false);
                        imagesavealpha($t_image, true);
                        $gdImg = ($type == 'image/png') ? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
                        imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                        if($gdImg){
                            if(is_file($dir_path)) unlink($dir_path);
                            $uploaded_img = imagepng($t_image, $dir_path);
                            imagedestroy($gdImg);
                            imagedestroy($t_image);
                            if(isset($uploaded_img)){
                                $this->conn->query("UPDATE employee_list set `avatar` = CONCAT('{$fname}', '?v=', unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$eid}' ");
                                if($this->settings->userdata('login_type') == 2 && $id == $this->settings->userdata('id')){
                                    $this->settings->set_userdata('avatar', $fname);
                                }
                            }
                        } else {
                            $resp['msg'] .= " But Image failed to upload due to unknown reason.";
                        }
                    }
                }
            }
        }

        if(isset($resp['msg'])){
            $this->settings->set_flashdata('success', $resp['msg']);
        }
        return json_encode($resp);
    }

    public function delete_employee(){
        extract($_POST);
        $avatar = $this->conn->query("SELECT avatar FROM employee_list where id = '{$id}'")->fetch_array()['avatar'];
        $qry = $this->conn->query("DELETE FROM employee_list where id = '{$id}'");
        if($qry){
            $avatar = explode("?", $avatar)[0];
            $this->settings->set_flashdata('success', 'Employee Details successfully deleted.');
            if(is_file(base_app . $avatar))
                unlink(base_app . $avatar);
            $resp['status'] = 'success';
        } else {
            $resp['status'] = 'failed';
        }
        return json_encode($resp);
    }
}

$users = new Users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
    case 'save':
        echo $users->save_users();
    break;
    case 'delete':
        echo $users->delete_users();
    break;
    case 'save_employee':
        echo $users->save_employee();
    break;
    case 'delete_employee':
        echo $users->delete_employee();
    break;
    default:
        // echo $sysset->index();
    break;
}
