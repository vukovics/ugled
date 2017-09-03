<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_dropcaps_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_dropcaps_theme_setup' );
	function plumbing_parts_sc_dropcaps_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_dropcaps_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_dropcaps_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_dropcaps id="unique_id" style="1-6"]paragraph text[/trx_dropcaps]

if (!function_exists('plumbing_parts_sc_dropcaps')) {	
	function plumbing_parts_sc_dropcaps($atts, $content=null){
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . plumbing_parts_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= plumbing_parts_get_css_dimensions_from_values($width, $height);
		$style = min(4, max(1, $style));
		$content = do_shortcode(str_replace(array('[vc_column_text]', '[/vc_column_text]'), array('', ''), $content));
		$output = plumbing_parts_substr($content, 0, 1) == '<' 
			? $content 
			: '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_dropcaps sc_dropcaps_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				. (!plumbing_parts_param_is_off($animation) ? ' data-animation="'.esc_attr(plumbing_parts_get_animation_classes($animation)).'"' : '')
				. '>' 
					. '<span class="sc_dropcaps_item">' . trim(plumbing_parts_substr($content, 0, 1)) . '</span>' . trim(plumbing_parts_substr($content, 1))
			. '</div>';
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_dropcaps', $atts, $content);
	}
	plumbing_parts_require_shortcode('trx_dropcaps', 'plumbing_parts_sc_dropcaps');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_dropcaps_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_dropcaps_reg_shortcodes');
	function plumbing_parts_sc_dropcaps_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_dropcaps", array(
			"title" => esc_html__("Dropcaps", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Make first letter as dropcaps", 'plumbing-parts') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Dropcaps style", 'plumbing-parts') ),
					"value" => "1",
					"type" => "checklist",
					"options" => plumbing_parts_get_list_styles(1, 4)
				),
				"_content_" => array(
					"title" => esc_html__("Paragraph content", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Paragraph with dropcaps content", 'plumbing-parts') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"width" => plumbing_parts_shortcodes_width(),
				"height" => plumbing_parts_shortcodes_height(),
				"top" => plumbing_parts_get_sc_param('top'),
				"bottom" => plumbing_parts_get_sc_param('bottom'),
				"left" => plumbing_parts_get_sc_param('left'),
				"right" => plumbing_parts_get_sc_param('right'),
				"id" => plumbing_parts_get_sc_param('id'),
				"class" => plumbing_parts_get_sc_param('class'),
				"animation" => plumbing_parts_get_sc_param('animation'),
				"css" => plumbing_parts_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_dropcaps_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_dropcaps_reg_shortcodes_vc');
	function plumbing_parts_sc_dropcaps_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_dropcaps",
			"name" => esc_html__("Dropcaps", 'plumbing-parts'),
			"description" => wp_kses_data( __("Make first letter of the text as dropcaps", 'plumbing-parts') ),
			"category" => esc_html__('Content', 'plumbing-parts'),
			'icon' => 'icon_trx_dropcaps',
			"class" => "trx_sc_container trx_sc_dropcaps",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'plumbing-parts'),
					"description" => wp_kses_data( __("Dropcaps style", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(plumbing_parts_get_list_styles(1, 4)),
					"type" => "dropdown"
				),
/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Paragraph text", 'plumbing-parts'),
					"description" => wp_kses_data( __("Paragraph with dropcaps content", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
*/
				plumbing_parts_get_vc_param('id'),
				plumbing_parts_get_vc_param('class'),
				plumbing_parts_get_vc_param('animation'),
				plumbing_parts_get_vc_param('css'),
				plumbing_parts_vc_width(),
				plumbing_parts_vc_height(),
				plumbing_parts_get_vc_param('margin_top'),
				plumbing_parts_get_vc_param('margin_bottom'),
				plumbing_parts_get_vc_param('margin_left'),
				plumbing_parts_get_vc_param('margin_right')
			)
		
		) );
		
		class WPBakeryShortCode_Trx_Dropcaps extends PLUMBING_PARTS_VC_ShortCodeContainer {}
	}
}
?>