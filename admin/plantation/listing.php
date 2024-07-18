<?php
require_once '../../middleware/AuthMiddleware.php';
require_once '../../services/PlantationService.php';

AuthMiddleware::check();

$plantationService = new PlantationService();

$filters = [
    'circle' => $_GET['circle'] ?? null,
    'division' => $_GET['division'] ?? null,
    'range' => $_GET['range'] ?? null,
    'scheme' => $_GET['scheme'] ?? null,
    'date' => $_GET['date'] ?? null,
];

$plantations = $plantationService->getFilteredPlantations($filters);
$uniqueCircles = $plantationService->getUniqueCircles();
$uniqueDivisions = $plantationService->getUniqueDivisions();
$uniqueRanges = $plantationService->getUniqueRanges();
$uniqueScheme = $plantationService->getUniqueSchemes();
?>
<?php include '../templates/header.php'; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Plantation</h1> 

    <!-- Filters -->
    <form method="GET" action="listing.php">
        <div class="row mb-4">
            <div class="col-md-2">
                <select name="circle" class="form-control">
                    <option value="">Select Circle</option>
                    <?php foreach ($uniqueCircles as $circle): ?>
                        <option value="<?php echo $circle; ?>" <?php echo $filters['circle'] == $circle ? 'selected' : ''; ?>><?php echo $circle; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="division" class="form-control">
                    <option value="">Select Division</option>
                    <?php foreach ($uniqueDivisions as $division): ?>
                        <option value="<?php echo $division; ?>" <?php echo $filters['division'] == $division ? 'selected' : ''; ?>><?php echo $division; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="range" class="form-control">
                    <option value="">Select Range</option>
                    <?php foreach ($uniqueRanges as $range): ?>
                        <option value="<?php echo $range; ?>" <?php echo $filters['range'] == $range ? 'selected' : ''; ?>><?php echo $range; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="scheme" class="form-control">
                    <option value="">Select Scheme</option>
                    <?php foreach ($uniqueScheme as $scheme): ?>
                        <option value="<?php echo $scheme; ?>" <?php echo $filters['scheme'] == $scheme ? 'selected' : ''; ?>><?php echo $scheme; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
             
            <div class="col-md-2">
                <input type="date" name="date" class="form-control" value="<?php echo $filters['date']; ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- DataTable -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Plantation Management</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Circle Name</th>
                            <th>Division Name</th>
                            <th>Range Name</th>
                            <th>Name</th>
                            <th>Surveyed Area</th>
                            <th>Scheme</th>
                            <th>Plantation Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($plantations as $plantation): ?>
                        <tr>
                            <td><?php echo $plantation['circle_name']; ?></td>
                            <td><?php echo $plantation['division_name']; ?></td>
                            <td><?php echo $plantation['range_name']; ?></td>
                            <td><?php echo $plantation['name']; ?></td>
                            <td><?php echo $plantation['area_gps']; ?></td>
                            <td><?php echo $plantation['scheme']; ?></td>
                            <td><?php echo $plantation['created_on']; ?></td>
                            <td>
                                <button class="btn btn-info" data-toggle="modal" data-target="#detailsModal" data-id="<?php echo $plantation['id']; ?>">View Details</button>
                                <button class="btn btn-primary" onclick="window.location.href='map.php?id=<?php echo $plantation['id']; ?>'">View on Map</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Plantation Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Plantation and Drone Data will be loaded here via AJAX -->
                </div>
            </div>
        </div>
    </div>

</div>
<?php include '../templates/footer.php'; ?>
<!-- /.container-fluid -->

<script>
$(document).ready(function() {
    $('#detailsModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var plantationId = button.data('id');
        var modal = $(this);
        $.ajax({
            url: 'details.php',
            type: 'GET',
            data: { id: plantationId },
            success: function(data) {
                modal.find('.modal-body').html(data);
            }
        });
    });
});
</script>
