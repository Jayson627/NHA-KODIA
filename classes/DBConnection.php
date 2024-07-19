<?php
if (!defined('DB_SERVER')) {
    require_once("../initialize.php");
}

class DBConnection {

    private $host = "127.0.0.1:3306";
    private $username = "u510162695_sis_db";
    private $password = "1Sis_dbpassword";
    private $database = "u510162695_sis_db";
    
    public $conn;
    
    public function __construct() {
        if (!isset($this->conn)) {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            
            // Check for connection errors
            if ($this->conn->connect_error) {
                die('Connection failed: ' . $this->conn->connect_error);
            }            
        }    
    }
    
    public function __destruct() {
        $this->conn->close();
    }
}
?>
