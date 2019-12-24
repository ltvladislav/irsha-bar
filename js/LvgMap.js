


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
	
	get centerMakerCoords() {
		return {
			lat: this.centerMarker.position.lat(),
			lng: this.centerMarker.position.lng()
		};
	}
	
	makeRoute() {
		if (!this.directionsService) {
			this.directionsService = new google.maps.DirectionsService();
		}
		if (!this.directionsRenderer) {
			this.directionsRenderer = new google.maps.DirectionsRenderer();
			this.directionsRenderer.setMap(this.map);
		}
		
		this.getCurrentPosition(function(position) {
			
			let renderer = this.directionsRenderer;
			
			let positionCoords = {
				lat: position.coords.latitude,
				lng: position.coords.longitude
			};
			
			this.directionsService.route({
				origin: positionCoords,
				destination: this.centerMakerCoords,
				travelMode: google.maps.DirectionsTravelMode.DRIVING
			},
			function(response, status) {
				if (status === google.maps.DirectionsStatus.OK) {
					renderer.setDirections(response);
				} else {
					window.alert('Directions request failed due to ' + status);
				}
			});
			
		}, this);
	}
	
	getCurrentPosition(callback, scope) {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				callback.call(scope, position);
			}, function() {});
		} else {
			window.console.log('Browser doesn\'t support Geolocation')
			window.alert('Browser doesn\'t support Geolocation')
		}
	}
}