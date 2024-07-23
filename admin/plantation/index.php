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
$uniqueSchemes = $plantationService->getUniqueSchemes();
?>
<?php include '../templates/header.php'; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->

    <fieldset class="pre-plantation-fieldset">
        <!-- <h1 class="h3 mb-2 text-gray-800">Plantation</h1> -->
        <legend class="tag-header-list">Plantation</legend>
        <!-- Filters -->
        <form id="search-form" method="GET" action="">
            <div class="row mb-4 align-items-md-end">
                <div class="col-md-2">
                    <label class="label">Select Circle</label>
                    <select name="circle" class="form-control">

                        <option value="">Select Circle</option>
                        <?php foreach ($uniqueCircles as $circle) : ?>
                            <option value="<?php echo $circle; ?>" <?php echo $filters['circle'] == $circle ? 'selected' : ''; ?>><?php echo $circle; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="label">Select Division</label>
                    <select name="division" class="form-control">
                        <option value="">Select Division</option>
                        <?php foreach ($uniqueDivisions as $division) : ?>
                            <option value="<?php echo $division; ?>" <?php echo $filters['division'] == $division ? 'selected' : ''; ?>><?php echo $division; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="label">Select Range</label>
                    <select name="range" class="form-control">
                        <option value="">Select Range</option>
                        <?php foreach ($uniqueRanges as $range) : ?>
                            <option value="<?php echo $range; ?>" <?php echo $filters['range'] == $range ? 'selected' : ''; ?>><?php echo $range; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="label">Select Scheme</label>
                    <select name="scheme" class="form-control">
                        <option value="">Select Scheme</option>
                        <?php foreach ($uniqueSchemes as $scheme) : ?>
                            <option value="<?php echo $scheme; ?>" <?php echo $filters['scheme'] == $scheme ? 'selected' : ''; ?>><?php echo $scheme; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="label">Plantation Date</label>
                    <input type="date" name="date" class="form-control" value="<?php echo $filters['date']; ?>">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <!-- <div class="col-md-1">
                    <button type="submit" class="btn btn-danger w-100">Reset</button>

                </div> -->

            </div>
        </form>
    </fieldset>
    <!-- DataTable -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-white">Plantation Monitoring</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-green" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-primary">
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
                        <?php foreach ($plantations as $plantation) : ?>
                            <tr>
                                <td><?php echo $plantation['circle_name']; ?></td>
                                <td><?php echo $plantation['division_name']; ?></td>
                                <td><?php echo $plantation['range_name']; ?></td>
                                <td><?php echo $plantation['name']; ?></td>
                                <td><?php echo $plantation['area_gps']; ?></td>
                                <td><?php echo $plantation['scheme']; ?></td>
                                <td><?php $date = new DateTime($plantation['plantation_date']);
                                    echo $date->format('d-m-Y'); ?></td>
                                <!-- <td>
                                <button class="btn btn-info" data-toggle="modal" data-target="#detailsModal" data-id="<?php echo $plantation['id']; ?>">
                                    <i class="fas fa-info-circle"></i> View Details
                                </button> </td>
                                <td> <button class="btn btn-primary" onclick="window.location.href='map.php?id=<?php echo $plantation['id']; ?>'">
                                    <i class="fas fa-map-marker-alt"></i> View on Map
                                </button>
                            </td> -->
                                <td>
                                    <div class="dropdown">
                                        <div class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-cog"></i> Action
                                        </div>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#detailsModal" data-id="<?php echo $plantation['id']; ?>" data-geojson='<?php echo json_encode($plantation['geojson']); ?>' data-name="<?php echo $plantation['name']; ?>">
                                                <i class="fas fa-info-circle"></i> View Details
                                            </a>

                                            <a class="dropdown-item" href="map?id=<?php echo $plantation['id']; ?>&name=<?php echo $plantation['name']; ?>" target="_blank">
                                                <i class="fas fa-map-marker-alt"></i> View on Map
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-upload"></i> Upload Drone Data
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-download"></i> Download Plantation KML
                                            </a>

                                        </div>
                                    </div>
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
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Plantation Details</h5> 
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
        // Handle the modal show event
        $('#detailsModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var plantationId = button.data('id'); // Extract info from data-* attributes
            var plantationName = button.data('name'); // Extract info from data-* attributes
            var plantationGeojson = button.data('geojson'); // Extract info from data-* attributes
            plantationGeojson = JSON.parse(plantationGeojson); // Parse the JSON string

            var modal = $(this); // Get the modal
            modal.find('.modal-body').html(` <div class="loader-container hideDiv" >
                <div id="loader" class="lds-ellipsis" >
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                </div>`);
            // Perform AJAX request to fetch details.php content
            $.ajax({
                url: 'details.php', // URL to fetch the details
                type: 'GET',
                data: {
                    id: plantationId // Send plantationId as a GET parameter
                },
                success: function(response) {
                    // Update the modal body with the fetched content
                    modal.find('.modal-body').html(response);
                    // Reinitialize the OpenLayers map inside the modal
                    initOpenLayersMap(plantationGeojson);
                },
                error: function() {
                    // Handle errors if the request fails
                    modal.find('.modal-body').html('<p>An error occurred while loading the details.</p>');
                }
            });
        });

        function initOpenLayersMap(plantationGeojson) {
            const satelliteLayer = new ol.layer.Tile({
                source: new ol.source.TileImage({
                    url: "https://mt1.google.com/vt/lyrs=s&hl=pl&&x={x}&y={y}&z={z}",
                    crossOrigin: "anonymous",
                }),
                title: "Satellite",
                type: "base",
                visible: true,
            });

            const wmsLayerStateBoundary = new ol.layer.Image({
                source: new ol.source.ImageWMS({
                    url: 'https://geoserver.amnslis.in/geoserver/Biju/wms',
                    params: {
                        'LAYERS': 'Biju:state_boundary',
                        'FORMAT': 'image/png',
                        'TRANSPARENT': true
                    },
                    serverType: 'geoserver'
                })
            });

            var map = new ol.Map({
                target: 'plantationMap',
                layers: [satelliteLayer, wmsLayerStateBoundary],
                view: new ol.View({
                    center: ol.proj.fromLonLat([84.44, 20.29]),
                    zoom: 7, // Adjust the initial zoom level as needed
                    minZoom: 5, // Set minimum zoom level
                    maxZoom: 25, // Set maximum zoom level
                })
            });

           // Add the plantation GeoJSON layer with style
           var vectorSource = new ol.source.Vector({
                features: new ol.format.GeoJSON().readFeatures(plantationGeojson, {
                    featureProjection: 'EPSG:3857'
                })
            });

            var vectorLayer = new ol.layer.Vector({
                source: vectorSource,
                style: new ol.style.Style({
                    fill: new ol.style.Fill({
                        color: 'rgba(0, 128, 0, 0.5)' // Green inside
                    }),
                    stroke: new ol.style.Stroke({
                        color: 'orange', // Orange boundary
                        width: 2
                    })
                })
            });

            map.addLayer(vectorLayer);

            // Zoom to the GeoJSON layer
            var extent = vectorSource.getExtent();
            map.getView().fit(extent, { duration: 1000, padding: [70, 70, 70, 70] });
        }
    });
</script>