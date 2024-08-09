<?php
require_once '../../middleware/AuthMiddleware.php';
require_once '../../services/PlantationService.php';

AuthMiddleware::check();

if (isset($_GET['name']) || isset($_GET['id'])) {
    $plantationService = new PlantationService();
    $plantation = $plantationService->getPlantationById($_GET['id']);
    $plantationName = $plantation['name'];
    $plantationGeojson = $plantation['geojson'] ?? null;
    echo "<script>var plantationName = '" . htmlspecialchars($plantationName) . "';</script>";
    echo "<script>var plantationGeojson = " . json_encode($plantationGeojson) . ";</script>";
} else {
    echo "<script>var plantationName = null;</script>";
    echo "<script>var plantationName = null;</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campa - Mapview</title>
    <link rel="icon" href="../../public/images/icons/favicon.ico" sizes="any" />
    <link rel="icon" href="../../public/images/icons/icon.svg" type="image/svg+xml" />
    <link rel="apple-touch-icon" href="../../public/images/icons/apple-touch-icon.png" />
    <link rel="manifest" href="../../public/images/icons/manifest.webmanifest" />
    <!-- <link href="../../public/css/sb-admin-2.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v7.3.0/ol.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/ol-layerswitcher@4.1.1/dist/ol-layerswitcher.css">
    <!-- <link rel="stylesheet" href="assets/mapview-style.css"> -->
    <link rel="stylesheet" href="../../campa_FE/assets/mapview-style.css">
    <style>
        .map {
            width: 100%;
            height: 100vh;
            position: relative;
        }

        .ol-zoomslider {
            right: 10px !important;
            /* Position 10px from the right */
            left: auto !important;
            /* Override default left position */
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand navbar-light bg-white topbar  static-top shadow">

        <a class="no-underline" style="text-decoration: none;" href="/campa/admin/plantation/">
            <div class="img-box-logo-head">

                <img src="../../public/images/LOGO-FPPS.svg" class="logo-ico">
                <div class="logo-text">Forest Plantation Planning and Monitoring System</div>

            </div>
        </a>

        <ul class="navbar-nav ml-auto">


            <li class="nav-item dropdown no-arrow d-sm-none">
                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-search fa-fw"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                    <form class="form-inline mr-auto w-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>


            <div class="top-head-image-icon">
                <img src="../../public/images/odisha-forest-black.png" class="image-hed-top">
                <span>Forest, Environment and Climate Change Department<br>
                    Government of Odisha
                </span>
            </div>


            <div class="topbar-divider d-none d-sm-block"></div>


            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>

                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                        Settings
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                        Activity Log
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>

        </ul>

    </nav>


    <div class="map-container">
        <div id="map">
            <button id="fullscreen-btn" class="custom-fullscreen-btn">
                <i class="fas fa-expand"></i>
            </button>
        </div>
        <!-- <button class="btn btn-primary" id="drawerToggle">Open Drawer</button> -->

        <div id="featureInfoDrawer" class="drawer">
            <div class="drawer-content">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-4">Feature Information</h5>
                    <button id="closeDrawer" class="btn btn-secondary mb-4">Close</button>
                </div>
                <div class="loader-container hideDiv">
                    <div id="loader" class="lds-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
                <div id="featureInfoContent"></div>

            </div>
        </div>
        <div class="map-overlay">
            <div id="layer-switcher" class="ol-unselectable ol-control">
                <button id="layer-switcher-btn" class="btn btn-primary"  sty data-bs-toggle="modal" data-bs-target="#layerModal">
                    Select Layer
                </button>
            </div>
            <div id="zoom-controls" class="ol-unselectable">
                <button id="zoom-in" class="ol-zoom-in" title="Zoom in">+</button>
                <button id="zoom-out" class="ol-zoom-out" title="Zoom out">−</button>
            </div>
            <!-- <div id="layer-select" class="ol-unselectable">
                <button id="layers-modal-btn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#layerSelectModal">
                    <span><i class="fas fa-layer-group"></i></span>
                </button>
            </div> -->

            <div id="layer-select" class="ol-unselectable">
                <button id="layers-modal-btn" class="btn btn-primary">
                    <span><i class="fas fa-layer-group"></i></span>
                </button>
            </div>
            <!-- <div id="filter" class="ol-unselectable">
                <button id="filter-btn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterLayerModal">
                    <span><i class="fas fa-filter"></i></span>
                </button>
            </div> -->
        </div>
    </div>

    <!-- Base Map Selector Modal -->
    <div class="modal fade" id="layerModal" tabindex="-1" aria-labelledby="layerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="layerModalLabel">Select Base Layer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card" data-layer="openstreetmap">
                                <img src="../../campa_FE/assets/images/osm.jpg" class="card-img-top" alt="OpenStreetMap">

                                <div class="card-body">
                                    <p class="card-title">Open Street Map</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card" data-layer="google street">
                                <img src="../../campa_FE/assets/images/street.png" class="card-img-top" alt="GoogleStreetMap">
                                <div class="card-body">
                                    <p class="card-title">Google Street</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card" data-layer="satellite">
                                <img src="../../campa_FE/assets/images/satellite.png" class="card-img-top" alt="Satellite">
                                <div class="card-body">
                                    <p class="card-title">Satellite</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card" data-layer="terrain">
                                <img src="../../campa_FE/assets/images/terrain.png" class="card-img-top" alt="Terrain">
                                <div class="card-body">
                                    <p class="card-title">Terrain</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Layer Select Modal -->

    <div id="layerSelectDiv">
        <div class="header">
            <h6>Layers</h6>
            <button type="button" id="closeLayerSelect" class="btn-close"></button>
        </div>
        <div class="bodymap">

            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                            <strong>OFMS Layer</strong>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                        <div class="accordion-body">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="divBnd">
                                <label class="form-check-label" for="divBnd">Division Boundary</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rngBnd">
                                <label class="form-check-label" for="rngBnd">Range Boundary</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="secBnd">
                                <label class="form-check-label" for="secBnd">Section Boundary</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="beatBnd">
                                <label class="form-check-label" for="beatBnd">Beat Boundary</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="fbBnd">
                                <label class="form-check-label" for="fbBnd">FB Boundary</label>
                            </div>
                            <!-- <div class="form-check">
										<input class="form-check-input" type="checkbox" id="poi3">
										<label class="form-check-label" for="poi3">Attractions</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="poi4">
										<label class="form-check-label" for="poi4">Shopping</label>
									</div> -->
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                        <button class="accordion-button panel-collapse collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                            <strong>Drone Survey</strong>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                        <div class="accordion-body">
                            <!-- <div class="form-check">
										<input class="form-check-input" type="checkbox" id="transport1">
										<label class="form-check-label" for="transport1">Image-1</label>
									</div> -->
                            <!-- <div class="form-check">
										<input class="form-check-input" type="checkbox" id="transport3">
										<label class="form-check-label" for="transport3">Image-3</label>
									</div> -->
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="nature1">
                                <label class="form-check-label" for="nature1">Plantation</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="transport6">
                                <label class="form-check-label" for="nature2">Plantation Boundary (Drone)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="transport4">
                                <label class="form-check-label" for="nature1">Plantation Analysis (Drone)</label>
                            </div>
                            <div class="form-check">
                                <input type="range" id="transparencySliderPlant" min="0" max="100" value="100" style="display: none">
                                <label class="form-check-label" for="transparencySliderPlant" style="display: none"><strong>Plantation Opacity</strong></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="transport5">
                                <label class="form-check-label" for="nature2">Pits Analysis (Drone)</label>
                            </div>
                            <div class="form-check">
                                <input type="range" id="transparencySliderPit" min="0" max="100" value="100" style="display: none">
                                <label class="form-check-label" for="transparencySliderPit" style="display: none"><strong>Pit Opacity</strong></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="transport2">
                                <label class="form-check-label" for="transport2">Ortho</label>
                            </div>
                            <div class="form-check">
                                <input type="range" id="transparencySlider" min="0" max="100" value="100" style="display: none">
                                <label class="form-check-label" for="transparencySlider" style="display: none"><strong>Ortho Opacity</strong></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                            <strong>Base Map</strong>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                        <div class="accordion-body">
                            <div class="container">
                            <div class="row">
                                <div class="col p-0 mx-1">
                                    <div class="card" data-layer="openstreetmap">
                                        <img src="../../campa_FE/assets/images/osm.jpg" class="card-img-top" alt="OpenStreetMap">
                                            <!-- <p class="card-title">Open Street Map</p> -->
                                    </div>
                                </div>
                                <div class="col p-0 mx-1">
                                    <div class="card" data-layer="google street">
                                        <img src="../../campa_FE/assets/images/street.png" class="card-img-top" alt="GoogleStreetMap">
                                        <!-- <div class="card-body">
                                            <p class="card-title">Google Street</p>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="col p-0 mx-1">
                                    <div class="card" data-layer="satellite">
                                        <img src="../../campa_FE/assets/images/satellite.png" class="card-img-top" alt="Satellite">
                                        <!-- <div class="card-body">
                                            <p class="card-title">Satellite</p>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="col p-0 mx-1">
                                    <div class="card" data-layer="terrain">
                                        <img src="../../campa_FE/assets/images/terrain.png" class="card-img-top" alt="Terrain">
                                        <!-- <div class="card-body">
                                            <p class="card-title">Terrain</p>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
                            <strong>Swipe layers</strong>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFour">
                        <div class="accordion-body">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="urban1">
                                <label class="form-check-label" for="urban1">Active Swipe Layer</label>
                            </div>

                            <!-- <div class="form-check">
										<input class="form-check-input" type="checkbox" id="urban2">
										<label class="form-check-label" for="urban2">Neighborhoods</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="urban3">
										<label class="form-check-label" for="urban3">City Boundaries</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="urban4">
										<label class="form-check-label" for="urban4">Land Use</label>
									</div> -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="swip-set">
        <input type="range" class="form-range w-20" id="swiplayerID" style="display: none" />
    </div>


    <!-- Filter Layer  Modal -->

    <div class="modal fade" id="filterLayerModal" tabindex="-1" aria-labelledby="filterLayerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xs modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="filterLayerModalLabel">Filter Layers</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm">
                        <div class="mb-3">
                            <label for="filterSelect1" class="form-label">Filter by Circle</label>
                            <select class="form-select" id="filterSelect1">
                                <option selected>Choose...</option>
                                <option value="forest">Forest Layers</option>
                                <option value="drone">Drone Image Analysis</option>
                                <option value="plantation">Plantation site</option>
                                <option value="waterbody">Waterbody</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="filterSelect2" class="form-label">Filter by Division</label>
                            <select class="form-select" id="filterSelect2">
                                <option selected>Choose...</option>
                                <option value="boundary">Boundary</option>
                                <option value="analysis">Analysis</option>
                                <option value="image">Image</option>
                                <option value="site">Site</option>
                                <option value="water">Water</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="filterSelect3" class="form-label">Filter by Plantation Name</label>
                            <select class="form-select" id="filterSelect3">
                                <option selected>Choose...</option>
                                <option value="boundary">Boundary</option>
                                <option value="analysis">Analysis</option>
                                <option value="image">Image</option>
                                <option value="site">Site</option>
                                <option value="water">Water</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="applyFilter()">Apply Filter</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ol@v7.3.0/dist/ol.js"></script>
    <script src="https://unpkg.com/ol-layerswitcher@4.1.1"></script>
    <!-- <script src="assets/mapview-script.js"></script> -->
    <script src="../../campa_FE/assets/mapview-script.js"></script>

    <script>
        document.getElementById('layers-modal-btn').addEventListener('click', function() {
            document.getElementById('layerSelectDiv').style.display = 'block';
        });

        document.getElementById('closeLayerSelect').addEventListener('click', function() {
            document.getElementById('layerSelectDiv').style.display = 'none';
        });
    </script>


</body>

</html>