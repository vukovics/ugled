<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_br_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_br_theme_setup' );
	function plumbing_parts_sc_br_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_br_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_br_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('plumbing_parts_sc_br')) {	
	function plumbing_parts_sc_br($atts, $content = null) {
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	plumbing_parts_require_shortcode("trx_br", "plumbing_parts_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_br_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_br_reg_shortcodes');
	function plumbing_parts_sc_br_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_br", array(
			"title" => esc_html__("Break", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Line break with clear floating (if need)", 'plumbing-parts') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Clear floating (if need)", 'plumbing-parts') ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'plumbing-parts'),
						'left' => esc_html__('Left', 'plumbing-parts'),
						'right' => esc_html__('Right', 'plumbing-parts'),
						'both' => esc_html__('Both', 'plumbing-parts')
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_br_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_br_reg_shortcodes_vc');
	function plumbing_parts_sc_br_reg_shortcodes_vc() {
/*
		vc_map( array(
			"base" => "trx_br",
			"name" => esc_html__("Line break", 'plumbing-parts'),
			"description" => wp_kses_data( __("Line break or Clear Floating", 'plumbing-parts') ),
			"category" => esc_html__('Content', 'plumbing-parts'),
			'icon' => 'icon_trx_br',
			"class" => "trx_sc_single trx_sc_br",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "clear",
					"heading" => esc_html__("Clear floating", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select clear side (if need)", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"value" => array(
						esc_html__('None', 'plumbing-parts') => 'none',
						esc_html__('Left', 'plumbing-parts') => 'left',
						esc_html__('Right', 'plumbing-parts') => 'right',
						esc_html__('Both', 'plumbing-parts') => 'both'
					),
					"type" => "dropdown"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Br extends PLUMBING_PARTS_VC_ShortCodeSingle {}
*/
	}
}
?>