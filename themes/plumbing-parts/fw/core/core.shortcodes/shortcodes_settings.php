<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'plumbing_parts_shortcodes_is_used' ) ) {
	function plumbing_parts_shortcodes_is_used() {
		return plumbing_parts_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && plumbing_parts_strpos($_SERVER['REQUEST_URI'], 'vc-roles')!==false)			// VC Role Manager
			|| (function_exists('plumbing_parts_vc_is_frontend') && plumbing_parts_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'plumbing_parts_shortcodes_width' ) ) {
	function plumbing_parts_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'plumbing-parts'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'plumbing_parts_shortcodes_height' ) ) {
	function plumbing_parts_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Width and height of the element", 'plumbing-parts') ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'plumbing_parts_get_sc_param' ) ) {
	function plumbing_parts_get_sc_param($prm) {
		return plumbing_parts_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'plumbing_parts_set_sc_param' ) ) {
	function plumbing_parts_set_sc_param($prm, $val) {
		plumbing_parts_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'plumbing_parts_sc_map' ) ) {
	function plumbing_parts_sc_map($sc_name, $sc_settings) {
		plumbing_parts_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'plumbing_parts_sc_map_after' ) ) {
	function plumbing_parts_sc_map_after($after, $sc_name, $sc_settings='') {
		plumbing_parts_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'plumbing_parts_sc_map_before' ) ) {
	function plumbing_parts_sc_map_before($before, $sc_name, $sc_settings='') {
		plumbing_parts_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}

// Compare two shortcodes by title
if ( !function_exists( 'plumbing_parts_compare_sc_title' ) ) {
	function plumbing_parts_compare_sc_title($a, $b) {
		return strcmp($a['title'], $b['title']);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'plumbing_parts_shortcodes_settings_theme_setup' ) ) {
//	if ( plumbing_parts_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'plumbing_parts_action_after_init_theme', 'plumbing_parts_shortcodes_settings_theme_setup' );
	function plumbing_parts_shortcodes_settings_theme_setup() {
		if (plumbing_parts_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = plumbing_parts_storage_get('registered_templates');
			ksort($tmp);
			plumbing_parts_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			plumbing_parts_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'plumbing-parts'),
					"desc" => wp_kses_data( __("ID for current element", 'plumbing-parts') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'plumbing-parts'),
					"desc" => wp_kses_data( __("CSS class for current element (optional)", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Any additional CSS rules (if need)", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'plumbing-parts'),
					'ol'	=> esc_html__('Ordered', 'plumbing-parts'),
					'iconed'=> esc_html__('Iconed', 'plumbing-parts')
				),

				'yes_no'	=> plumbing_parts_get_list_yesno(),
				'on_off'	=> plumbing_parts_get_list_onoff(),
				'dir' 		=> plumbing_parts_get_list_directions(),
				'align'		=> plumbing_parts_get_list_alignments(),
				'float'		=> plumbing_parts_get_list_floats(),
				'hpos'		=> plumbing_parts_get_list_hpos(),
				'show_hide'	=> plumbing_parts_get_list_showhide(),
				'sorting' 	=> plumbing_parts_get_list_sortings(),
				'ordering' 	=> plumbing_parts_get_list_orderings(),
				'shapes'	=> plumbing_parts_get_list_shapes(),
				'sizes'		=> plumbing_parts_get_list_sizes(),
				'sliders'	=> plumbing_parts_get_list_sliders(),
				'controls'	=> plumbing_parts_get_list_controls(),
				'categories'=> plumbing_parts_get_list_categories(),
				'columns'	=> plumbing_parts_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), plumbing_parts_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), plumbing_parts_get_list_icons()),
				'locations'	=> plumbing_parts_get_list_dedicated_locations(),
				'filters'	=> plumbing_parts_get_list_portfolio_filters(),
				'formats'	=> plumbing_parts_get_list_post_formats_filters(),
				'hovers'	=> plumbing_parts_get_list_hovers(true),
				'hovers_dir'=> plumbing_parts_get_list_hovers_directions(true),
				'schemes'	=> plumbing_parts_get_list_color_schemes(true),
				'animations'		=> plumbing_parts_get_list_animations_in(),
				'margins' 			=> plumbing_parts_get_list_margins(true),
				'blogger_styles'	=> plumbing_parts_get_list_templates_blogger(),
				'forms'				=> plumbing_parts_get_list_templates_forms(),
				'posts_types'		=> plumbing_parts_get_list_posts_types(),
				'googlemap_styles'	=> plumbing_parts_get_list_googlemap_styles(),
				'field_types'		=> plumbing_parts_get_list_field_types(),
				'label_positions'	=> plumbing_parts_get_list_label_positions()
				)
			);

			// Common params
			plumbing_parts_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'plumbing-parts'),
				"desc" => wp_kses_data( __('Select animation while object enter in the visible area of page',  'plumbing-parts') ),
				"value" => "none",
				"type" => "select",
				"options" => plumbing_parts_get_sc_param('animations')
				)
			);
			plumbing_parts_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'plumbing-parts'),
				"divider" => true,
				"value" => "inherit",
				"type" => "select",
				"options" => plumbing_parts_get_sc_param('margins')
				)
			);
			plumbing_parts_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'plumbing-parts'),
				"value" => "inherit",
				"type" => "select",
				"options" => plumbing_parts_get_sc_param('margins')
				)
			);
			plumbing_parts_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'plumbing-parts'),
				"value" => "inherit",
				"type" => "select",
				"options" => plumbing_parts_get_sc_param('margins')
				)
			);
			plumbing_parts_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'plumbing-parts'),
				"desc" => wp_kses_data( __("Margins around this shortcode", 'plumbing-parts') ),
				"value" => "inherit",
				"type" => "select",
				"options" => plumbing_parts_get_sc_param('margins')
				)
			);

			plumbing_parts_storage_set('sc_params', apply_filters('plumbing_parts_filter_shortcodes_params', plumbing_parts_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			plumbing_parts_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('plumbing_parts_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = plumbing_parts_storage_get('shortcodes');
			uasort($tmp, 'plumbing_parts_compare_sc_title');
			plumbing_parts_storage_set('shortcodes', $tmp);
		}
	}
}
?>