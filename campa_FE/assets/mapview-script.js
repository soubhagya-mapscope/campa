// Define base layers
function show_loader() {
  var element = document.getElementById("loader");
  element.classList.add("lds-ellipsis");
  var container = document.getElementsByClassName("loader-container");
  container[0].classList.remove("hideDiv");

  //event.preventDefault();
}
function hide_loader() {
  var element = document.getElementById("loader");
  element.classList.remove("lds-ellipsis");
  var container = document.getElementsByClassName("loader-container");
  container[0].classList.add("hideDiv");

  //event.preventDefault();
}

const osmLayer = new ol.layer.Tile({
  source: new ol.source.OSM(),
  title: "OpenStreetMap",
  type: "base",
  visible: false,
});

const satelliteLayer = new ol.layer.Tile({
  source: new ol.source.TileImage({
    url: "https://mt1.google.com/vt/lyrs=s&hl=pl&&x={x}&y={y}&z={z}",
    crossOrigin: "anonymous",
  }),
  title: "Satellite",
  type: "base",
  visible: true,
});

const terrainLayer = new ol.layer.Tile({
  source: new ol.source.BingMaps({
    imagerySet: "AerialWithLabels",
    key: "voi3DlahFqo0MOrFalC2~6BX9iFreRSXk_hCsSHtZ0A~AuXzxBFu7NJaGwZO6oX2bEbHUKwhiif5YTYYqOZvgRiSl3Rt2zrcB6Addylvwat9",
    //key:'Aj5jxhwjBJgTVTIRfrLBlZIxoyheEqfNqtvD8Nxv-dT0DIPNHK7S-LTDbca6wkjo' //thakur86@hotmail.com
  }),
  title: "Terrain",
  type: "base",
  visible: false,
});

const googleStreetLayer = new ol.layer.Tile({
  source: new ol.source.TileImage({
    url: "https://mt1.google.com/vt/lyrs=r&hl=pl&&x={x}&y={y}&z={z}",
    crossOrigin: "anonymous",
  }),
  title: "Google Street",
  type: "base",
  visible: false,
});

var scaleControl = new ol.control.ScaleLine({
  units: "metric", // You can change this to 'imperial' if preferred
  bar: true,
  steps: 4,
  text: true,
  minWidth: 140,
});

const fullscreenControl = new ol.control.FullScreen({
  source: "map",
  label: "", // Empty string to use our custom button
  labelActive: "",
});

// Initialize OpenLayers map
const map = new ol.Map({
  target: "map",
  layers: [],
  view: new ol.View({
    center: ol.proj.fromLonLat([0, 0]),
    zoom: 2,
  }),
  controls: [], // This disables all default controls, including zoom buttons
});

map.addControl(scaleControl);
//map.addControl(fullscreenControl);

// Add these layers to your map
map.addLayer(osmLayer);
map.addLayer(satelliteLayer);
map.addLayer(terrainLayer);
map.addLayer(googleStreetLayer);

// Zoom in function
function zoomIn() {
  var view = map.getView();
  var zoom = view.getZoom();
  view.animate({
    zoom: zoom + 1,
    duration: 250,
  });
}

// Zoom out function
function zoomOut() {
  var view = map.getView();
  var zoom = view.getZoom();
  view.animate({
    zoom: zoom - 1,
    duration: 250,
  });
}

// Add click event listeners to zoom buttons
document.getElementById("zoom-in").addEventListener("click", zoomIn);
document.getElementById("zoom-out").addEventListener("click", zoomOut);

// Layer switcher functionality

const modal = new bootstrap.Modal(document.getElementById("layerModal"));
const cards = document.querySelectorAll(".card");

function getBaseLayers() {
  return map
    .getLayers()
    .getArray()
    .filter((layer) => layer.get("type") === "base");
}

function switchLayer(layerType) {
  const baseLayers = getBaseLayers();
  baseLayers.forEach((layer) => {
    if (layer && typeof layer.setVisible === "function") {
      layer.setVisible(layer.get("title").toLowerCase() === layerType);
    }
  });
}

function updateLayerButtonText() {
  const activeLayer = getBaseLayers().find((layer) => layer.getVisible());
  const button = document.getElementById("layer-switcher-btn");
  if (activeLayer) {
    button.textContent = `Layer: ${activeLayer.get("title")}`;
  } else {
    button.textContent = "Layer: None";
  }
}

cards.forEach((card) => {
  card.addEventListener("click", function () {
    const layerType = this.dataset.layer;
    switchLayer(layerType);
    updateLayerButtonText();
    modal.hide();
  });
});

// Initial update
updateLayerButtonText();

// Update whenever the visible property of a layer changes
getBaseLayers().forEach((layer) => {
  if (layer && typeof layer.on === "function") {
    layer.on("change:visible", updateLayerButtonText);
  }
});

// Layer select functionality
document.addEventListener("DOMContentLoaded", function () {
  var layersModalBtn = document.getElementById("layers-modal-btn");
  var layerSelectModal = new bootstrap.Modal(
    document.getElementById("layerSelectModal"),
    {
      backdrop: false,
      keyboard: false,
    }
  );

  layersModalBtn.addEventListener("click", function (event) {
    event.preventDefault();
    event.stopPropagation();
    layerSelectModal.toggle();
  });

  document.addEventListener("click", function (event) {
    if (
      !event.target.closest("#layerSelectModal") &&
      !event.target.closest("#layers-modal-btn")
    ) {
      layerSelectModal.hide();
    }
  });
});

const fullscreenBtn = document.getElementById("fullscreen-btn");

fullscreenBtn.addEventListener("click", function () {
  // Toggle fullscreen using the browser's Fullscreen API
  if (!document.fullscreenElement) {
    document
      .getElementById("map")
      .requestFullscreen()
      .catch((err) => {
        console.error(`Error attempting to enable fullscreen: ${err.message}`);
      });
  } else {
    document.exitFullscreen();
  }
});

// Listen for fullscreenchange event on the document
document.addEventListener("fullscreenchange", function () {
  if (document.fullscreenElement) {
    fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';
  } else {
    fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i>';
  }
});

//------------------------------Map work starts----------------------------------//

// Define the WMS layer with zoom levels
var wmsLayerStateBoundary = new ol.layer.Image({
  source: new ol.source.ImageWMS({
    url: "https://geoserver.amnslis.in/geoserver/Biju/wms",
    params: {
      LAYERS: "Biju:state_boundary",
      FORMAT: "image/png", // or other format supported by your GeoServer
      TRANSPARENT: true,
    },
    serverType: "geoserver",
  }),
});

// Add the WMS layer to the map
map.addLayer(wmsLayerStateBoundary);

// Configure the map view
map.setView(
  new ol.View({
    center: ol.proj.fromLonLat([84.44, 20.29]), // Adjust the center as needed
    zoom: 7, // Adjust the initial zoom level as needed
    minZoom: 5, // Set minimum zoom level
    maxZoom: 25, // Set maximum zoom level
  })
);

//-------------------------Plantation boundary WMS--------------------------------//
var pltBndDataLayer;
try {
  pltBndDataLayer = new ol.layer.Image({
    source: new ol.source.ImageWMS({
      url: "https://geoserver.amnslis.in/geoserver/campa/wms",
      params: {
        LAYERS: "campa:corected_drone_polygon",
        TILED: true,
        VERSION: "1.1.0",
        FORMAT: "image/png",
      },
      serverType: "geoserver",
      crossOrigin: "anonymous",
    }),
    visible: false, // Set layer initial visibility to false
  });
  pltBndDataLayer.setZIndex(99);
  map.addLayer(pltBndDataLayer);
} catch (error) {
  console.log("pltBndDataLayer: " + error);
}
document.getElementById("transport6").addEventListener("change", function (event) {
  //alert(9)
  if (event.target.checked) {
    pltBndDataLayer.setVisible(true);
    // Zoom to the extent of both layers combined
    //   var extent = ol.extent.createEmpty();
    //  ol.extent.extend(extent, pltBndDataLayer.getSource().getParams().LAYERS === 'campa:corected_drone_polygon' ? [85.8437631726265,20.907748460769653,85.8473111987114,20.91187047958374] : ol.extent.createEmpty());
    //  map.getView().fit(ol.proj.transformExtent(extent, 'EPSG:4326', 'EPSG:3857'), { duration: 1000 });
  } else {
    pltBndDataLayer.setVisible(false);
  }
});


//---------------------------plantation Analysis(Drone) --------------------
var pltDataLayer1;
try {
  pltDataLayer1 = new ol.layer.Image({
    source: new ol.source.ImageWMS({
      url: "https://geoserver.amnslis.in/geoserver/campa/wms",
      params: {
        LAYERS: "campa:plantation",
        TILED: true,
        VERSION: "1.1.0",
        FORMAT: "image/png",
      },
      serverType: "geoserver",
      crossOrigin: "anonymous",
    }),
    visible: false, // Set layer initial visibility to false
  });
  pltDataLayer1.setZIndex(99);
  map.addLayer(pltDataLayer1);
} catch (error) {
  console.log("pltDataLayer1: " + error);
}
document.getElementById("transport4").addEventListener("change", function (event) {
  const transparencySliderPlant = document.getElementById("transparencySliderPlant");
  const transparencyLabelPlant = document.querySelector("label[for='transparencySliderPlant']");
  if (event.target.checked) {
    pltDataLayer1.setVisible(true);
    transparencySliderPlant.style.display = "block";
    transparencyLabelPlant.style.display = "block";
    // Zoom to the extent of both layers combined
    // var extent = ol.extent.createEmpty();
    // ol.extent.extend(extent, pltDataLayer1.getSource().getParams().LAYERS === 'campa:plantation' ? [85.84375780820847,20.907737731933594,85.84732729196548,20.91185975074768] : ol.extent.createEmpty());
    // map.getView().fit(ol.proj.transformExtent(extent, 'EPSG:4326', 'EPSG:3857'), { duration: 1000 });
  } else {
    pltDataLayer1.setVisible(false);
    transparencySliderPlant.style.display = "none";
    transparencyLabelPlant.style.display = "none";
  }
});

//----------------------Map for pits analysis(Drone) --------------------
var aiMlDataLayer1;
try {
  aiMlDataLayer1 = new ol.layer.Image({
    source: new ol.source.ImageWMS({
      url: "https://geoserver.amnslis.in/geoserver/campa/wms",
      params: {
        LAYERS: "campa:pits",
        TILED: true,
        VERSION: "1.1.0",
        FORMAT: "image/png",
      },
      serverType: "geoserver",
      crossOrigin: "anonymous",
    }),
    visible: false, // Set layer initial visibility to false
  });
  aiMlDataLayer1.setZIndex(99);
  map.addLayer(aiMlDataLayer1);
} catch (error) {
  console.log("aiMlDataLayer1: " + error);
}
// Add event listener to the checkbox
document
  .getElementById("transport5")
  .addEventListener("change", function (event) {
    //alert(36)
    const transparencySliderPit = document.getElementById("transparencySliderPit");
    const transparencyLabelPit = document.querySelector("label[for='transparencySliderPit']");
    if (event.target.checked) {
      aiMlDataLayer1.setVisible(true);
      transparencySliderPit.style.display = "block";
      transparencyLabelPit.style.display = "block";
      // var extent = ol.extent.createEmpty();
      // ol.extent.extend(extent, aiMlDataLayer1.getSource().getParams().LAYERS === 'campa:pits' ? [85.90795540809631,20.79056704044342,85.91619944572449,20.795581698417664] : ol.extent.createEmpty());
      // map.getView().fit(ol.proj.transformExtent(extent, 'EPSG:4326', 'EPSG:3857'), { duration: 1000 });
    } else {
      aiMlDataLayer1.setVisible(false);
      transparencySliderPit.style.display = "none";
      transparencyLabelPit.style.display = "none";
    }
  });

//------------ ALL drone ortho images WMS-------------------------------
// Layer 1
var geotiffSite1Layer;
try {
  geotiffSite1Layer = new ol.layer.Image({
    source: new ol.source.ImageWMS({
      url: "https://geoserver.amnslis.in/geoserver/campa/wms",
      params: {
        LAYERS: "campa:geotiffSite1",
        TILED: true,
        VERSION: "1.1.0",
        FORMAT: "image/png",
        SRS: "EPSG:4326",
      },
      serverType: "geoserver",
      crossOrigin: "anonymous",
    }),
    visible: false,
  });
  // geotiffSite1Layer.setZIndex(99);
  map.addLayer(geotiffSite1Layer);
} catch (error) {
  console.log("geotiffSite1Layer: " + error);
}

// Layer 2
var orthomosaicLayer;
try {
  orthomosaicLayer = new ol.layer.Image({
    source: new ol.source.ImageWMS({
      url: "https://geoserver.amnslis.in/geoserver/campa/wms",
      params: {
        LAYERS: "campa:3-orthomosaic",
        TILED: true,
        VERSION: "1.1.0",
        FORMAT: "image/png",
        SRS: "EPSG:4326",
      },
      serverType: "geoserver",
      crossOrigin: "anonymous",
    }),
    visible: false,
  });
  //orthomosaicLayer.setZIndex(99);
  map.addLayer(orthomosaicLayer);
} catch (error) {
  console.log("orthomosaicLayer: " + error);
}

// Layer 3
var orthomosaicLayer1;
try {
  orthomosaicLayer1 = new ol.layer.Image({
    source: new ol.source.ImageWMS({
      url: "https://geoserver.amnslis.in/geoserver/campa/wms",
      params: {
        LAYERS: "campa:1-orthomosaic_cog",
        VERSION: "1.1.0",
        FORMAT: "image/png",
        SRS: "EPSG:4326",
      },
      serverType: "geoserver",
      crossOrigin: "anonymous",
    }),
    visible: false,  // Set to true to make the layer visible
  });
  //orthomosaicLayer1.setZIndex(99);
  map.addLayer(orthomosaicLayer1);
} catch (error) {
  console.log("orthomosaicLayer1 error: " + error);
}

// Add event listener to the checkbox
document.getElementById("transport2").addEventListener("change", function (event) {
  const transparencySlider = document.getElementById("transparencySlider");
  const transparencyLabel = document.querySelector("label[for='transparencySlider']");
  if (event.target.checked) {
    transparencySlider.style.display = "block";
    transparencyLabel.style.display = "block";
    geotiffSite1Layer.setVisible(true);
    orthomosaicLayer.setVisible(true);
    orthomosaicLayer1.setVisible(true);
    // Zoom to the extent of all three layers combined
    // var extent = ol.extent.createEmpty();
    // ol.extent.extend(extent, [85.844267198, 20.90827591, 85.846982418, 20.911329492]); // geotiffSite1Layer extent
    // ol.extent.extend(extent, [85.832449894, 20.865525744, 85.83517625, 20.870097175]); // orthomosaicLayer extent
    // ol.extent.extend(extent, [85.909415484, 20.791341688, 85.915252679, 20.794856705]); // orthomosaicLayer1 extent
    // map.getView().fit(ol.proj.transformExtent(extent, 'EPSG:4326', 'EPSG:3857'), { duration: 1000 });
  } else {
    geotiffSite1Layer.setVisible(false);
    orthomosaicLayer.setVisible(false);
    orthomosaicLayer1.setVisible(false);
  }
});
// Add event listener to the transparency slider
document.getElementById("transparencySlider").addEventListener("input", function (event) {
  //alert(99)
  var value = event.target.value;
  var opacity = value / 100; // Convert to 0-1 range
  geotiffSite1Layer.setOpacity(opacity);
  orthomosaicLayer.setOpacity(opacity);
  orthomosaicLayer1.setOpacity(opacity);
});

document.getElementById("transparencySliderPit").addEventListener("input", function (event) {
  //alert(99)
  var value = event.target.value;
  var opacity = value / 100; // Convert to 0-1 range
  aiMlDataLayer1.setOpacity(opacity);
  //orthomosaicLayer.setOpacity(opacity);
  //orthomosaicLayer1.setOpacity(opacity);
});

document.getElementById("transparencySliderPlant").addEventListener("input", function (event) {
  //alert(99)
  var value = event.target.value;
  var opacity = value / 100; // Convert to 0-1 range
  pltDataLayer1.setOpacity(opacity);
});


//----------------------Map for Planatation site --------------------
var aiMlDataLayer3;
var vectorLayer;

try {
  aiMlDataLayer3 = new ol.layer.Image({
    source: new ol.source.ImageWMS({
      url: "https://geoserver.amnslis.in/geoserver/campa/wms",
      params: {
        LAYERS: "campa:plantation_data",
        TILED: true,
        VERSION: "1.1.0",
        FORMAT: "image/png",
      },
      serverType: "geoserver",
      crossOrigin: "anonymous",
    }),
    visible: false, // Set layer initial visibility to false
  });
  aiMlDataLayer3.setZIndex(99);
  map.addLayer(aiMlDataLayer3);
} catch (error) {
  console.log("aiMlDataLayer3: " + error);
}

// Function to set CQL filter
function setCqlFilter(filter) {
  var source = aiMlDataLayer3.getSource();
  var params = source.getParams();
  if (filter) {
    params.CQL_FILTER = filter;
  } else {
    delete params.CQL_FILTER; // Remove CQL_FILTER parameter to show all layers
  }
  source.updateParams(params);
}

// Add event listener to the checkbox
document.getElementById("nature1").addEventListener("change", function (event) {
  if (event.target.checked) {
    aiMlDataLayer3.setVisible(true);
    map.addLayer(aiMlDataLayer3); // Ensure the layer is added to the map
    zoomToGeoJSON(); // Add the vectorLayer
  } else {
    aiMlDataLayer3.setVisible(false);
    map.removeLayer(aiMlDataLayer3);
    if (vectorLayer) {
      map.removeLayer(vectorLayer);
    }
  }
});

document.addEventListener("DOMContentLoaded", function () {
  var cqlFilterValue = "name='" + plantationName + "'";
  if (plantationName != null) {
    aiMlDataLayer3.setVisible(true);
    setCqlFilter(cqlFilterValue); // Update with your CQL filter
    document.getElementById("nature1").checked = true;
    zoomToGeoJSON(); // Add the vectorLayer
  } else {
    aiMlDataLayer3.setVisible(false);
  }
});

function zoomToGeoJSON() {
  var vectorSource = new ol.source.Vector({
    features: new ol.format.GeoJSON().readFeatures(plantationGeojson, {
      featureProjection: 'EPSG:3857' // Match the map projection
    })
  });

  vectorLayer = new ol.layer.Vector({
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

  var extent = vectorSource.getExtent();
  map.getView().fit(extent, { padding: [100, 100, 100, 100], maxZoom: 19 });
}



function isClickOutsideDrawer(event) {
  var drawer = document.getElementById("featureInfoDrawer");
  return (
    !drawer.contains(event.target) &&
    !event.target.closest(".ol-overlay-container")
  );
}

//-----------------onclick-function work for plantation details show----------------------//
map.on("singleclick", function (evt) {
  if (aiMlDataLayer3.getVisible()) {
    var viewResolution = map.getView().getResolution();
    var projection = map.getView().getProjection();
    var source = aiMlDataLayer3.getSource();

    var url = source.getFeatureInfoUrl(
      evt.coordinate,
      viewResolution,
      projection,
      {
        INFO_FORMAT: "application/json",
        FEATURE_COUNT: 50,
      }
    );

    if (url) {
      fetch(url)
        .then(function (response) {
          return response.json();
        })
        .then(function (result) {
          if (result.features && result.features.length > 0) {
            // Open the drawer
            document.getElementById("featureInfoDrawer").classList.add("open");
            show_loader();
            // Populate the drawer with feature information
            var featureInfoContent =
              document.getElementById("featureInfoContent");
            featureInfoContent.innerHTML = ""; // Clear previous content

            result.features.forEach(function (feature) {
              var str = feature.id;
              var n = str.lastIndexOf('.');
              var resultID = str.substring(n + 1);


              fetch('detailsMap.php?id=' + resultID)
                .then(function (response) {
                  // When the page is loaded convert it to text
                  return response.text()
                })
                .then(function (html) {
                  featureInfoContent.innerHTML += html;
                })
                .catch(function (err) {
                  featureInfoContent.innerHTML = '<p>Error fetching feature info:.</p>';
                }).finally(function () {
                  hide_loader();
                });
            });
          } else {
            featureInfoContent.innerHTML = '<p>No feature information available at this location.</p>';
          }
        })
        .catch(function (error) {
          featureInfoContent.innerHTML = '<p>Error fetching feature info:.</p>';
          // console.error("Error fetching feature info:", error);
        });
    }
  }
});


// Function to close the drawer
function closeDrawer() {
  document.getElementById("featureInfoDrawer").classList.remove("open");
}

// Add event listener to close button
document.getElementById("closeDrawer").addEventListener("click", closeDrawer);

// Add event listener to close drawer when clicking outside
document.addEventListener("click", function (event) {
  if (isClickOutsideDrawer(event)) {
    closeDrawer();
  }
});

// Prevent drawer from closing when clicking inside it
document
  .getElementById("featureInfoDrawer")
  .addEventListener("click", function (event) {
    event.stopPropagation();
  });

//-------------------Forest Boundary-------------------
var fbDataLayer;
try {
  fbDataLayer = new ol.layer.Image({
    source: new ol.source.ImageWMS({
      url: "https://geoserver.amnslis.in/geoserver/campa/wms",
      params: {
        LAYERS: "campa:jv boundary",
        TILED: true,
        VERSION: "1.1.0",
        //FORMAT: "image/png",
        //SRS: "EPSG:4326",
      },
      serverType: "geoserver",
      crossOrigin: "anonymous",
    }),
    visible: false, // Set layer initial visibility to false
  });
  fbDataLayer.setZIndex(99);
  map.addLayer(fbDataLayer);
} catch (error) {
  console.log("divDataLayer: " + error);
}
// Add event listener to the checkbox
document.getElementById("fbBnd").addEventListener("change", function (event) {
  //alert(336)
  if (event.target.checked) {
    fbDataLayer.setVisible(true);
  } else {
    fbDataLayer.setVisible(false);
  }
});

//-------------------Division Boundary-------------------
var divDataLayer;
try {
  divDataLayer = new ol.layer.Image({
    source: new ol.source.ImageWMS({
      url: "https://geoserver.amnslis.in/geoserver/campa/wms",
      params: {
        LAYERS: "campa:dhenkanal_division_bnd",
        TILED: true,
        VERSION: "1.1.0",
        //FORMAT: "image/png",
        //SRS: "EPSG:4326",
      },
      serverType: "geoserver",
      crossOrigin: "anonymous",
    }),
    visible: false, // Set layer initial visibility to false
  });
  divDataLayer.setZIndex(99);
  map.addLayer(divDataLayer);

} catch (error) {
  console.log("divDataLayer: " + error);
}
// Add event listener to the checkbox
document.getElementById("divBnd").addEventListener("change", function (event) {
  //alert(336)
  if (event.target.checked) {
    divDataLayer.setVisible(true);
    var extent = ol.extent.createEmpty();
    ol.extent.extend(extent, divDataLayer.getSource().getParams().LAYERS === 'campa:dhenkanal_division_bnd' ? [85.04489135742188, 20.43292236328125, 86.10012817382812, 21.23822021484375] : ol.extent.createEmpty());
    map.getView().fit(ol.proj.transformExtent(extent, 'EPSG:4326', 'EPSG:3857'), { duration: 1000 });
  } else {
    divDataLayer.setVisible(false);
  }
});


//-------------------Range Boundary-------------------
var rngDataLayer;
try {
  rngDataLayer = new ol.layer.Image({
    source: new ol.source.ImageWMS({
      url: "https://geoserver.amnslis.in/geoserver/campa/wms",
      params: {
        LAYERS: "campa:range",
        TILED: true,
        VERSION: "1.1.0",
        //FORMAT: "image/png",
        //SRS: "EPSG:4326",
      },
      serverType: "geoserver",
      crossOrigin: "anonymous",
    }),
    visible: false, // Set layer initial visibility to false
  });
  rngDataLayer.setZIndex(99);
  map.addLayer(rngDataLayer);
} catch (error) {
  console.log("rngDataLayer: " + error);
}
// Add event listener to the checkbox
document.getElementById("rngBnd").addEventListener("change", function (event) {
  //alert(336)
  if (event.target.checked) {
    rngDataLayer.setVisible(true);
  } else {
    rngDataLayer.setVisible(false);
  }
});

//-------------------Section Boundary-------------------
var secDataLayer;
try {
  secDataLayer = new ol.layer.Image({
    source: new ol.source.ImageWMS({
      url: "https://geoserver.amnslis.in/geoserver/campa/wms",
      params: {
        LAYERS: "campa:section_boundary",
        TILED: true,
        VERSION: "1.1.0",
        //FORMAT: "image/png",
        //SRS: "EPSG:4326", 
      },
      serverType: "geoserver",
      crossOrigin: "anonymous",
    }),
    visible: false, // Set layer initial visibility to false
  });
  secDataLayer.setZIndex(99);
  map.addLayer(secDataLayer);
} catch (error) {
  console.log("secDataLayer: " + error);
}
// Add event listener to the checkbox
document.getElementById("secBnd").addEventListener("change", function (event) {
  //alert(336)
  if (event.target.checked) {
    secDataLayer.setVisible(true);
  } else {
    secDataLayer.setVisible(false);
  }
});

//-------------------Beat Boundary-------------------
var beatDataLayer;
try {
  beatDataLayer = new ol.layer.Image({
    source: new ol.source.ImageWMS({
      url: "https://geoserver.amnslis.in/geoserver/campa/wms",
      params: {
        LAYERS: "campa:beat_boundary",
        TILED: true,
        VERSION: "1.1.0",
        //FORMAT: "image/png",
        //SRS: "EPSG:4326",
      },
      serverType: "geoserver",
      crossOrigin: "anonymous",
    }),
    visible: false, // Set layer initial visibility to false
  });
  beatDataLayer.setZIndex(99);
  map.addLayer(beatDataLayer);
} catch (error) {
  console.log("beatDataLayer: " + error);
}
// Add event listener to the checkbox
document.getElementById("beatBnd").addEventListener("change", function (event) {
  //alert(336)
  if (event.target.checked) {
    beatDataLayer.setVisible(true);
  } else {
    beatDataLayer.setVisible(false);
  }
});



//-----------------------------------Swipe layer work--------------------------------//

// Define the base layer (always visible)
var orthomosaicLayer;
try {
  orthomosaicLayer = new ol.layer.Image({
    source: new ol.source.ImageWMS({
      url: "https://geoserver.amnslis.in/geoserver/campa/wms",
      params: {
        LAYERS: "campa:geotiffSite1",
        TILED: true,
        VERSION: "1.1.0",
        FORMAT: "image/png",
        SRS: "EPSG:4326",
      },
      serverType: "geoserver",
      crossOrigin: "anonymous",
    }),
    visible: false, // Base layer is always visible
  });
  orthomosaicLayer.setZIndex(98);
  map.addLayer(orthomosaicLayer);
} catch (error) {
  console.log("orthomosaicLayer: " + error);
}

// Swipe Layer (toggled visibility)
var pltDataLayer;
try {
  pltDataLayer = new ol.layer.Image({
    source: new ol.source.ImageWMS({
      url: "https://geoserver.amnslis.in/geoserver/campa/wms",
      params: {
        LAYERS: "campa:plantation",
        TILED: true,
        VERSION: "1.1.0",
        FORMAT: "image/png",
      },
      serverType: "geoserver",
      crossOrigin: "anonymous",
    }),
    visible: false, // Set layer initial visibility to false
  });
  pltDataLayer.setZIndex(99);
  map.addLayer(pltDataLayer);
} catch (error) {
  console.log("pltDataLayer: " + error);
}

// Add event listener to the checkbox for layer toggle and swipe functionality
document.getElementById("urban1").addEventListener("change", function (event) {
  const swipe = document.getElementById("swiplayerID");

  if (event.target.checked) {
    pltDataLayer1.setVisible(true);
    aiMlDataLayer1.setVisible(true);
    swipe.style.display = "block";

    const layer_prerender = function (event) {
      const ctx = event.context;
      const width = ctx.canvas.width * (swipe.value / 100);
      ctx.save();
      ctx.beginPath();
      ctx.rect(width, 0, ctx.canvas.width - width, ctx.canvas.height);
      ctx.clip();
    };

    const layer_postrender = function (event) {
      const ctx = event.context;
      ctx.restore();
    };

    pltDataLayer1.on("prerender", layer_prerender);
    pltDataLayer1.on("postrender", layer_postrender);

    aiMlDataLayer1.on("prerender", layer_prerender);
    aiMlDataLayer1.on("postrender", layer_postrender);

    swipe.addEventListener("input", function () {
      map.render();
    });

  } else {
    pltDataLayer1.setVisible(false);
    aiMlDataLayer1.setVisible(false);
    swipe.style.display = "none";
    pltDataLayer1.un("prerender", layer_prerender);
    pltDataLayer1.un("postrender", layer_postrender);
    aiMlDataLayer1.un("prerender", layer_prerender);
    aiMlDataLayer1.un("postrender", layer_postrender);
  }
});