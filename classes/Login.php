<?php
require_once '../config.php';
class Login extends DBConnection {
    private $settings;
    
    public function __construct(){
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
        ini_set('display_error', 1);
    }

    public function __destruct(){
        parent::__destruct();
    }

    public function index(){
        echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
    }

    // Original login method
    public function login(){
        extract($_POST);
        $stmt = $this->conn->prepare("SELECT * from users where email = ? and password = ? ");
        $pw = md5($password);
        $stmt->bind_param('ss',$email,$pw);
        $stmt->execute();
        $qry = $stmt->get_result();
        if($qry->num_rows > 0){
            $res = $qry->fetch_array();
            if($res['status'] != 1){
                return json_encode(array('status'=>'notverified'));
            }
            foreach($res as $k => $v){
                if(!is_numeric($k) && $k != 'password'){
                    $this->settings->set_userdata($k,$v);
                }
            }
            $this->settings->set_userdata('login_type',1);
            return json_encode(array('status'=>'success'));
        }else{
            return json_encode(array('status'=>'','error'=>$this->conn->error));
        }
    }

    public function logout(){
        if($this->settings->sess_des()){
            redirect('admin/login');
        }
    }

    // Original employee login method
    function employee_login(){
        extract($_POST);
        $stmt = $this->conn->prepare("SELECT *,concat(lastname,', ',firstname,' ',middlename) as fullname from employee_list where email = ? and `password` = ? ");
        $pw = md5($password);
        $stmt->bind_param('ss',$email,$pw);
        $stmt->execute();
        $qry = $stmt->get_result();
        if($this->conn->error){
            $resp['status'] = 'failed';
            $resp['msg'] = "An error occurred while fetching data. Error:". $this->conn->error;
        }else{
            if($qry->num_rows > 0){
                $res = $qry->fetch_array();
                if($res['status'] == 1){
                    foreach($res as $k => $v){
                        $this->settings->set_userdata($k,$v);
                    }
                    $this->settings->set_userdata('login_type',2);
                    $resp['status'] = 'success';
                }else{
                    $resp['status'] = 'failed';
                    $resp['msg'] = "Your Account is Inactive. Please Contact the Management to verify your account.";
                }
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = "Invalid email or password.";
            }
        }
        return json_encode($resp);
    }

    public function employee_logout(){
        if($this->settings->sess_des()){
            redirect('./login');
        }
    }

    // New method to handle password reset request (send OTP)
    public function reset_password_request(){
        extract($_POST);
        $stmt = $this->conn->prepare("SELECT email from users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $qry = $stmt->get_result();
        if($qry->num_rows > 0){
            // Generate a random OTP (6 digits)
            $otp = random_int(100000, 999999);

            // Save OTP in the database against the user's email
            $stmt = $this->conn->prepare("UPDATE users SET code = ? WHERE email = ?");
            $stmt->bind_param('is', $otp, $email);
            if ($stmt->execute()) {
                // Send OTP via email (You would need your own email-sending code here)
                // Example:
                // mail($email, "Password Reset OTP", "Use this OTP to reset your password: $otp");
                // For the sake of the example, let's assume email is sent successfully.
                
                return json_encode(array('status' => 'success', 'message' => 'OTP sent successfully.'));
            } else {
                return json_encode(array('status' => 'failed', 'message' => 'Failed to send OTP.'));
            }
        } else {
            return json_encode(array('status' => 'failed', 'message' => 'Email not found.'));
        }
    }

    // New method to handle password reset with OTP validation
    public function reset_password(){
        extract($_POST);
        
        // Validate OTP
        $stmt = $this->conn->prepare("SELECT code FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $qry = $stmt->get_result();
        
        if($qry->num_rows > 0){
            $res = $qry->fetch_array();
            // Check if the OTP matches
            if($otp == $res['code']){
                // Check if the new password and confirm password match
                if($new_password != $confirm_password){
                    return json_encode(array('status' => 'failed', 'message' => 'Passwords do not match.'));
                }
                
                // Update the password (hashed for security)
                $hashed_password = md5($new_password);
                $stmt = $this->conn->prepare("UPDATE users SET password = ?, code = NULL WHERE email = ?");
                $stmt->bind_param('ss', $hashed_password, $email);
                if($stmt->execute()){
                    return json_encode(array('status' => 'success', 'message' => 'Password has been reset.'));
                } else {
                    return json_encode(array('status' => 'failed', 'message' => 'Failed to update password.'));
                }
            } else {
                return json_encode(array('status' => 'failed', 'message' => 'Invalid OTP.'));
            }
        } else {
            return json_encode(array('status' => 'failed', 'message' => 'Email not found.'));
        }
    }
}

// Handle the requested action
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();
switch ($action) {
    case 'login':
        echo $auth->login();
        break;
    case 'logout':
        echo $auth->logout();
        break;
    case 'reset_password_request':
        echo $auth->reset_password_request();
        break;
    case 'reset_password':
        echo $auth->reset_password();
        break;
    default:
        echo $auth->index();
        break;
}
