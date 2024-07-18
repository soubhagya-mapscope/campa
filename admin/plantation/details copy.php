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
                <h5>Plantation Details</h5>
                <p><strong>Circle Name:</strong> <?php echo $plantation['circle_name']; ?></p>
                <p><strong>Division Name:</strong> <?php echo $plantation['division_name']; ?></p>
                <p><strong>Range Name:</strong> <?php echo $plantation['range_name']; ?></p>
                <p><strong>Section Name:</strong> <?php echo $plantation['section_name'];; ?></p>
                <p><strong>Beat Name:</strong> <?php echo $plantation['beat_name']; ?></p>
                <p><strong>Plantation Name:</strong> <?php echo $plantation['name']; ?></p>
                <p><strong>Plantation Area (GPS Area):</strong> <?php echo $plantation['area_gps']; ?></p>
                <p><strong>Target Area:</strong> <?php echo $plantation['area_target']; ?></p>
                <p><strong>Seedling (Target):</strong> <?php echo $plantation['seedling_target']; ?></p>
                <p><strong>Seedling (Achieved):</strong> <?php echo $plantation['seedling_achievement']; ?></p>
                <p><strong>Pit (Target):</strong> <?php echo $plantation['pit_target']; ?></p>
                <p><strong>Pit (Achieved):</strong> <?php echo $plantation['pit_achievement']; ?></p>
                <p><strong>Last Monitoring Date:</strong> <?php echo $plantation['last_monitoring_date'] ?? "NA"; ?></p>
                 <p><strong>Next Monitoring Date:</strong> <?php echo $plantation['next_monitoring_date'] ?? "NA"; ?></p>
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
                <h5>Drone Data Analysis</h5>
                <div class="row">
                    <!-- Pre-plantation data -->
                    <div class="col-md-6">
                        <h6>Pre-Plantation Data</h6> 
                        <p><strong>Drone Fly Date:</strong> <?php echo $droneData['prePlantationData'][0]['drone_fly_date'] ?? "NA"; ?></p>
                        <p><strong>No. of Pits Identified:</strong> <?php echo $droneData['prePlantationData'][0]['no_of_pits'] ?? "NA"; ?></p>
                        <p><strong>Area Identified from Ortho:</strong> <?php echo $droneData['prePlantationData'][0]['area_identified'] ?? "NA"; ?></p>
                        <p><strong>No. of Pits as per Target:</strong> <?php echo $droneData['prePlantationData'][0]['no_of_pits_as_per_target'] ?? "NA" ; ?></p>
                        <p><strong>Target Area:</strong> <?php echo $droneData['prePlantationData'][0]['target_area'] ?? "NA"; ?></p>
                        <p><strong>Survival Rate:</strong> <?php echo $droneData['prePlantationData'][0]['survival_rate'] ?? "NA"; ?></p> 
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