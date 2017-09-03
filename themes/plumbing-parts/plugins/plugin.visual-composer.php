<?php
/* Visual Composer support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('plumbing_parts_vc_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_vc_theme_setup', 1 );
	function plumbing_parts_vc_theme_setup() {
		if (plumbing_parts_exists_visual_composer()) {
			if (is_admin()) {
				add_filter( 'plumbing_parts_filter_importer_options',				'plumbing_parts_vc_importer_set_options' );
			}
			add_action('plumbing_parts_action_add_styles',		 				'plumbing_parts_vc_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'plumbing_parts_filter_importer_required_plugins',		'plumbing_parts_vc_importer_required_plugins', 10, 2 );
			add_filter( 'plumbing_parts_filter_required_plugins',					'plumbing_parts_vc_required_plugins' );
		}
	}
}

// Check if Visual Composer installed and activated
if ( !function_exists( 'plumbing_parts_exists_visual_composer' ) ) {
	function plumbing_parts_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if Visual Composer in frontend editor mode
if ( !function_exists( 'plumbing_parts_vc_is_frontend' ) ) {
	function plumbing_parts_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
		//return function_exists('vc_is_frontend_editor') && vc_is_frontend_editor();
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'plumbing_parts_vc_required_plugins' ) ) {
	//add_filter('plumbing_parts_filter_required_plugins',	'plumbing_parts_vc_required_plugins');
	function plumbing_parts_vc_required_plugins($list=array()) {
		if (in_array('visual_composer', plumbing_parts_storage_get('required_plugins'))) {
			$path = plumbing_parts_get_file_dir('plugins/install/js_composer.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> 'Visual Composer',
					'slug' 		=> 'js_composer',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Enqueue VC custom styles
if ( !function_exists( 'plumbing_parts_vc_frontend_scripts' ) ) {
	//add_action( 'plumbing_parts_action_add_styles', 'plumbing_parts_vc_frontend_scripts' );
	function plumbing_parts_vc_frontend_scripts() {
		if (file_exists(plumbing_parts_get_file_dir('css/plugin.visual-composer.css')))
			plumbing_parts_enqueue_style( 'plumbing_parts-plugin.visual-composer-style',  plumbing_parts_get_file_url('css/plugin.visual-composer.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check VC in the required plugins
if ( !function_exists( 'plumbing_parts_vc_importer_required_plugins' ) ) {
	//add_filter( 'plumbing_parts_filter_importer_required_plugins',	'plumbing_parts_vc_importer_required_plugins', 10, 2 );
	function plumbing_parts_vc_importer_required_plugins($not_installed='', $list='') {
		if (!plumbing_parts_exists_visual_composer() )		// && plumbing_parts_strpos($list, 'visual_composer')!==false
			$not_installed .= '<br>' . esc_html__('Visual Composer', 'plumbing-parts');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'plumbing_parts_vc_importer_set_options' ) ) {
	//add_filter( 'plumbing_parts_filter_importer_options',	'plumbing_parts_vc_importer_set_options' );
	function plumbing_parts_vc_importer_set_options($options=array()) {
		if ( in_array('visual_composer', plumbing_parts_storage_get('required_plugins')) && plumbing_parts_exists_visual_composer() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'wpb_js_templates';
		}
		return $options;
	}
}
?>