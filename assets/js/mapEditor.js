var map;
var jsonData = "";
var coordenadas = "";
var centerpoint = "";
var datosMapa = "";

function initMap() {
     // Create a map object and specify the DOM element for display.
    map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 21.15064600684834, lng: -101.67477440088986},
          scrollwheel: false,
          zoom: 17,
          fullscreenControl: false, 
          zoomControl: true,
          streetViewControl: false,
          mapTypeControl: false
     });
     map.data.setControls(['Point','Polygon']);
     map.data.setStyle({
	     editable: true,
	     draggable: true
     });
     bindDataLayerListeners(map.data);
     initAutocompleteMap();
}

function initAutocompleteMap(){
     var input = document.getElementById('searchLocation');
     var button = document.getElementById('buttonBorrar');
     map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);
     map.controls[google.maps.ControlPosition.TOP_LEFT].push(button);
     var autocomplete = new google.maps.places.Autocomplete(input);
     autocomplete.bindTo('bounds', map);
     var infowindow = new google.maps.InfoWindow();
     var marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(0, -29)
     });
     $('#searchLocation').show();
     autocomplete.addListener('place_changed', function() {
          infowindow.close();
          marker.setVisible(false);
          var place = autocomplete.getPlace();
          if (!place.geometry) {
              window.alert("No details available for input: '" + place.name + "'");
              return;
          }
          if (place.geometry.viewport) {
              map.fitBounds(place.geometry.viewport);
          } else {
              map.setCenter(place.geometry.location);
              map.setZoom(17);  // Why 17? Because it looks good.
          }
          marker.setIcon(({
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(35, 35)
          }));
          marker.setPosition(place.geometry.location);
          marker.setVisible(true);

          var address = '';
          if (place.address_components) {
              address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
              ].join(' ');
          }

          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
          infowindow.open(map, marker);
     });
}

function bindDataLayerListeners(dataLayer) {
     dataLayer.addListener('addfeature', refreshGeoJsonFromData);
     dataLayer.addListener('removefeature', refreshGeoJsonFromData);
     dataLayer.addListener('setgeometry', refreshGeoJsonFromData);
}

function refreshGeoJsonFromData() {
    map.data.toGeoJson(function(geoJson) {
        jsonData = JSON.stringify(geoJson, null, 2);
        datosMapa = geoJson;
        //console.log(datosMapa);
        if(geoJson.features != "" && geoJson.features != null){
            for (var i = 0; i < geoJson.features.length; i++) {
                if (geoJson.features[i].geometry.type == "Point"){
                    centerpoint = JSON.stringify(geoJson.features[i].geometry.coordinates, null, 2);
                } else if(geoJson.features[i].geometry.type == "Polygon"){
                    coordenadas = JSON.stringify(geoJson.features[i].geometry.coordinates[0], null, 2);
                }
            }
        } else {
            coordenadas = "";
            centerpoint = "";
        }
        console.log(centerpoint);
        console.log(coordenadas);
        //geoJsonInput.value = JSON.stringify(geoJson, null, 2);
    });
}

$('#buttonBorrar').click(function(){
    map.data.forEach(function(feature) {
        // If you want, check here for some constraints.
        map.data.remove(feature);
    });
});
