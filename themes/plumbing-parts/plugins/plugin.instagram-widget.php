<?php
/* Instagram Widget support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('plumbing_parts_instagram_widget_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_instagram_widget_theme_setup', 1 );
	function plumbing_parts_instagram_widget_theme_setup() {
		if (plumbing_parts_exists_instagram_widget()) {
			add_action( 'plumbing_parts_action_add_styles', 						'plumbing_parts_instagram_widget_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'plumbing_parts_filter_importer_required_plugins',		'plumbing_parts_instagram_widget_importer_required_plugins', 10, 2 );
			add_filter( 'plumbing_parts_filter_required_plugins',					'plumbing_parts_instagram_widget_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'plumbing_parts_exists_instagram_widget' ) ) {
	function plumbing_parts_exists_instagram_widget() {
		return function_exists('wpiw_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'plumbing_parts_instagram_widget_required_plugins' ) ) {
	//add_filter('plumbing_parts_filter_required_plugins',	'plumbing_parts_instagram_widget_required_plugins');
	function plumbing_parts_instagram_widget_required_plugins($list=array()) {
		if (in_array('instagram_widget', plumbing_parts_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Instagram Widget', 'plumbing-parts'),
					'slug' 		=> 'wp-instagram-widget',
					'required' 	=> false
				);
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'plumbing_parts_instagram_widget_frontend_scripts' ) ) {
	//add_action( 'plumbing_parts_action_add_styles', 'plumbing_parts_instagram_widget_frontend_scripts' );
	function plumbing_parts_instagram_widget_frontend_scripts() {
		if (file_exists(plumbing_parts_get_file_dir('css/plugin.instagram-widget.css')))
			plumbing_parts_enqueue_style( 'plumbing_parts-plugin.instagram-widget-style',  plumbing_parts_get_file_url('css/plugin.instagram-widget.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Widget in the required plugins
if ( !function_exists( 'plumbing_parts_instagram_widget_importer_required_plugins' ) ) {
	//add_filter( 'plumbing_parts_filter_importer_required_plugins',	'plumbing_parts_instagram_widget_importer_required_plugins', 10, 2 );
	function plumbing_parts_instagram_widget_importer_required_plugins($not_installed='', $list='') {
		if (plumbing_parts_strpos($list, 'instagram_widget')!==false && !plumbing_parts_exists_instagram_widget() )
			$not_installed .= '<br>' . esc_html__('WP Instagram Widget', 'plumbing-parts');
		return $not_installed;
	}
}
?>