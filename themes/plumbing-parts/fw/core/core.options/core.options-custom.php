<?php
/**
 * Plumbing Parts Framework: Theme options custom fields
 *
 * @package	plumbing_parts
 * @since	plumbing_parts 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'plumbing_parts_options_custom_theme_setup' ) ) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_options_custom_theme_setup' );
	function plumbing_parts_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'plumbing_parts_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'plumbing_parts_options_custom_load_scripts' ) ) {
	//add_action("admin_enqueue_scripts", 'plumbing_parts_options_custom_load_scripts');
	function plumbing_parts_options_custom_load_scripts() {
		plumbing_parts_enqueue_script( 'plumbing_parts-options-custom-script',	plumbing_parts_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );	
	}
}


// Show theme specific fields in Post (and Page) options
if ( !function_exists( 'plumbing_parts_show_custom_field' ) ) {
	function plumbing_parts_show_custom_field($id, $field, $value) {
		$output = '';
		switch ($field['type']) {
			case 'reviews':
				$output .= '<div class="reviews_block">' . trim(plumbing_parts_reviews_get_markup($field, $value, true)) . '</div>';
				break;
	
			case 'mediamanager':
				wp_enqueue_media( );
				$output .= '<a id="'.esc_attr($id).'" class="button mediamanager plumbing_parts_media_selector"
					data-param="' . esc_attr($id) . '"
					data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'plumbing-parts') : esc_html__( 'Choose Image', 'plumbing-parts')).'"
					data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'plumbing-parts') : esc_html__( 'Choose Image', 'plumbing-parts')).'"
					data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
					data-linked-field="'.esc_attr($field['media_field_id']).'"
					>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'plumbing-parts') : esc_html__( 'Choose Image', 'plumbing-parts')) . '</a>';
				break;
		}
		return apply_filters('plumbing_parts_filter_show_custom_field', $output, $id, $field, $value);
	}
}
?>