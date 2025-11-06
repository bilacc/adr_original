var map = new Array();
var markersArray = new Array();

function initialize(num, lat, lon) {
	var centar = new google.maps.LatLng(lat, lon);
	var mapOptions = {
		zoom: 12,
		center: centar,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var input = document.getElementById('locationTextField');
	var autocomplete = new google.maps.places.Autocomplete(input);

	// if (place.geometry.viewport) {
	//     map.fitBounds(place.geometry.viewport);
	//   } else {
	//     map.setCenter(place.geometry.location);
	//     map.setZoom(17);  // Why 17? Because it looks good.
	//   }

	// autocomplete.bindTo('bounds', map);
	map[num] = new Array();

	map[num] = new google.maps.Map(document.getElementById("map" + num), mapOptions);

	google.maps.event.addListener(map[num], 'click', function (event) {
		addMarker(event.latLng, num);
	});
}



function initAutocomplete() {
	var map = new google.maps.Map(document.getElementById('map'), {
		center: { lat: -33.8688, lng: 151.2195 },
		zoom: 13,
		mapTypeId: 'roadmap'
	});

	// Create the search box and link it to the UI element.
	var input = document.getElementById('pac-input');
	var searchBox = new google.maps.places.SearchBox(input);
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

	// Bias the SearchBox results towards current map's viewport.
	map.addListener('bounds_changed', function () {
		searchBox.setBounds(map.getBounds());
	});

	var markers = [];
	// Listen for the event fired when the user selects a prediction and retrieve
	// more details for that place.
	searchBox.addListener('places_changed', function () {
		var places = searchBox.getPlaces();

		if (places.length == 0) {
			return;
		}

		// Clear out the old markers.
		markers.forEach(function (marker) {
			marker.setMap(null);
		});
		markers = [];

		// For each place, get the icon, name and location.
		var bounds = new google.maps.LatLngBounds();
		places.forEach(function (place) {
			if (!place.geometry) {
				console.log("Returned place contains no geometry");
				return;
			}
			var icon = {
				url: place.icon,
				size: new google.maps.Size(71, 71),
				origin: new google.maps.Point(0, 0),
				anchor: new google.maps.Point(17, 34),
				scaledSize: new google.maps.Size(25, 25)
			};

			// Create a marker for each place.
			markers.push(new google.maps.Marker({
				map: map,
				icon: icon,
				title: place.name,
				position: place.geometry.location
			}));

			if (place.geometry.viewport) {
				// Only geocodes have viewport.
				bounds.union(place.geometry.viewport);
			} else {
				bounds.extend(place.geometry.location);
			}
		});
		map.fitBounds(bounds);
	});
}

function addMarker(location, num, lat, lon) {
	clearOverlays(num);

	if (lat && lon)
		var pos = new google.maps.LatLng(lat, lon);
	else
		var pos = location;

	marker = new google.maps.Marker({
		position: pos,
		map: map[num]
	});

	document.getElementById('gmap_lon_' + num).value = pos.lng();
	document.getElementById('gmap_lat_' + num).value = pos.lat();

	markersArray[num] = new Array();
	markersArray[num].push(marker);
}

// Removes the overlays from the map, but keeps them in the array
function clearOverlays(num) {
	if (markersArray) {
		for (i in markersArray[num]) {
			markersArray[num][i].setMap(null);
		}
	}
}

function start_gmap() {
	for (var i = 1; i <= 1; i++) {
		if ($('#map' + i).length) {
			if ($('#gmap_lat_' + i).val() == '') {
				document.getElementById('gmap_lat_' + i).value = 45.81348650;
			}
			if ($('#gmap_lon_' + i).val() == '') {
				document.getElementById('gmap_lon_' + i).value = 15.93017578;
			}
			initialize(i, $('#gmap_lat_' + i).val(), $('#gmap_lon_' + i).val());
			addMarker(false, i, $('#gmap_lat_' + i).val(), $('#gmap_lon_' + i).val());
		}
	}
	for (var i = 2; i <= 2; i++) {
		if ($('#map' + i).length) {
			if ($('#gmap_lat_' + i).val() == '') {
				document.getElementById('gmap_lat_' + i).value = 45.81348650;
			}
			if ($('#gmap_lon_' + i).val() == '') {
				document.getElementById('gmap_lon_' + i).value = 15.93017578;
			}
			initialize(i, $('#gmap_lat_' + i).val(), $('#gmap_lon_' + i).val());
			addMarker(false, i, $('#gmap_lat_' + i).val(), $('#gmap_lon_' + i).val());
		}
	}
};