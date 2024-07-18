<?php
class PlantationData {
    private $conn;
    private $table_name = "plantation_data";

    public $id;
    public $circle_name;
    public $division_name;
    public $range_name;
    public $area_gps;
    public $area_target;
    public $area_achievement;
    public $pit_target;
    public $pit_achievement;
    public $seedling_target;
    public $seedling_achievement;
    public $scheme;
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
        $query = "INSERT INTO " . $this->table_name . " SET circle_name=:circle_name, division_name=:division_name, range_name=:range_name, area_gps=:area_gps, area_target=:area_target, area_achievement=:area_achievement, pit_target=:pit_target, pit_achievement=:pit_achievement, seedling_target=:seedling_target, seedling_achievement=:seedling_achievement, scheme=:scheme, geom=:geom, created_by=:created_by";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":circle_name", $this->circle_name);
        $stmt->bindParam(":division_name", $this->division_name);
        $stmt->bindParam(":range_name", $this->range_name);
        $stmt->bindParam(":area_gps", $this->area_gps);
        $stmt->bindParam(":area_target", $this->area_target);
        $stmt->bindParam(":area_achievement", $this->area_achievement);
        $stmt->bindParam(":pit_target", $this->pit_target);
        $stmt->bindParam(":pit_achievement", $this->pit_achievement);
        $stmt->bindParam(":seedling_target", $this->seedling_target);
        $stmt->bindParam(":seedling_achievement", $this->seedling_achievement);
        $stmt->bindParam(":scheme", $this->scheme);
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
