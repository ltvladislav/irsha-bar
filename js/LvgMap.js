


class LvgMap {
	
	constructor(config) {
		
		let mapElement = document.getElementById(config.containerId);
		let mapConfig = {
			zoom: config.zoom || 16,
			center: config.center
		};
		
		this.map = new google.maps.Map(mapElement, mapConfig);
		
		this.centerMarker = new google.maps.Marker({
			position: config.center, 
			map: this.map, 
			title: config.centerTitle || ""
		});
		this.makers = [];
		if (config.markers) {
			config.markers.forEach(function(marker) {
				this.makers.push(new google.maps.Marker({
					position: {
						lat: marker.lat, 
						lng: marker.lng
					}, 
					map: this.map, 
					title: marker.title
				}))
			}, this);
		}
	}
	
	makeRoute() {
		if (!this.directionsService) {
			this.directionsService = new google.maps.DirectionsService();
		}
		if (!this.directionsRenderer) {
			this.directionsRenderer = new google.maps.DirectionsRenderer();
			this.directionsRenderer.setMap(this.map);
		}
		let infoWindow = new google.maps.InfoWindow;

		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {

				let destination = {
					lat: position.coords.latitude,
					lng: position.coords.longitude
				};
				let centerDestination = {
					lat: this.centerMarker.position.lat(),
					lng: this.centerMarker.position.lat()
				};
				let renderer = this.directionsRenderer;

				this.directionsService.route({
						origin: centerDestination,
						destination: destination,
						travelMode: "DRIVING"
					},
					function(response, status) {
						if (status === 'OK') {
							renderer.setDirections(response);
						} else {
							window.alert('Directions request failed due to ' + status);
						}
					});
			}, function() {});
		} else {
			window.console.log('Browser doesn\'t support Geolocation')
			window.alert('Browser doesn\'t support Geolocation')
		}
	}
}