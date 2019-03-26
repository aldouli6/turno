var map;
var map1;

function initMap() {//this initializes the map, to be placed on a class
    //Sinester Details' map
    var mapCanvas = document.getElementById('map');
    var mapOptions = {

        disableDefaultUI: true,
        zoomControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDoubleClickZoom: true,
        clickableIcons: false
    };
    map = new google.maps.Map(mapCanvas, mapOptions);

    //Set Fixer's map
    var mapCanvas1 = document.getElementById('map1');
    var mapOptions1 = {
    
        disableDefaultUI: true,
        zoomControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDoubleClickZoom: true,
        draggable: false,
        scrollwheel: false



    };
    map1 = new google.maps.Map(mapCanvas1, mapOptions1);

/*
    map1.addListener('rightclick', function(a) { //just for testing: it saves into firebase the lat&lng clicked on the map for fixers
        ajustRef.push({
            idajus: 4,
            locationx: a.latLng.lng(),
            locationy: a.latLng.lat(),
            status: "Disponible"
        });
    });*/

}


function createMarker(e) {

  var canvas, context;

  canvas = document.createElement("canvas");
  canvas.width=44;
  canvas.height=44;
  var centerX = (canvas.width / 2);
  var centerY = (canvas.height / 2);

  context = canvas.getContext("2d");
  context.fillStyle = "rgba(91,192,222,1)";
  context.beginPath();
  context.arc(centerX,centerY,20,0,2*Math.PI);
  context.closePath();
  context.fill();
  context.fillStyle="white";
  context.font="18px Monserrat";

  // Move it down by half the text height and left by half the text width
  var width = context.measureText(e).width;
  var height = context.measureText("w").width; // this is a GUESS of height
  context.fillText(e, 22 - (width/2) ,22 + (height/2));




  return canvas.toDataURL();

}

google.maps.event.addDomListener(window, 'load', initMap);
