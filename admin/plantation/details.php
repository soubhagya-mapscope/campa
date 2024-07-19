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
    $lastMonitoringDate = !empty($droneData['max_drone_fly_date']) ? date('d-m-Y', strtotime($droneData['max_drone_fly_date'])) : "NA";
    $nextMonitoringDate = $lastMonitoringDate !== "NA" ? date('d-m-Y', strtotime($lastMonitoringDate . ' +6 months')) : "NA";
    $escapedPlantationName = htmlspecialchars($plantation['name'], ENT_QUOTES, 'UTF-8');

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
                            <div class="col-sm-6 row-label">stage Name:</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $droneData['stage_details']['name']?? 'NA'; ?></span></div>
                        </div>
                        <?php if ($droneData['stage_details']['id'] ==1) {?>
                            <div class="row mb-2">
                            <div class="col-sm-6 row-label">Pit (Target) (Nos):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['pit_target']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Pit (Achievement) (Nos):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['pit_achievement']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Pit Deviation (Nos):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $pit_deviation; ?></span></div>
                        </div>

                            <?php }?>


                            <?php if ($droneData['stage_details']['id'] ==2){ ?>

                                <div class="row mb-2">
                            <div class="col-sm-6 row-label">Seedling (Target) (Nos) :</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['seedling_target']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Seedling (Achievement) (Nos):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $plantation['seedling_achievement']; ?></span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6 row-label">Seedling Deviation (Nos):</div>
                            <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $seedling_deviation; ?></span></div>
                        </div>

                                    <?php }?>
                     

                    

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
                         transition: none;overflow:hidden;width:500px;height:600px;">
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

                            <fieldset class="pre-plantation-fieldset w-100">
                                <legend class="tag-header">Pre-Plantation Data <?php //echo ($_GET['id']) ;?></legend>
                                <!-- <h6 class="tag-header"><b>Pre-Plantation Data</b></h6> -->
                                <!-- <div class="card" style="width: 18rem;"> -->
                                <?php
                                if ($droneData['prePlantationData'] != null) {
                                ?>


                                    <div class="row mb-2">
                                        <div class="col-sm-6 row-label">Drone Fly Date:</div>
                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo date('d-m-Y', strtotime($droneData['prePlantationData']['drone_fly_date'])) ?? "NA"; ?></span></div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-sm-6 row-label">No. of Pits Identified:</div>
                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $droneData['prePlantationData']['no_of_pits'] ?? "NA"; ?></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6 row-label">Area Identified from Ortho (Ha)</div>
                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $droneData['prePlantationData']['area_identified'] ?? "NA"; ?></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6 row-label">No. of Pits as per Target:</div>
                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $droneData['prePlantationData']['no_of_pits_as_per_target'] ?? "NA"; ?></div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-sm-6 row-label">Target Area (Ha):</div>
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
                            <fieldset class="pre-plantation-fieldset w-100">
                                <legend class="tag-header">Post-Plantation Data</legend>

                                <div class="row">
                                    <?php
                                    if (count($droneData['postPlantationData']) > 0) {
                                        foreach ($droneData['postPlantationData'] as $data) : ?>

                                            <div class="col-lg-6">
                                                <div class="card mt-2 mb-4 px-4 py-4">

                                                    <div class="row mb-2">
                                                        <div class="col-sm-6 row-label">Drone Fly Date:</div>
                                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo date('d-m-Y', strtotime($data['drone_fly_date']))  ?? "NA"; ?></span></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-6 row-label">No. of Pits Identified:</div>
                                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $data['no_of_pits'] ?? "NA"; ?></span></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-6 row-label">Area Identified from Ortho (Ha):</div>
                                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"> <?php echo $data['area_identified'] ?? "NA"; ?></span></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-6 row-label">No. of Pits as per Target:</div>
                                                        <div class="col-sm-6 badge-box-back"><span class="badge-box"><?php echo $data['no_of_pits_as_per_target'] ?? "NA"; ?></span></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-6 row-label">Target Area (Ha):</div>
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
                                        echo "<p class='ml-4'>No post-plantation data available.</p>";
                                    }
                                    ?>
                                </div>
                            </fieldset>
                        </div>

                        <!-- <div class="col-md-12">

                            <fieldset class="pre-plantation-fieldset">
                                <legend class="tag-header">Drone Flight Schedule</legend>
                             
                                <?php
                                if ($droneData['prePlantationData'] != null) {
                                ?>

                                    <table class="table table-bordered custom-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Year</th>
                                                <th scope="col">Scheduled Date</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Pre Plantation</td>
                                                <td><span class="badge-box-map-view quarterly">01-05-2024</span></td>
                                                <td><span class="badge-box-map-view completed">Completed</span></td>
                                            </tr>
                                            <tr>
                                                <td>Pre Plantation</td>
                                                <td><span class="badge-box-map-view quarterly">01-05-2024</span></td>
                                                <td><span class="badge-box-map-view completed">Completed</span></td>
                                            </tr>
                                            <tr>
                                                <td>Pre Plantation</td>
                                                <td><span class="badge-box-map-view quarterly">01-05-2024</span></td>
                                                <td><span class="badge-box-map-view completed">Completed</span></td>
                                            </tr>
                                        </tbody>
                                    </table>


                                <?php

                                } else {
                                    echo "<p>No pre-plantation data available.</p>";
                                }
                                ?>


                            </fieldset> 
                        
                        </div> -->


                        <?php
                        // Sample data for different IDs
                        $sampleData = [
                            1 => [
                                ['year' => 'Pre-plantation', 'scheduled_date' => '12-06-2024', 'status' => 'done', 'status_class' => 'completed'],
                                ['year' => '1yr (1st Half)', 'scheduled_date' => '02-12-2024', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '1yr (2nd Half)', 'scheduled_date' => '11-06-2025', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '2yr (1st Half)', 'scheduled_date' => '11-12-2025', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '2yr (2nd Half)', 'scheduled_date' => '16-06-2026', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '3yr (1st Half)', 'scheduled_date' => '02-12-2026', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '3yr (2nd Half)', 'scheduled_date' => '17-06-2027', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '4yr (1st Half)', 'scheduled_date' => '11-12-2027', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '4yr (2nd Half)', 'scheduled_date' => '18-06-2028', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '5yr (1st Half)', 'scheduled_date' => '11-12-2028', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '5yr (2nd Half)', 'scheduled_date' => '11-06-2029', 'status' => 'not done', 'status_class' => 'pending'],
                               
                            ],
                            3 => [
                               // ['year' => 'Pre-plantation', 'scheduled_date' => '12-06-2024',, 'status' => 'done', 'status_class' => 'completed'],
                                ['year' => '1yr (1st Half)', 'scheduled_date' => '02-12-2024', 'status' => 'done', 'status_class' => 'completed'],
                                ['year' => '1yr (2nd Half)', 'scheduled_date' => '11-06-2025', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '2yr (1st Half)', 'scheduled_date' => '11-12-2025', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '2yr (2nd Half)', 'scheduled_date' => '16-06-2026', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '3yr (1st Half)', 'scheduled_date' => '02-12-2026', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '3yr (2nd Half)', 'scheduled_date' => '17-06-2027', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '4yr (1st Half)', 'scheduled_date' => '11-12-2027', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '4yr (2nd Half)', 'scheduled_date' => '18-06-2028','status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '5yr (1st Half)', 'scheduled_date' => '11-12-2028', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '5yr (2nd Half)', 'scheduled_date' => '11-06-2029', 'status' => 'not done', 'status_class' => 'pending'],
                            ],
                            6 => [
                            // ['year' => 'Pre-plantation', 'scheduled_date' => '12-06-2024', 'status' => 'done', 'status_class' => 'completed'],
                            // ['year' => '1yr (1st Half)', 'scheduled_date' => '02-12-2024', 'status' => 'done', 'status_class' => 'completed'],
                            // ['year' => '1yr (2nd Half)', 'scheduled_date' => '11-06-2025', 'status' => 'not done', 'status_class' => 'pending'],
                           
                                ['year' => '2yr (1st Half)', 'scheduled_date' => '11-12-2025', 'status' => 'done', 'status_class' => 'completed'],
                                ['year' => '2yr (2nd Half)', 'scheduled_date' => '16-06-2026', 'status' => 'done', 'status_class' => 'completed'],
                                ['year' => '3yr (1st Half)', 'scheduled_date' => '02-12-2026', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '3yr (2nd Half)', 'scheduled_date' => '17-06-2027', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '4yr (1st Half)', 'scheduled_date' => '11-12-2027', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '4yr (2nd Half)', 'scheduled_date' => '18-06-2028', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '5yr (1st Half)', 'scheduled_date' => '11-12-2028', 'status' => 'not done', 'status_class' => 'pending'],
                                ['year' => '5yr (2nd Half)', 'scheduled_date' => '11-06-2029', 'status' => 'not done', 'status_class' => 'pending'],
                            ]
                        ];

                        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
                        $droneData = isset($sampleData[$id]) ? $sampleData[$id] : null;
                        ?>

                        <fieldset class="pre-plantation-fieldset w-100">
                            <legend class="tag-header">Drone Flight Schedule</legend>

                            <?php if ($droneData != null) : ?>
                                <table class="table table-bordered custom-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Year</th>
                                            <th scope="col">Scheduled Date</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($droneData as $data) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($data['year']); ?></td>
                                                <td><span class="badge-box-map-view quarterly"><?php echo htmlspecialchars($data['scheduled_date']); ?></span></td>
                                                <td><span class="badge-box-map-view <?php echo htmlspecialchars($data['status_class']); ?>"><?php echo htmlspecialchars($data['status']); ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>No pre-plantation data available.</p>
                            <?php endif; ?>
                        </fieldset>

                    </div>
                </div>

            </div>
        </div>
    </div>
    
<?php
} else {
    echo 'Invalid request.';
}
?>