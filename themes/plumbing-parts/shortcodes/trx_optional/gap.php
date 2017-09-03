<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_gap_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_gap_theme_setup' );
	function plumbing_parts_sc_gap_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_gap_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_gap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_gap]Fullwidth content[/trx_gap]

if (!function_exists('plumbing_parts_sc_gap')) {	
	function plumbing_parts_sc_gap($atts, $content = null) {
		if (plumbing_parts_in_shortcode_blogger()) return '';
		$output = plumbing_parts_gap_start() . do_shortcode($content) . plumbing_parts_gap_end();
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_gap', $atts, $content);
	}
	plumbing_parts_require_shortcode("trx_gap", "plumbing_parts_sc_gap");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_gap_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_gap_reg_shortcodes');
	function plumbing_parts_sc_gap_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_gap", array(
			"title" => esc_html__("Gap", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", 'plumbing-parts') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Gap content", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Gap inner content", 'plumbing-parts') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_gap_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_gap_reg_shortcodes_vc');
	function plumbing_parts_sc_gap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_gap",
			"name" => esc_html__("Gap", 'plumbing-parts'),
			"description" => wp_kses_data( __("Insert gap (fullwidth area) in the post content", 'plumbing-parts') ),
			"category" => esc_html__('Structure', 'plumbing-parts'),
			'icon' => 'icon_trx_gap',
			"class" => "trx_sc_collection trx_sc_gap",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"params" => array(
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Gap content", 'plumbing-parts'),
					"description" => wp_kses_data( __("Gap inner content", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				)
				*/
			)
		) );
		
		class WPBakeryShortCode_Trx_Gap extends PLUMBING_PARTS_VC_ShortCodeCollection {}
	}
}
?>