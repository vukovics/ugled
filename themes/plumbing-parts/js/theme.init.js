/* global jQuery:false */
/* global PLUMBING_PARTS_STORAGE:false */


// Theme-specific first load actions
//==============================================
function plumbing_parts_theme_ready_actions() {
	"use strict";
	// Put here your init code with theme-specific actions
	// It will be called before core actions
}


// Theme-specific scroll actions
//==============================================
function plumbing_parts_theme_scroll_actions() {
	"use strict";
	// Put here your theme-specific code with scroll actions
	// It will be called when page is scrolled (before core actions)
}


// Theme-specific resize actions
//==============================================
function plumbing_parts_theme_resize_actions() {
	"use strict";
	// Put here your theme-specific code with resize actions
	// It will be called when window is resized (before core actions)
}


// Theme-specific shortcodes init
//=====================================================
function plumbing_parts_theme_sc_init(cont) {
	"use strict";
	// Put here your theme-specific code to init shortcodes
	// It will be called before core init shortcodes
	// @param cont - jQuery-container with shortcodes (init only inside this container)
}


// Theme-specific post-formats init
//=====================================================
function plumbing_parts_theme_init_post_formats() {
	"use strict";
	// Put here your theme-specific code to init post-formats
	// It will be called before core init post_formats when page is loaded or after 'Load more' or 'Infinite scroll' actions
}


// Theme-specific GoogleMap styles
//=====================================================
function plumbing_parts_theme_googlemap_styles($styles) {
	"use strict";
	// Put here your theme-specific code to add GoogleMap styles
	// It will be called before GoogleMap init when page is loaded
	$styles['greyscale'] = [
    	{ "stylers": [
        	{ "saturation": -100 }
            ]
        }
	];
	$styles['inverse'] = [
		{ "stylers": [
			{ "invert_lightness": true },
			{ "visibility": "on" }
			]
		}
	];
	$styles['simple'] = [
    	{ stylers: [
        	{ hue: "#00ffe6" },
            { saturation: -20 }
			]
		},
		{ featureType: "road",
          elementType: "geometry",
          stylers: [
			{ lightness: 100 },
           	{ visibility: "simplified" }
            ]
		},
		{ featureType: "road",
          elementType: "labels",
          stylers: [
          	{ visibility: "off" }
            ]
		}
	];
	return $styles;
}
