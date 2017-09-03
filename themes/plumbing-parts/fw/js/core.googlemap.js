function plumbing_parts_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof PLUMBING_PARTS_STORAGE['googlemap_init_obj'] == 'undefined') plumbing_parts_googlemap_init_styles();
	PLUMBING_PARTS_STORAGE['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: PLUMBING_PARTS_STORAGE['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		plumbing_parts_googlemap_create(id);

	} catch (e) {
		
		dcl(PLUMBING_PARTS_STORAGE['strings']['googlemap_not_avail']);

	};
}

function plumbing_parts_googlemap_create(id) {
	"use strict";

	// Create map
	PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].map = new google.maps.Map(PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].dom, PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers)
		PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].inited = false;
	plumbing_parts_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].map)
			PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].map.setCenter(PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].opt.center);
	});
}

function plumbing_parts_googlemap_add_markers(id) {
	"use strict";
	for (var i in PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers) {
		
		if (PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (PLUMBING_PARTS_STORAGE['googlemap_init_obj'].geocoder == '') PLUMBING_PARTS_STORAGE['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].geocoder_request = i;
			PLUMBING_PARTS_STORAGE['googlemap_init_obj'].geocoder.geocode({address: PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].geocoder_request = false;
					setTimeout(function() { 
						plumbing_parts_googlemap_add_markers(id); 
						}, 200);
				} else
					dcl(PLUMBING_PARTS_STORAGE['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].point) markerInit.icon = PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].point;
			if (PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].title) markerInit.title = PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].title;
			PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].opt.center == null) {
				PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].opt.center = markerInit.position;
				PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].map.setCenter(PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].opt.center);				
			}
			
			// Add description window
			if (PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].description!='') {
				PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers) {
						if (latlng == PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].latlng) {
							PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].infowindow.open(
								PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].map,
								PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			PLUMBING_PARTS_STORAGE['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function plumbing_parts_googlemap_refresh() {
	"use strict";
	for (id in PLUMBING_PARTS_STORAGE['googlemap_init_obj']) {
		plumbing_parts_googlemap_create(id);
	}
}

function plumbing_parts_googlemap_init_styles() {
	// Init Google map
	PLUMBING_PARTS_STORAGE['googlemap_init_obj'] = {};
	PLUMBING_PARTS_STORAGE['googlemap_styles'] = {
		'default': []
	};
	if (window.plumbing_parts_theme_googlemap_styles!==undefined)
		PLUMBING_PARTS_STORAGE['googlemap_styles'] = plumbing_parts_theme_googlemap_styles(PLUMBING_PARTS_STORAGE['googlemap_styles']);
}