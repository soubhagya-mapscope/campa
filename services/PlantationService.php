<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PlantationData.php';
require_once __DIR__ . '/../models/DroneMonitoringData.php';

class PlantationService
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getFilteredPlantations($filters)
    {
        $query = "SELECT * FROM plantation_data WHERE 1=1";
        $params = [];

        if (!empty($filters['circle'])) {
            $query .= " AND circle_name = :circle";
            $params[':circle'] = $filters['circle'];
        }

        if (!empty($filters['division'])) {
            $query .= " AND division_name = :division";
            $params[':division'] = $filters['division'];
        }

        if (!empty($filters['range'])) {
            $query .= " AND range_name = :range";
            $params[':range'] = $filters['range'];
        }

        if (!empty($filters['scheme'])) {
            $query .= " AND scheme = :scheme";
            $params[':scheme'] = $filters['scheme'];
        }

        if (!empty($filters['date'])) {
            $query .= " AND DATE(created_on) = :date";
            $params[':date'] = $filters['date'];
        }
        $query .= " order by id ASC";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPlantationById($id)
    {
        $query = "SELECT * FROM plantation_data WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUniqueCircles()
    {
        $query = "SELECT DISTINCT circle_name FROM plantation_data";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getUniqueDivisions()
    {
        $query = "SELECT DISTINCT division_name FROM plantation_data";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getUniqueRanges()
    {
        $query = "SELECT DISTINCT range_name FROM plantation_data";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getUniqueSchemes()
    {
        $query = "SELECT DISTINCT scheme FROM plantation_data";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }


    public function getDroneDataByPlantationId($plantation_id)
{
    $droneData = array(); 

    // Fetch pre-plantation data
    $queryPrePlantationData = "SELECT * FROM drone_monitoring_data WHERE plantation_id = :plantation_id and stage_id = 1 limit 1";
    $stmtPrePlantationData = $this->conn->prepare($queryPrePlantationData);
    $stmtPrePlantationData->bindParam(':plantation_id', $plantation_id);
    $stmtPrePlantationData->execute();

    // Fetch post-plantation data
    $queryPostPlantationData = "SELECT * FROM drone_monitoring_data WHERE plantation_id = :plantation_id and stage_id = 2";
    $stmtPostPlantationData = $this->conn->prepare($queryPostPlantationData);
    $stmtPostPlantationData->bindParam(':plantation_id', $plantation_id);
    $stmtPostPlantationData->execute();

    // Fetch the maximum drone_fly_date
    $queryMaxDroneFlyDate = "SELECT MAX(drone_fly_date) as max_drone_fly_date FROM drone_monitoring_data WHERE plantation_id = :plantation_id";
    $stmtMaxDroneFlyDate = $this->conn->prepare($queryMaxDroneFlyDate);
    $stmtMaxDroneFlyDate->bindParam(':plantation_id', $plantation_id);
    $stmtMaxDroneFlyDate->execute();

      // Fetch the maximum drone_fly_date
      $querystageDetails = "SELECT id, name FROM stage_m WHERE id=(select stage_id from drone_monitoring_data where  plantation_id = :plantation_id order by id desc limit 1)";
      $stmtstageDetails = $this->conn->prepare($querystageDetails);
      $stmtstageDetails->bindParam(':plantation_id', $plantation_id);
      $stmtstageDetails->execute();

    $droneData['prePlantationData'] = $stmtPrePlantationData->fetch(PDO::FETCH_ASSOC);
    $droneData['postPlantationData'] = $stmtPostPlantationData->fetchAll(PDO::FETCH_ASSOC);
    $droneData['max_drone_fly_date'] = $stmtMaxDroneFlyDate->fetch(PDO::FETCH_ASSOC)['max_drone_fly_date'];
    $droneData['stage_details'] = $stmtstageDetails->fetch(PDO::FETCH_ASSOC);
    // print_r($droneData);exit;
    return $droneData;
}

}
