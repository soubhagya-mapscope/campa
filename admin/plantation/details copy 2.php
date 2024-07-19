<?php
require_once '../../middleware/AuthMiddleware.php';
require_once '../../services/PlantationService.php';

AuthMiddleware::check();

if (isset($_GET['id'])) {
    $plantationService = new PlantationService();
    $plantation = $plantationService->getPlantationById($_GET['id']);
    $droneData = $plantationService->getDroneDataByPlantationId($_GET['id']);

    // Calculate deviations
    $area_deviation = $plantation['area_gps'] - $plantation['area_target'];
    $pit_deviation = $plantation['pit_achievement'] - $plantation['pit_target'];
    $seedling_deviation = $plantation['seedling_achievement'] - $plantation['seedling_target'];
    // Calculate last and next monitoring dates
    $lastMonitoringDate = !empty($droneData['max_drone_fly_date']) ? $droneData['max_drone_fly_date'] : "NA";
    $nextMonitoringDate = $lastMonitoringDate !== "NA" ? date('Y-m-d', strtotime($lastMonitoringDate . ' +6 months')) : "NA";
?>

    <div class="modal-body">
        <div class="row">
            <!-- Left side with plantation details -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Plantation Details</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $area_deviation = $plantation['area_gps'] - $plantation['area_target'];
                        $pit_deviation = $plantation['pit_achievement'] - $plantation['pit_target'];
                        $seedling_deviation = $plantation['seedling_achievement'] - $plantation['seedling_target'];
                        ?>

                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Circle Name:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['circle_name']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Division Name:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['division_name']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Range Name:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['range_name']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Section Name:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['section_name']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Beat Name:</div>
                            <div class="col-sm-6  badge-box-back"><span class="badge-box"><?php echo $plantation['beat_name']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Plantation Name:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['name']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Plantation Area (Ha):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['area_gps']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Target Area (Ha):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['area_target']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Area Deviation (Ha):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $area_deviation; ?></span></div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Pit (Target) (Ha):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['pit_target']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Pit (Achievement) (Ha):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['pit_achievement']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Pit Deviation (Ha):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $pit_deviation; ?></span></div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Seedling (Target) (Ha) :</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['seedling_target']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Seedling (Achievement) (Ha):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['seedling_achievement']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Seedling Deviation (Ha):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $seedling_deviation; ?></span></div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Last Monitoring Date:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $lastMonitoringDate; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Next Monitoring Date:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $nextMonitoringDate; ?></span></div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Right side with map -->
            <div class="col-md-6">
                <h5>Plantation Map</h5>
                <div style="max-width:100%;list-style:none;
                         transition: none;overflow:hidden;width:500px;height:500px;">
                    <div id="plantationMap" style="height:100%; width:100%; max-width:100%;"></div>

                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">


                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Drone Data Analysis</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Pre-plantation data -->
                        <div class="col-md-12">

                            <fieldset class="pre-plantation-fieldset">
                                <legend class="tag-header">Pre-Plantation Data</legend>
                                <!-- <h6 class="tag-header"><b>Pre-Plantation Data</b></h6> -->
                                <!-- <div class="card" style="width: 18rem;"> -->
                                <?php
                                if ($droneData['prePlantationData'] != null) {
                                ?>


                                    <div class="row mb-2">
                                        <div class="col-sm-6 row-label">Drone Fly Date:</div>
                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $droneData['prePlantationData']['drone_fly_date'] ?? "NA"; ?></span></div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-sm-6 row-label">No. of Pits Identified:</div>
                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $droneData['prePlantationData']['no_of_pits'] ?? "NA"; ?></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6 row-label">Area Identified from Ortho</div>
                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $droneData['prePlantationData']['area_identified'] ?? "NA"; ?></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6 row-label">No. of Pits as per Target:</div>
                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $droneData['prePlantationData']['no_of_pits_as_per_target'] ?? "NA"; ?></div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-sm-6 row-label">Target Area:</div>
                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $droneData['prePlantationData']['target_area'] ?? "NA"; ?></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6 row-label">Survival Rate:</div>
                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $droneData['prePlantationData']['survival_rate'] ?? "NA"; ?></div>
                                    </div>

                                <?php


                                } else {
                                    echo "<p>No pre-plantation data available.</p>";
                                }
                                ?>


                            </fieldset> <!-- </div> -->
                        </div>
                        <!-- Post-plantation data -->
                        <div class="col-md-12 mt-4">
                            <fieldset class="pre-plantation-fieldset">
                                <legend class="tag-header">Post-Plantation Data</legend>

                                <div class="row">
                                    <?php
                                    if (count($droneData['postPlantationData']) > 0) {
                                        foreach ($droneData['postPlantationData'] as $data) : ?>

                                            <div class="col-lg-6">
                                                <div class="card mt-2 mb-4 px-4 py-4">

                                                    <div class="row mb-2">
                                                        <div class="col-sm-6 row-label">Drone Fly Date:</div>
                                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $data['drone_fly_date'] ?? "NA"; ?></span></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-6 row-label">No. of Pits Identified:</div>
                                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $data['no_of_pits'] ?? "NA"; ?></span></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-6 row-label">Area Identified from Ortho:</div>
                                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $data['area_identified'] ?? "NA"; ?></span></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-6 row-label">No. of Pits as per Target:</div>
                                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $data['no_of_pits_as_per_target'] ?? "NA"; ?></span></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-6 row-label">Target Area:</div>
                                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $data['target_area'] ?? "NA"; ?></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-6 row-label">Survival Rate:</div>
                                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $data['survival_rate'] ?? "NA"; ?></span></div>
                                                    </div>

                                                </div>
                                            </div>


                                    <?php

                                        endforeach;
                                    } else {
                                        echo "<p>No post-plantation data available.</p>";
                                    }
                                    ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>





            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            const osmLayer = new ol.layer.Tile({
                source: new ol.source.OSM(),
                title: "OpenStreetMap",
                type: "base",
                visible: true,
            });

            function initOpenLayersMap() {

                // alert();
                var map = new ol.Map({
                    target: 'plantationMap',
                    layers: [
                        new ol.layer.Tile({
                            source: new ol.source.TileWMS({
                                url: 'http://192.168.1.34:8080/geoserver/campa/wms?',
                                params: {
                                    'SERVICE': 'WMS',
                                    'VERSION': '1.1.0',
                                    'REQUEST': 'GetMap',
                                    'LAYERS': 'campa:plantation_data',
                                    'STYLES': '',
                                    'FORMAT': 'image/png',
                                    'SRS': 'EPSG:4326',
                                    'BBOX': '85.83226013183594,20.790882110595703,85.91511535644531,20.911762237548828'
                                }
                            })
                        }),
                    ],
                    view: new ol.View({
                        projection: 'EPSG:4326',
                        center: [85.87369, 20.85132],
                        zoom: 14
                    })
                });
                // Add these layers to your map
                map.addLayer(osmLayer);
            }

            // Call the function to initialize the map
            initOpenLayersMap();
        });
    </script>



<?php
} else {
    echo 'Invalid request.';
}
?>