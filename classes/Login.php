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

    public function login(){
        extract($_POST);
        // Use prepared statement to fetch user by email
        $stmt = $this->conn->prepare("SELECT * from users where email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $qry = $stmt->get_result();

        if($qry->num_rows > 0){
            $res = $qry->fetch_array();

            // Verify password using Argon2i
            if (!password_verify($password, $res['password'])) {
                return json_encode(array('status' => '', 'error' => 'Invalid password.'));
            }

            // Check if the account is verified
            if ($res['status'] != 1) {
                return json_encode(array('status' => 'notverified'));
            }

            // Store user data in session
            foreach ($res as $k => $v){
                if(!is_numeric($k) && $k != 'password'){
                    $this->settings->set_userdata($k, $v); 
                }
            }
            $this->settings->set_userdata('login_type', 1);

            return json_encode(array('status' => 'success'));
        } else {
            return json_encode(array('status' => '', 'error' => $this->conn->error));
        }
    }

    public function logout(){
        if($this->settings->sess_des()){
            redirect('admin/login.php');
        }
    }

    public function employee_login(){
        extract($_POST);
        
        // Use prepared statement to fetch employee by email
        $stmt = $this->conn->prepare("SELECT *, concat(lastname, ', ', firstname, ' ', middlename) as fullname from employee_list where email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $qry = $stmt->get_result();

        if ($this->conn->error) {
            $resp['status'] = 'failed';
            $resp['msg'] = "An error occurred while fetching data. Error:". $this->conn->error;
        } else {
            if ($qry->num_rows > 0) {
                $res = $qry->fetch_array();
                
                // Verify password using Argon2i
                if (!password_verify($password, $res['password'])) {
                    $resp['status'] = 'failed';
                    $resp['msg'] = "Invalid email or password.";
                } elseif ($res['status'] == 1) {
                    // Store employee data in session
                    foreach ($res as $k => $v) {
                        $this->settings->set_userdata($k, $v);
                    }
                    $this->settings->set_userdata('login_type', 2);
                    $resp['status'] = 'success';
                } else {
                    $resp['status'] = 'failed';
                    $resp['msg'] = "Your account is inactive. Please contact the management to verify your account.";
                }
            } else {
                $resp['status'] = 'failed';
                $resp['msg'] = "Invalid email or password.";
            }
        }

        return json_encode($resp);
    }

    public function employee_logout(){
        if($this->settings->sess_des()){
            redirect('./login.php');
        }
    }
}

// Handling actions
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();

switch ($action) {
    case 'login':
        echo $auth->login();
        break;
    case 'logout':
        echo $auth->logout();
        break;
    case 'elogin':
        echo $auth->employee_login();
        break;
    case 'elogout':
        echo $auth->employee_logout();
        break;
    default:
        echo $auth->index();
        break;
}
