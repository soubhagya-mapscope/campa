<?php
require_once '../../middleware/AuthMiddleware.php';
require_once '../../services/PlantationService.php';

AuthMiddleware::check();

if (isset($_GET['id'])) {
    $plantationService = new PlantationService();
    $plantation = $plantationService->getPlantationById($_GET['id']);
    $droneData = $plantationService->getDroneDataByPlantationId($_GET['id']);
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
                            <div class="col-sm-6 row-label">Plantation Area (GPS Area):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['area_gps']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Target Area:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['area_target']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Seedling (Target):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['seedling_target']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Seedling (Achieved):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['seedling_achievement']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Pit (Target):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['pit_target']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Pit (Achieved):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['pit_achievement']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Last Monitoring Date:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box">NA</span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Next Monitoring Date:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box">NA</span></div>
                        </div>
                        <!-- <div class="row mb-2">
                            <div class="col-sm-6 row-label">Last Monitoring Date:</div>
                            <div class="col-sm-6"><span class="badge-box"><?php echo $plantation['last_monitoring_date']?? "NA";  ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Next Monitoring Date:</div>
                            <div class="col-sm-6"><span class="badge-box"><?php echo $plantation['next_monitoring_date']?? "NA"; ?></span></div>
                        </div> -->
                    </div>
                  
                </div>
                    </div>
                    <!-- Right side with map -->
                    <div class="col-md-6">
                        <h5>Plantation Map</h5>
                        <div style="max-width:100%;list-style:none;
                         transition: none;overflow:hidden;width:500px;height:500px;">
                         <div id="my-map-canvas" style="height:100%; width:100%;max-width:100%;">
                            <iframe style="height:100%;width:100%;border:0;"
                             frameborder="0" src="https://www.google.com/maps/embed/v1/place?q=bhuba&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8">
                             </iframe></div><a class="google-map-code-enabler"
                              href="https://www.bootstrapskins.com/themes" id="authorize-map-data">
                                premium bootstrap themes</a>
                                <style>#my-map-canvas .text-marker{}.map-generator{max-width: 100%; max-height: 100%; background: none;}</style>
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
                    <div class="col-md-6">
                       
                        <h6>Pre-Plantation Data</h6> 

                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Drone Fly Date:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $droneData['prePlantationData'][0]['drone_fly_date'] ?? "NA"; ?></span></div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">No. of Pits Identified:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $droneData['prePlantationData'][0]['no_of_pits'] ?? "NA"; ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Area Identified from Ortho</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $droneData['prePlantationData'][0]['area_identified'] ?? "NA"; ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">No. of Pits as per Target:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $droneData['prePlantationData'][0]['no_of_pits_as_per_target'] ?? "NA" ; ?></div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Target Area:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $droneData['prePlantationData'][0]['target_area'] ?? "NA"; ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Survival Rate:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box">  <?php echo $droneData['prePlantationData'][0]['survival_rate'] ?? "NA"; ?></div>
                        </div>
                      
                 
                    </div>
                    <!-- Post-plantation data -->
                    <div class="col-md-6">
                        <h6>Post-Plantation Data</h6>
                        <?php 
                            if (count($droneData['postPlantationData']) > 0){
                                foreach ($droneData['postPlantationData'] as $data) : ?> 
                                    <p><strong>Drone Fly Date:</strong> <?php echo $data['drone_fly_date'] ?? "NA"; ?></p>
                                    <p><strong>No. of Pits Identified:</strong> <?php echo $data['no_of_pits'] ?? "NA"; ?></p>
                                    <p><strong>Area Identified from Ortho:</strong> <?php echo $data['area_identified'] ?? "NA"; ?></p>
                                    <p><strong>No. of Pits as per Target:</strong> <?php echo $data['no_of_pits_as_per_target'] ?? "NA"; ?></p>
                                    <p><strong>Target Area:</strong> <?php echo $data['target_area'] ?? "NA"; ?></p>
                                    <p><strong>Survival Rate:</strong> <?php echo $data['survival_rate'] ?? "NA"; ?></p> 
                            <?php 
                        
                        endforeach;
                    }
                    else {
                        echo "<p>No post-plantation data available.</p>";
                    }
                        ?>
                    </div>
                </div>
                </div>




               
            </div>
        </div>
            </div>
 

    <script>
        // Initialize map
        function initMap() {
            var plantationLocation = { lat: "20.3409" ?>, lng: <?php 85.8057; ?> };
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
