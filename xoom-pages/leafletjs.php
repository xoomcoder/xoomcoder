
<!DOCTYPE html>
<html>
<head>
	
	<title>Xoom Map</title>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" />

    <link rel="stylesheet" href="assets/leaflet/leaflet.css">
    <script src="assets/leaflet/leaflet.js"></script>


	<style>
html, body {
    width:100%;
    height:100%;
    padding:0;
    margin:0;
}        
    </style>
</head>
<body>



<div id="mymap" style="width: 100%; height: 100%;"></div>
<script>

	var map = L.map('mymap').fitWorld();

	L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
		maxZoom: 18,
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
			'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		id: 'mapbox/streets-v11',
		tileSize: 512,
		zoomOffset: -1
	}).addTo(map);

	function onLocationFound(e) {
		var radius = e.accuracy / 2;

		L.marker(e.latlng,).addTo(map)
			.bindPopup("You are within " + radius + " meters from this point. Move the marker to change your location.").openPopup();
        L.marker(e.latlng,{draggable: true}).addTo(map)
            .bindPopup("choose your location");

		L.circle(e.latlng, radius).addTo(map);
	}

	function onLocationError(e) {
		alert(e.message);
	}

	map.on('locationfound', onLocationFound);
	map.on('locationerror', onLocationError);

    map.locate({setView: true, maxZoom: 16});

    window.addEventListener('resize', function(event) {
        mymap.style.width = Math.round(1.0 * document.body.clientWidth) + 'px';
        mymap.style.height = Math.round(1.0 * document.body.clientHeight) + 'px';
        map.invalidateSize();
    });

    </script>


</body>
</html>
