// Define base layers
const osmLayer = new ol.layer.Tile({
  source: new ol.source.OSM(),
  title: "OpenStreetMap", 
  type: "base",
  visible: true,
});

const satelliteLayer = new ol.layer.Tile({
  source: new ol.source.TileImage({
    url: "https://mt1.google.com/vt/lyrs=s&hl=pl&&x={x}&y={y}&z={z}",
    crossOrigin: "anonymous",
  }),
  title: "Satellite",
  type: "base",
  visible: false,
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
  units: 'metric', // You can change this to 'imperial' if preferred
  bar: true,
  steps: 4,
  text: true,
  minWidth: 140
});

const fullscreenControl = new ol.control.FullScreen({
	source: 'map',
	label: '', // Empty string to use our custom button
	labelActive: ''
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
// map.addControl(fullscreenControl);

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

const fullscreenBtn = document.getElementById('fullscreen-btn');

fullscreenBtn.addEventListener('click', function() {
  // Toggle fullscreen using the browser's Fullscreen API
  if (!document.fullscreenElement) {
    document.getElementById('map').requestFullscreen().catch(err => {
      console.error(`Error attempting to enable fullscreen: ${err.message}`);
    });
  } else {
    document.exitFullscreen();
  }
});

// Listen for fullscreenchange event on the document
document.addEventListener('fullscreenchange', function() {
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
      url: 'https://geoserver.amnslis.in/geoserver/Biju/wms',
      params: {
          'LAYERS': 'Biju:state_boundary',
          'FORMAT': 'image/png', // or other format supported by your GeoServer
          'TRANSPARENT': true
      },
      serverType: 'geoserver'
  })
});

// Add the WMS layer to the map
map.addLayer(wmsLayerStateBoundary);

// Configure the map view
map.setView(new ol.View({
  center: ol.proj.fromLonLat([84.44, 20.29]), // Adjust the center as needed
  zoom: 7, // Adjust the initial zoom level as needed
  minZoom: 5, // Set minimum zoom level
  maxZoom: 15 // Set maximum zoom level
}));


// Define the WMS layer
var aiMlDataLayer2;
try {
  aiMlDataLayer2 = new ol.layer.Image({
      source: new ol.source.ImageWMS({
          url: 'https://geoserver.amnslis.in/geoserver/Biju/wms',
          params: {
              'LAYERS': 'Biju:village_boundary',
              'TILED': true,
              'VERSION': '1.1.0',
              'FORMAT': 'image/png'
          },
          serverType: 'geoserver',
          crossOrigin: 'anonymous'
      }),
      visible: false // Set layer initial visibility to false
  });
  aiMlDataLayer2.setZIndex(99);
  map.addLayer(aiMlDataLayer2);
} catch (error) {
  console.log('aiMlaiMlDataLayer2DataLayer: ' + error);
}

// Add event listener to the checkbox
document.getElementById('transport1').addEventListener('change', function (event) {
  if (event.target.checked) {
    aiMlDataLayer2.setVisible(true);
  } else {
    aiMlDataLayer2.setVisible(false);
  }
});


  // Define the WMS layer
  var aiMlDataLayer2;
  try {
    aiMlDataLayer2 = new ol.layer.Image({
          source: new ol.source.ImageWMS({
              url: 'https://geoserver.amnslis.in/geoserver/Biju/wms',
              params: {
                  'LAYERS': 'Biju:village_boundary',
                  'TILED': true,
                  'VERSION': '1.1.0',
                  'FORMAT': 'image/png'
              },
              serverType: 'geoserver',
              crossOrigin: 'anonymous'
          }),
          visible: false // Set layer initial visibility to false
      });
      aiMlDataLayer2.setZIndex(99);
      map.addLayer(aiMlDataLayer2);
  } catch (error) {
      console.log('aiMlDataLayer: ' + error);
  }  
  // Add event listener to the checkbox
  document.getElementById('urban1').addEventListener('change', function (event) {
    //alert(36)
      if (event.target.checked) {
        aiMlDataLayer2.setVisible(true);
      } else {
        aiMlDataLayer2.setVisible(false);
      }
  });


  // Define the WMS layer
  var aiMlDataLayer;
  try {
      aiMlDataLayer = new ol.layer.Image({
          source: new ol.source.ImageWMS({
              url: 'http://192.168.1.34:8080/geoserver/campa/wms',
              params: {
                  'LAYERS': 'campa:plantation',
                  'TILED': true,
                  'VERSION': '1.1.0',
                  'FORMAT': 'image/png'
              },
              serverType: 'geoserver',
              crossOrigin: 'anonymous'
          }),
          visible: false // Set layer initial visibility to false
      });
      aiMlDataLayer.setZIndex(99);
      map.addLayer(aiMlDataLayer);
  } catch (error) {
      console.log('aiMlDataLayer: ' + error);
  }  
  // Add event listener to the checkbox
  document.getElementById('transport4').addEventListener('change', function (event) {
    //alert(35)
      if (event.target.checked) {
          aiMlDataLayer.setVisible(true);
      } else {
          aiMlDataLayer.setVisible(false);
      }
  });


 
  // Define the WMS layer
  var aiMlDataLayer1;
  try {
    aiMlDataLayer1 = new ol.layer.Image({
          source: new ol.source.ImageWMS({
              url: 'http://192.168.1.34:8080/geoserver/campa/wms',
              params: {
                  'LAYERS': 'campa:pits',
                  'TILED': true,
                  'VERSION': '1.1.0',
                  'FORMAT': 'image/png'
              },
              serverType: 'geoserver',
              crossOrigin: 'anonymous'
          }),
          visible: false // Set layer initial visibility to false
      });
      aiMlDataLayer1.setZIndex(99);
      map.addLayer(aiMlDataLayer1);
  } catch (error) {
      console.log('aiMlDataLayer1: ' + error);
  }  
  // Add event listener to the checkbox
  document.getElementById('transport5').addEventListener('change', function (event) {
    //alert(36)
      if (event.target.checked) {
        aiMlDataLayer1.setVisible(true);
      } else {
        aiMlDataLayer1.setVisible(false);
      }
  });


    // Define the WMS layer
    var aiMlDataLayer3;
    try {
      aiMlDataLayer3 = new ol.layer.Image({
            source: new ol.source.ImageWMS({
                url: 'http://192.168.1.34:8080/geoserver/campa/wms',
                params: {
                    'LAYERS': 'campa:plantation_data',
                    'TILED': true,
                    'VERSION': '1.1.0',
                    'FORMAT': 'image/png'
                },
                serverType: 'geoserver',
                crossOrigin: 'anonymous'
            }),
            visible: false // Set layer initial visibility to false
        });
        aiMlDataLayer3.setZIndex(99);
        map.addLayer(aiMlDataLayer3);
    } catch (error) {
        console.log('aiMlDataLayer1: ' + error);
    }  
    // Add event listener to the checkbox
    document.getElementById('nature1').addEventListener('change', function (event) {
      //alert(36)
        if (event.target.checked) {
          aiMlDataLayer3.setVisible(true);
        } else {
          aiMlDataLayer3.setVisible(false);
        }
    });


        // Define the WMS layer
        var aiMlDataLayer5;
        try {
          aiMlDataLayer5 = new ol.layer.Image({
                source: new ol.source.ImageWMS({
                    url: 'http://192.168.1.34:8080/geoserver/campa/wms',
                    params: {
                        'LAYERS': 'campa:3-orthomosaic',
                        'TILED': true,
                        'VERSION': '1.1.0',
                        'FORMAT': 'image/png',
                        'SRS':'EPSG:4326'
                    },
                    serverType: 'geoserver',
                    crossOrigin: 'anonymous'
                }),
                visible: false // Set layer initial visibility to false
            });
            aiMlDataLayer5.setZIndex(99);
            map.addLayer(aiMlDataLayer5);
        } catch (error) {
            console.log('aiMlDataLayer1: ' + error);
        }  
        // Add event listener to the checkbox
        document.getElementById('transport2').addEventListener('change', function (event) {
          //alert(36)
            if (event.target.checked) {
              aiMlDataLayer5.setVisible(true);
            } else {
              aiMlDataLayer5.setVisible(false);
            }
        });

