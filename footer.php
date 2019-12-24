<footer id="contacts">
	<div class="container">
		<div class="footer-col1">
			<h1>IRSHA BAR</h1>
			<p>НЕ ЗНАЄШ ДЕ ВІДПОЧИТИ, ТА ЯК ГАРНО ПРОВЕСТИ ЧАС, ТОДІ ТОБІ ДО НАС</p>
		</div>
		<div class="footer-col2">
			<h4>Чекаємо вас</h4>
			<p>c. Ірша, Житомирська область, Україна</p>
			<p>+38 097 376 5417</p>
			<p>barmen@irshabar.kl.com.ua</p>
			<p>Ми відкриті з 20:00 що п'ятниці та що суботи</p>
			<p>Працюємо на замовлення (деталі за телефоном)</p>
		</div>
	</div>
	
	<div>
		<div id="map" class="google-map"></div>
		<a href="#" class="makeRoad link">Прокласти маршрут</a>
		
		<script src="js/LvgMap.js" type="text/javascript"></script>
		
		<script>
			function initMap() {
				
				window.footerMap = new LvgMap({
					containerId: "map",
					center: {
						lat: 50.7220111, 
						lng: 29.4550095
					},
					centerTitle: "Бар",
					markers: [
						{
							lat: 50.7215857, 
							lng: 29.4534646,
							title: "Магазин"
						}
					]
				});
				document.querySelector('.makeRoad').addEventListener('click', function(e) {
					e.preventDefault();
					window.footerMap.makeRoute();
				});
			}
			/*function calculateAndDisplayRoute(directionsService, directionsRenderer, map) {

				let pos = { lat: 50.388667, lng: 30.518540};
				let destination;
				let modeSelect = document.getElementById('mode');
				if (document.querySelector('#location').checked) {
					infoWindow = new google.maps.InfoWindow;

					if (navigator.geolocation) {
						navigator.geolocation.getCurrentPosition(function(position) {

							destination = {
								lat: position.coords.latitude,
								lng: position.coords.longitude
							};

							directionsService.route({
									origin: pos,
									destination: destination ,
									travelMode: "DRIVING"
								},
								function(response, status) {
									if (status === 'OK') {
										directionsRenderer.setDirections(response);
									} else {
										window.alert('Directions request failed due to ' + status);
									}
								});
						}, function() {
						});
					} else {
						// Browser doesn't support Geolocation
						console.log('Browser doesn\'t support Geolocation')
						alert('Browser doesn\'t support Geolocation')
					}

				} else {
					directionsService.route(
						{
							origin: pos,
							destination:  document.querySelector('#destination').value,
							travelMode: modeSelect[modeSelect.selectedIndex].value
						},
						function(response, status) {
							if (status === 'OK') {
								directionsRenderer.setDirections(response);
							} else {
								window.alert('Directions request failed due to ' + status);
							}
						});
				}
			}


			function initMap() {
				let directionsService = new google.maps.DirectionsService();
				let directionsRenderer = new google.maps.DirectionsRenderer();
				let pos = { lat: 50.388667, lng: 30.518540};

				let opt = {
					center: pos ,
					zoom: 15
				};

				let myMap = new google.maps.Map(document.getElementById("map"), opt);


				let marker = new google.maps.Marker({
					position: pos,
					map: myMap,
					title: 'Hello World!'
				});

				directionsRenderer.setMap(myMap);

				let onChangeHandler = function() {
					calculateAndDisplayRoute(directionsService, directionsRenderer, myMap);
				};

				document.querySelector('.makeRoad').addEventListener('click', onChangeHandler );
			}*/
		</script>
		<script asynch defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCpGlb7OvqiPwCftfQpJY3MMvO_CNQuMmo&callback=initMap"
			type="text/javascript"></script>
		
	</div>
	

</footer>