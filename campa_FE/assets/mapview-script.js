// Define base layers
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

// Define the swipe layer (toggled visibility)
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
  if (event.target.checked) {
    pltDataLayer1.setVisible(true);
    // Zoom to the extent of both layers combined
    var extent = ol.extent.createEmpty();
    ol.extent.extend(extent, pltDataLayer1.getSource().getParams().LAYERS === 'campa:plantation' ? [85.84375780820847, 20.907737731933594, 85.84732729196548, 20.91185975074768] : ol.extent.createEmpty());
    map.getView().fit(ol.proj.transformExtent(extent, 'EPSG:4326', 'EPSG:3857'), { duration: 1000 });
  } else {
    pltDataLayer1.setVisible(false);
  }
});

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
    var extent = ol.extent.createEmpty();
    ol.extent.extend(extent, pltBndDataLayer.getSource().getParams().LAYERS === 'campa:corected_drone_polygon' ? [85.8437631726265, 20.907748460769653, 85.8473111987114, 20.91187047958374] : ol.extent.createEmpty());
    map.getView().fit(ol.proj.transformExtent(extent, 'EPSG:4326', 'EPSG:3857'), { duration: 1000 });
  } else {
    pltBndDataLayer.setVisible(false);
  }
});


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
    visible: true, // Base layer is always visible
  });
  orthomosaicLayer.setZIndex(98);
  map.addLayer(orthomosaicLayer);
  //orthomosaicLayer.setVisible(true);
} catch (error) {
  console.log("orthomosaicLayer: " + error);
}

// Define the swipe layer (toggled visibility)
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
  if (event.target.checked) {
    pltDataLayer.setVisible(true);
    //pltDataLayer1.setVisible(true);
    // Zoom to the extent of both layers combined
    var extent = ol.extent.createEmpty();
    ol.extent.extend(extent, pltDataLayer.getSource().getParams().LAYERS === 'campa:plantation' ? [85.84375780820847, 20.907737731933594, 85.84732729196548, 20.91185975074768] : ol.extent.createEmpty());
    map.getView().fit(ol.proj.transformExtent(extent, 'EPSG:4326', 'EPSG:3857'), { duration: 1000 });
    document.getElementById("swiplayerID").style.display = "block";

    var swipe = document.getElementById("swiplayerID");

    var layer1_prerender = function (event) {
      var ctx = event.context;
      var width = ctx.canvas.width * (swipe.value / 100);
      ctx.save();
      ctx.beginPath();
      ctx.rect(width, 0, ctx.canvas.width - width, ctx.canvas.height);
      ctx.clip();
    };

    var layer1_postrender = function (event) {
      const ctx = event.context;
      ctx.restore();
    };

    pltDataLayer.on("prerender", layer1_prerender);
    pltDataLayer.on("postrender", layer1_postrender);

    swipe.addEventListener("input", function () {
      map.render();
    });

  } else {
    pltDataLayer.setVisible(false);
    document.getElementById("swiplayerID").style.display = "none";
    document.getElementById("txtAd").innerHTML = "";
    pltDataLayer.un("prerender", layer1_prerender);
    pltDataLayer.un("postrender", layer1_postrender);
  }
});



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
  geotiffSite1Layer.setZIndex(99);
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
  orthomosaicLayer.setZIndex(99);
  map.addLayer(orthomosaicLayer);
} catch (error) {
  console.log("orthomosaicLayer: " + error);
}

// Add event listener to the checkbox
document.getElementById("transport2").addEventListener("change", function (event) {
  if (event.target.checked) {
    geotiffSite1Layer.setVisible(true);
    orthomosaicLayer.setVisible(true);
    // Zoom to the extent of both layers combined
    var extent = ol.extent.createEmpty();
    ol.extent.extend(extent, geotiffSite1Layer.getSource().getParams().LAYERS === 'campa:geotiffSite1' ? [85.844267198, 20.90827591, 85.846982418, 20.911329492] : ol.extent.createEmpty());
    ol.extent.extend(extent, orthomosaicLayer.getSource().getParams().LAYERS === 'campa:3-orthomosaic' ? [85.832449894, 20.865525744, 85.83517625, 20.870097175] : ol.extent.createEmpty());
    map.getView().fit(ol.proj.transformExtent(extent, 'EPSG:4326', 'EPSG:3857'), { duration: 1000 });
  } else {
    geotiffSite1Layer.setVisible(false);
    orthomosaicLayer.setVisible(false);
  }
});

// Define the WMS layer
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
    if (event.target.checked) {
      aiMlDataLayer1.setVisible(true);
      var extent = ol.extent.createEmpty();
      ol.extent.extend(extent, aiMlDataLayer1.getSource().getParams().LAYERS === 'campa:pits' ? [85.90795540809631, 20.79056704044342, 85.91619944572449, 20.795581698417664] : ol.extent.createEmpty());
      map.getView().fit(ol.proj.transformExtent(extent, 'EPSG:4326', 'EPSG:3857'), { duration: 1000 });
    } else {
      aiMlDataLayer1.setVisible(false);
    }
  });

// Define the WMS layer
// var aiMlDataLayer3;
// try {
//   aiMlDataLayer3 = new ol.layer.Image({
//     source: new ol.source.ImageWMS({
//       url: "http://192.168.1.34:8080/geoserver/campa/wms",
//       params: {
//         LAYERS: "campa:plantation_data",
//         TILED: true,
//         VERSION: "1.1.0",
//         FORMAT: "image/png",
//       },
//       serverType: "geoserver",
//       crossOrigin: "anonymous",
//     }),
//     visible: false, // Set layer initial visibility to false
//   });
//   aiMlDataLayer3.setZIndex(99);
//   map.addLayer(aiMlDataLayer3);
// } catch (error) {
//   console.log("aiMlDataLayer1: " + error);
// }
// // Add event listener to the checkbox
// document.getElementById("nature1").addEventListener("change", function (event) {
//   //alert(36)
//   if (event.target.checked) {
//     aiMlDataLayer3.setVisible(true);
//     var extent = ol.extent.createEmpty();
//       ol.extent.extend(extent, aiMlDataLayer1.getSource().getParams().LAYERS === 'campa:pits' ? [85.82842254638672,20.785411834716797,85.91878509521484,20.917316436767578] : ol.extent.createEmpty());
//       map.getView().fit(ol.proj.transformExtent(extent, 'EPSG:4326', 'EPSG:3857'), { duration: 1000 });
//   } else {
//     aiMlDataLayer3.setVisible(false);
//   }
// });

var aiMlDataLayer3;
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
    //var cqlFilterValue;
    var cqlFilterValue = "name='Bhuban NAC 10000 plantation'";
    if (cqlFilterValue != null) {
      //cqlFilterValue = "name=''"; // Replace with the dynamic value or set to null
      aiMlDataLayer3.setVisible(true);
      setCqlFilter(cqlFilterValue); // Update with your CQL filter
      var extent = ol.extent.createEmpty();
      ol.extent.extend(extent, aiMlDataLayer1.getSource().getParams().LAYERS === 'campa:pits' ? [85.82842254638672, 20.785411834716797, 85.91878509521484, 20.917316436767578] : ol.extent.createEmpty());
      map.getView().fit(ol.proj.transformExtent(extent, 'EPSG:4326', 'EPSG:3857'), { duration: 1000 });
    } else {
      aiMlDataLayer3.setVisible(true);
      var extent = ol.extent.createEmpty();
      ol.extent.extend(extent, aiMlDataLayer1.getSource().getParams().LAYERS === 'campa:pits' ? [85.82842254638672, 20.785411834716797, 85.91878509521484, 20.917316436767578] : ol.extent.createEmpty());
      map.getView().fit(ol.proj.transformExtent(extent, 'EPSG:4326', 'EPSG:3857'), { duration: 1000 });
    }
  } else {
    aiMlDataLayer3.setVisible(false);
    setCqlFilter(null); // Clear the CQL filter to show all layers
  }
});


// Define the WMS layer
var aiMlDataLayer5;
try {
  aiMlDataLayer5 = new ol.layer.Image({
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
    visible: false, // Set layer initial visibility to false
  });
  aiMlDataLayer5.setZIndex(99);
  map.addLayer(aiMlDataLayer5);
} catch (error) {
  console.log("aiMlDataLayer1: " + error);
}
// Add event listener to the checkbox
document
  .getElementById("transport2")
  .addEventListener("change", function (event) {
    //alert(36)
    if (event.target.checked) {
      aiMlDataLayer5.setVisible(true);
    } else {
      aiMlDataLayer5.setVisible(false);
    }
  });



function isClickOutsideDrawer(event) {
  var drawer = document.getElementById("featureInfoDrawer");
  return (
    !drawer.contains(event.target) &&
    !event.target.closest(".ol-overlay-container")
  );
}

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

            // Populate the drawer with feature information
            var featureInfoContent =
              document.getElementById("featureInfoContent");
            featureInfoContent.innerHTML = ""; // Clear previous content

            result.features.forEach(function (feature) {
              var props = feature.properties;
              featureInfoContent.innerHTML +=
                //"Plantation Details"+ "</br>" + 
                "<div class='table-responsive my-table-sm'><table class='table table-sm table-bordered mb-0'>"
                + "<tr><td><strong>Circle Name :</strong></td><td>" + (props.circle_name || 'N/A') + "</td></tr>"
                + "<tr><td><strong>Division Name :</strong></td><td>" + (props.division_name || 'N/A') + "</td></tr>"
                + "<tr><td><strong>Range Name :</strong></td><td>" + (props.range_name || 'N/A') + "</td></tr>"
                + "<tr><td><strong>Section Name :</strong></td><td>" + (props.section_name || 'N/A') + "</td></tr>"
                + "<tr><td><strong>Beat Name :</strong></td><td>" + (props.beat_name || 'N/A') + "</td></tr>"
                + "<tr><td><strong>Name :</strong></td><td>" + (props.name || 'N/A') + "</td></tr>"
                + "<tr><td><strong>Scheme :</strong></td><td>" + (props.scheme || 'N/A') + "</td></tr>"
                + "<tr><td><strong>Area Achievement :</strong></td><td>" + (props.area_achievement || 'N/A') + "</td></tr>"
                + "<tr><td><strong>Pit Target :</strong></td><td>" + (props.pit_target || 'N/A') + "</td></tr>"
                + "<tr><td><strong>Pit Achievement :</strong></td><td>" + (props.pit_achievement || 'N/A') + "</td></tr>"
                + "<tr><td><strong>Seedling Achievement :</strong></td><td>" + (props.seedling_achievement || 'N/A') + "</td></tr>"
                + "<tr><td><strong>Seedling Target :</strong></td><td>" + (props.seedling_target || 'N/A') + "</td></tr>"
                + "<tr><td><strong>Plantation Date :</strong></td><td>" + (props.plantation_date || 'N/A') + "</td></tr></table></div>";
            });
          } else {
            featureInfoContent.innerHTML = '<p>No feature information available at this location.</p>';
          }
        })
        .catch(function (error) {
          console.error("Error fetching feature info:", error);
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