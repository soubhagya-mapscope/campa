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
                    <div id="my-map-canvas" style="height:100%; width:100%;max-width:100%;">
                        <iframe style="height:100%;width:100%;border:0;" frameborder="0" src="https://www.google.com/maps/embed/v1/place?q=bhuba&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8">
                        </iframe>
                    </div><a class="google-map-code-enabler" href="https://www.bootstrapskins.com/themes" id="authorize-map-data">
                        premium bootstrap themes</a>
                    <style>
                        #my-map-canvas .text-marker {}

                        .map-generator {
                            max-width: 100%;
                            max-height: 100%;
                            background: none;
                        }
                    </style>
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
        // Initialize map
        function initMap() {
            var plantationLocation = {
                lat: "20.3409" ? > ,
                lng: <?php 85.8057; ?>
            };
            var map = new google.maps.Map(document.getElementById('plantationMap'), {
                zoom: 15,
                center: plantationLocation
            });
            var marker = new google.maps.Marker({
                position: plantationLocation,
                map: map
            });
        }

        // Load Google Maps script dynamically
        function loadScript(src, callback) {
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = src;
            script.onload = callback;
            document.head.appendChild(script);
        }

        loadScript('https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap', initMap);
    </script>

<?php
} else {
    echo 'Invalid request.';
}
?>