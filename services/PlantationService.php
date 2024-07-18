<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PlantationData.php';
require_once __DIR__ . '/../models/DroneMonitoringData.php';

class PlantationService {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getFilteredPlantations($filters) {
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

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPlantationById($id) {
        $query = "SELECT * FROM plantation_data WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDroneDataByPlantationId($plantation_id) {
        $query = "SELECT * FROM drone_monitoring_data WHERE plantation_id = :plantation_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':plantation_id', $plantation_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getUniqueCircles() {
        $query = "SELECT DISTINCT circle_name FROM plantation_data";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function getUniqueDivisions() {
        $query = "SELECT DISTINCT division_name FROM plantation_data";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function getUniqueRanges() {
        $query = "SELECT DISTINCT range_name FROM plantation_data";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getUniqueSchemes() {
        $query = "SELECT DISTINCT scheme FROM plantation_data";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    
    
}
?>
