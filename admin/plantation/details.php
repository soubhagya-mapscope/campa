<?php
require_once '../../services/PlantationService.php';

$plantationService = new PlantationService();
$plantationId = $_GET['id'];

$plantation = $plantationService->getPlantationById($plantationId);
$droneData = $plantationService->getDroneDataByPlantationId($plantationId);
?>
<h5>Plantation Details</h5>
<table class="table table-bordered">
    <tr>
        <th>Circle Name</th>
        <td><?php echo $plantation['circle_name']; ?></td>
    </tr>
    <tr>
        <th>Division Name</th>
        <td><?php echo $plantation['division_name']; ?></td>
    </tr>
    <tr>
        <th>Range Name</th>
        <td><?php echo $plantation['range_name']; ?></td>
    </tr>
    <tr>
        <th>Name</th>
        <td><?php echo $plantation['name']; ?></td>
    </tr>
    <tr>
        <th>Surveyed Area</th>
        <td><?php echo $plantation['area_gps']; ?></td>
    </tr>
    <tr>
        <th>Scheme</th>
        <td><?php echo $plantation['scheme']; ?></td>
    </tr>
    <tr>
        <th>Plantation Date</th>
        <td><?php echo $plantation['created_on']; ?></td>
    </tr>
</table>

<h5>Drone Monitoring Data</h5>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Data</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($droneData as $data): ?>
        <tr>
            <td><?php echo $data['id']; ?></td>
            <td><?php echo $data['data']; ?></td>
            <td><?php echo $data['date']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
