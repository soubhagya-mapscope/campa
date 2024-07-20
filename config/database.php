<?php
// echo "jii";exit;
class Database {
    // private $host = "192.168.1.49";
    // private $port = "5432";
    // private $db_name = "campa";
    // private $username = "postgres";
    // private $password = "postgres";
    private $host = "164.164.122.69";
    private $port = "5432";
    private $db_name = "campa";
    private $username = "app_amns";
    private $password = 'U$er@Mn$2024';
    public $conn;


    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
