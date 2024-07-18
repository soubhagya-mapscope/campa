<?php
class User {
    private $conn;
    private $table_name = "user_m";

    public $id;
    public $name;
    public $email;
    public $password;
    public $username;
    public $mobile_number;
    public $is_active;
    public $created_by;
    public $updated_by;
    public $created_on;
    public $updated_on;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, email=:email, password=:password, username=:username, mobile_number=:mobile_number, created_by=:created_by";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":mobile_number", $this->mobile_number);
        $stmt->bindParam(":created_by", $this->created_by);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getUserByEmail() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        return $stmt;
    }
}
?>
