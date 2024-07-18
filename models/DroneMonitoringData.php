<?php
class DroneMonitoringData {
    private $conn;
    private $table_name = "drone_monitoring_data";

    public $id;
    public $plantation_id;
    public $drone_fly_date;
    public $stage_id;
    public $no_of_pits;
    public $area_identified;
    public $no_of_seedling;
    public $survival_rate;
    public $year;
    public $is_active;
    public $created_by;
    public $updated_by;
    public $created_on;
    public $updated_on;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET plantation_id=:plantation_id, drone_fly_date=:dronefly_date, stage_id=:stage_id, no_of_pits=:no_of_pits, area_identified=:area_identified, no_of_seedling=:no_of_seedling, survival_rate=:survival_rate, year=:year, created_by=:created_by";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":plantation_id", $this->plantation_id);
        $stmt->bindParam(":drone_fly_date", $this->drone_fly_date);
        $stmt->bindParam(":stage_id", $this->stage_id);
        $stmt->bindParam(":no_of_pits", $this->no_of_pits);
        $stmt->bindParam(":area_identified", $this->area_identified);
        $stmt->bindParam(":no_of_seedling", $this->no_of_seedling);
        $stmt->bindParam(":survival_rate", $this->survival_rate);
        $stmt->bindParam(":year", $this->year);
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
