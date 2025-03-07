/**
 * @file
 * HOG[SCAN] GPX Map behaviors.
 */
(function ($, Drupal, drupalSettings) {

  var initialized;

  function init() {
    if (!initialized) {
      initialized = true;

      var map_settings = drupalSettings.hs_gpx_map.mapSettings;
      var google_api_key = drupalSettings.hs_gpx_map.api;
      var coordinates = drupalSettings.hs_gpx_map.gpx;
      var map_id = drupalSettings.hs_gpx_map.id;

      $('#directions-panel-'+map_id).toggle();

      $.getScript('https://maps.googleapis.com/maps/api/js?key=' + google_api_key, function () {

        var controltype = map_settings.controltype;
        if (controltype == 'default') {
          controltype = google.maps.ZoomControlStyle.DEFAULT;
        } else if (controltype == 'small') {
          controltype = google.maps.ZoomControlStyle.SMALL;
        } else if (controltype == 'large') {
          controltype = google.maps.ZoomControlStyle.LARGE;
        } else {
          controltype = false
        }

        var maptype = map_settings.maptype;
        if (maptype) {
          if (maptype == 'map' && map_settings.baselayers_map) {
            maptype = google.maps.MapTypeId.ROADMAP;
          }
          if (maptype == 'satellite' && map_settings.baselayers_satellite) {
            maptype = google.maps.MapTypeId.SATELLITE;
          }
        } else {
          maptype = google.maps.MapTypeId.ROADMAP;
        }

        var mtc = map_settings.mtc;
        if (mtc == 'standard') {
          mtc = google.maps.MapTypeControlStyle.HORIZONTAL_BAR;
        } else if (mtc == 'menu') {
          mtc = google.maps.MapTypeControlStyle.DROPDOWN_MENU;
        } else {
          mtc = false;
        }

        var myOptions = {
          zoom: parseInt(map_settings.zoom),
          minZoom: parseInt(map_settings.min_zoom),
          maxZoom: parseInt(map_settings.max_zoom),
          mapTypeId: maptype,
          mapTypeControl: (mtc ? true : false),
          mapTypeControlOptions: {style: mtc},
          zoomControl: ((controltype !== false) ? true : false),
          zoomControlOptions: {style: controltype},
          panControl: (map_settings.pancontrol ? true : false),
          scrollwheel: (map_settings.scrollwheel ? true : false),
          draggable: (map_settings.draggable ? true : false),
          overviewMapControl: (map_settings.overview ? true : false),
          overviewMapControlOptions: {opened: (map_settings.overview_opened ? true : false)},
          streetViewControl: (map_settings.streetview_show ? true : false),
          scaleControl: (map_settings.scale ? true : false),
          scaleControlOptions: {style: google.maps.ScaleControlStyle.DEFAULT}
        };


        var mapCanvas = document.getElementById("map-"+map_id);
        var map = new google.maps.Map(mapCanvas, myOptions);
        mapCanvas.style.width = map_settings.width;
        mapCanvas.style.height = map_settings.height;
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        directionsDisplay.setMap(map);

        var waypts = [];

        for (var i = 0; i < coordinates.length; i++) {
          waypts.push({
            location: new google.maps.LatLng(coordinates[i][0], coordinates[i][1]),
            stopover: true
          });
        }

        var origin = waypts.shift().location;
        var destination = waypts.pop().location;
        var alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split('');

        directionsService.route({
          origin: origin,
          destination: destination,
          waypoints: waypts,
          optimizeWaypoints: true,
          travelMode: 'DRIVING'
        }, function (response, status) {
          if (status === 'OK') {
            directionsDisplay.setDirections(response);
            var route = response.routes[0];
            var summaryPanel = document.getElementById('directions-panel-'+map_id);
            summaryPanel.innerHTML = '';
            // For each route, display summary information.
            for (var i = 0; i < route.legs.length; i++) {
              summaryPanel.innerHTML += '<b>Route Segment: ' + alphabet[i] + '-' + alphabet[i + 1] + '</b><br>';
              summaryPanel.innerHTML += route.legs[i].distance.text + '<br>';
              for (var y = 0; y < route.legs[i].steps.length; y++) {
                summaryPanel.innerHTML += route.legs[i].steps[y].instructions + '<br>';
              }
              summaryPanel.innerHTML += '<br>';
            }
          } else {
            $('.field-name-field-event-route').html("<div><p><br><br>The route could not be rendered.  This could be caused by road closures or GPX file errors.</p></div>");
            $('#map').hide();
          }
        });
      });
    }
  }

  Drupal.behaviors.hsGpxMap = {
    attach: function (context, settings) {
      var map_id = drupalSettings.hs_gpx_map.id;
      $('#route-description-'+map_id).click(function () {
        $('#directions-panel-'+map_id).toggle();
        return false;
      });
      if (drupalSettings.hs_gpx_map.gpx !== "") {
        init();
      }
    }
  };

}(jQuery, Drupal, drupalSettings));
