<?php
class DronePitOutputData {
    private $conn;
    private $table_name = "drone_pit_output_data";

    public $id;
    public $plantation_id;
    public $drone_monitoring_data_id;
    public $geom;
    public $is_active;
    public $created_by;
    public $updated_by;
    public $created_on;
    public $updated_on;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET plantation_id=:plantation_id, drone_monitoring_data_id=:drone_monitoring_data_id, geom=:geom, created_by=:created_by";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":plantation_id", $this->plantation_id);
        $stmt->bindParam(":drone_monitoring_data_id", $this->drone_monitoring_data_id);
        $stmt->bindParam(":geom", $this->geom);
        $stmt->bindParam(":created_by", $this->created_by);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}
?>
