<?php
require_once '../../middleware/AuthMiddleware.php';
require_once '../../services/PlantationService.php';

AuthMiddleware::check();

if (isset($_GET['id'])) {
    // echo 'valid request.';
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Campa - Mapview</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v7.3.0/ol.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="stylesheet" href="https://unpkg.com/ol-layerswitcher@4.1.1/dist/ol-layerswitcher.css">
	<!-- <link rel="stylesheet" href="assets/mapview-style.css"> -->
    <link rel="stylesheet" href="../../campa_FE/assets/mapview-style.css">
</head>

<body>


<div class="map-container">
		<div id="map">
			<button id="fullscreen-btn" class="custom-fullscreen-btn">
				<i class="fas fa-expand"></i>
			</button>
		</div>
		<!-- <button class="btn btn-primary" id="drawerToggle">Open Drawer</button> -->

		<div id="featureInfoDrawer" class="drawer">
			<div class="drawer-content">
			  <h2>Feature Information</h2>
			  <div id="featureInfoContent"></div>
			  <button id="closeDrawer" class="btn btn-secondary">Close</button>
			</div>
		  </div>
		<div class="map-overlay">
			<div id="layer-switcher" class="ol-unselectable ol-control">
				<button id="layer-switcher-btn" class="btn btn-primary" data-bs-toggle="modal"
					data-bs-target="#layerModal">
					Select Layer
				</button>
			</div>
			<div id="zoom-controls" class="ol-unselectable">
				<button id="zoom-in" class="ol-zoom-in" title="Zoom in">+</button>
				<button id="zoom-out" class="ol-zoom-out" title="Zoom out">−</button>
			</div>
			<div id="layer-select" class="ol-unselectable">
				<button id="layers-modal-btn" class="btn btn-primary" data-bs-toggle="modal"
					data-bs-target="#layerSelectModal">
					<span><i class="fas fa-layer-group"></i></span>
				</button>
			</div>


			<div id="filter" class="ol-unselectable">
				<button id="filter-btn" class="btn btn-primary" data-bs-toggle="modal"
					data-bs-target="#filterLayerModal">
					<span><i class="fas fa-filter"></i></span>
				</button>
			</div>
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
								<img src="./assets/images/osm.jpg" class="card-img-top" alt="OpenStreetMap">
								<div class="card-body">
									<p class="card-title">Open Street Map</p>
								</div>
							</div>
						</div>
						<div class="col-md-3 mb-3">
							<div class="card" data-layer="google street">
								<img src="./assets/images/street.png" class="card-img-top" alt="GoogleStreetMap">
								<div class="card-body">
									<p class="card-title">Google Street</p>
								</div>
							</div>
						</div>
						<div class="col-md-3 mb-3">
							<div class="card" data-layer="satellite">
								<img src="./assets/images/satellite.png" class="card-img-top" alt="Satellite">
								<div class="card-body">
									<p class="card-title">Satellite</p>
								</div>
							</div>
						</div>
						<div class="col-md-3 mb-3">
							<div class="card" data-layer="terrain">
								<img src="./assets/images/terrain.png" class="card-img-top" alt="Terrain">
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
	<div class="modal fade" id="layerSelectModal" tabindex="-1" aria-labelledby="layerSelectModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-xs modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<p class="modal-title" id="layerSelectModalLabel">Layers</p>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="accordion" id="layerAccordion">
						<!-- Accordion Item 1 -->
						<div class="accordion-item">
							<h2 class="accordion-header" id="headingOne">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
									<i class="fas fa-map-marker-alt me-2"></i> Forest Layers
								</button>
							</h2>
							<div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
								data-bs-parent="#layerAccordion">
								<div class="accordion-body">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="poi1">
										<label class="form-check-label" for="poi1">FB Boundary</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="poi2">
										<label class="form-check-label" for="poi2">Division Boundary</label>
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

						<!-- Accordion Item 2 -->
						<div class="accordion-item">
							<h2 class="accordion-header" id="headingTwo">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									<i class="fas fa-road me-1"></i> Drone Image Analysis
								</button>
							</h2>
							<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
								data-bs-parent="#layerAccordion">
								<div class="accordion-body">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="transport1">
										<label class="form-check-label" for="transport1">Image-1</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="transport2">
										<label class="form-check-label" for="transport2">Image-2</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="transport3">
										<label class="form-check-label" for="transport3">Image-3</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="transport4">
										<label class="form-check-label" for="nature1">Plantation</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="transport5">
										<label class="form-check-label" for="nature2">Pits</label>
									</div>
								</div>
							</div>
						</div>

						<!-- Accordion Item 3 -->
						<div class="accordion-item">
							<h2 class="accordion-header" id="headingThree">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
									<i class="fas fa-tree me-2"></i> Plantation site
								</button>
							</h2>
							<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
								data-bs-parent="#layerAccordion">
								<div class="accordion-body">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="nature1">
										<label class="form-check-label" for="nature1">Plantation</label>
									</div>
									<!-- <div class="form-check">
										<input class="form-check-input" type="checkbox" id="nature3">
										<label class="form-check-label" for="nature3">Forests</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="nature4">
										<label class="form-check-label" for="nature4">Mountains</label>
									</div> -->
								</div>
							</div>
						</div>

						<!-- Accordion Item 4 -->
						<div class="accordion-item">
							<h2 class="accordion-header" id="headingFour">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
									<i class="fas fa-building me-2"></i> Swipe layers
								</button>
							</h2>
							<div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
								data-bs-parent="#layerAccordion">
								<div class="accordion-body">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="urban1">
										<label class="form-check-label" for="urban1">layer-1</label>
									</div>
							
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Filter Layer  Modal -->

	<div class="modal fade" id="filterLayerModal" tabindex="-1" aria-labelledby="filterLayerModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-xs modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<p class="modal-title" id="filterLayerModalLabel">Filter Layers</p>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="filterForm">
						<div class="mb-3">
							<label for="filterSelect1" class="form-label">Filter by Category</label>
							<select class="form-select" id="filterSelect1">
								<option selected>Choose...</option>
								<option value="forest">Forest Layers</option>
								<option value="drone">Drone Image Analysis</option>
								<option value="plantation">Plantation site</option>
								<option value="waterbody">Waterbody</option>
							</select>
						</div>
						<div class="mb-3">
							<label for="filterSelect2" class="form-label">Filter by Type</label>
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
							<label for="filterSelect3" class="form-label">Filter by Date</label>
							<select class="form-select" id="filterSelect3">
								<option selected>Choose...</option>
								<option value="2023">2023</option>
								<option value="2022">2022</option>
								<option value="2021">2021</option>
								<option value="2020">2020</option>
							</select>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="applyFilter()">Apply Filter</button>
				</div>
			</div>
		</div>
	</div>



	<!--  feature modal info  -->
	<div class="modal fade" id="featureInfoModal" tabindex="-1" aria-labelledby="featureInfoModalLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="featureInfoModalLabel">Feature Information</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p id="feature-info"></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
    
   
   
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/ol@v7.3.0/dist/ol.js"></script>
	<script src="https://unpkg.com/ol-layerswitcher@4.1.1"></script>
	<!-- <script src="assets/mapview-script.js"></script> -->
     <script src="../../campa_FE/assets/mapview-script.js"></script>


</body>

</html>



<?php
} else {
    echo 'Invalid request.';
}
?>




