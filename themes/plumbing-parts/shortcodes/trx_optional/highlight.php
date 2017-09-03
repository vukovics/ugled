<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_highlight_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_highlight_theme_setup' );
	function plumbing_parts_sc_highlight_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_highlight_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_highlight_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_highlight id="unique_id" color="fore_color's_name_or_#rrggbb" backcolor="back_color's_name_or_#rrggbb" style="custom_style"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_highlight]
*/

if (!function_exists('plumbing_parts_sc_highlight')) {	
	function plumbing_parts_sc_highlight($atts, $content=null){	
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(plumbing_parts_prepare_css_value($font_size)) . '; line-height: 1em;' : '');
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</span>';
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	plumbing_parts_require_shortcode('trx_highlight', 'plumbing_parts_sc_highlight');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_highlight_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_highlight_reg_shortcodes');
	function plumbing_parts_sc_highlight_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_highlight", array(
			"title" => esc_html__("Highlight text", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'plumbing-parts') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Type", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Highlight type", 'plumbing-parts') ),
					"value" => "1",
					"type" => "checklist",
					"options" => array(
						0 => esc_html__('Custom', 'plumbing-parts'),
						1 => esc_html__('Type 1', 'plumbing-parts'),
						2 => esc_html__('Type 2', 'plumbing-parts'),
						3 => esc_html__('Type 3', 'plumbing-parts')
					)
				),
				"color" => array(
					"title" => esc_html__("Color", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Color for the highlighted text", 'plumbing-parts') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Background color for the highlighted text", 'plumbing-parts') ),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Highlighting content", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Content for highlight", 'plumbing-parts') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => plumbing_parts_get_sc_param('id'),
				"class" => plumbing_parts_get_sc_param('class'),
				"css" => plumbing_parts_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_highlight_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_highlight_reg_shortcodes_vc');
	function plumbing_parts_sc_highlight_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_highlight",
			"name" => esc_html__("Highlight text", 'plumbing-parts'),
			"description" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'plumbing-parts') ),
			"category" => esc_html__('Content', 'plumbing-parts'),
			'icon' => 'icon_trx_highlight',
			"class" => "trx_sc_single trx_sc_highlight",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'plumbing-parts'),
					"description" => wp_kses_data( __("Highlight type", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Custom', 'plumbing-parts') => 0,
							esc_html__('Type 1', 'plumbing-parts') => 1,
							esc_html__('Type 2', 'plumbing-parts') => 2,
							esc_html__('Type 3', 'plumbing-parts') => 3
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'plumbing-parts'),
					"description" => wp_kses_data( __("Color for the highlighted text", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'plumbing-parts'),
					"description" => wp_kses_data( __("Background color for the highlighted text", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'plumbing-parts'),
					"description" => wp_kses_data( __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Highlight text", 'plumbing-parts'),
					"description" => wp_kses_data( __("Content for highlight", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				plumbing_parts_get_vc_param('id'),
				plumbing_parts_get_vc_param('class'),
				plumbing_parts_get_vc_param('css')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Highlight extends PLUMBING_PARTS_VC_ShortCodeSingle {}
	}
}
?>