<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_icon_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_icon_theme_setup' );
	function plumbing_parts_sc_icon_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_icon_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_icon_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('plumbing_parts_sc_icon')) {	
	function plumbing_parts_sc_icon($atts, $content=null){	
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . plumbing_parts_get_css_position_as_classes($top, $right, $bottom, $left);
		$css2 = ($font_weight != '' && !plumbing_parts_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(plumbing_parts_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || plumbing_parts_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !plumbing_parts_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($class ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	plumbing_parts_require_shortcode('trx_icon', 'plumbing_parts_sc_icon');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_icon_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_icon_reg_shortcodes');
	function plumbing_parts_sc_icon_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_icon", array(
			"title" => esc_html__("Icon", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Insert icon", 'plumbing-parts') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__('Icon',  'plumbing-parts'),
					"desc" => wp_kses_data( __('Select font icon from the Fontello icons set',  'plumbing-parts') ),
					"value" => "",
					"type" => "icons",
					"options" => plumbing_parts_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Icon's color", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Icon's color", 'plumbing-parts') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"bg_shape" => array(
					"title" => esc_html__("Background shape", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Shape of the icon background", 'plumbing-parts') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "none",
					"type" => "radio",
					"options" => array(
						'none' => esc_html__('None', 'plumbing-parts'),
						'round' => esc_html__('Round', 'plumbing-parts'),
						'square' => esc_html__('Square', 'plumbing-parts')
					)
				),
				"bg_color" => array(
					"title" => esc_html__("Icon's background color", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Icon's background color", 'plumbing-parts') ),
					"dependency" => array(
						'icon' => array('not_empty'),
						'background' => array('round','square')
					),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Icon's font size", 'plumbing-parts') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "spinner",
					"min" => 8,
					"max" => 240
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Icon font weight", 'plumbing-parts') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'plumbing-parts'),
						'300' => esc_html__('Light (300)', 'plumbing-parts'),
						'400' => esc_html__('Normal (400)', 'plumbing-parts'),
						'700' => esc_html__('Bold (700)', 'plumbing-parts')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Icon text alignment", 'plumbing-parts') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => plumbing_parts_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Link URL from this icon (if not empty)", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"top" => plumbing_parts_get_sc_param('top'),
				"bottom" => plumbing_parts_get_sc_param('bottom'),
				"left" => plumbing_parts_get_sc_param('left'),
				"right" => plumbing_parts_get_sc_param('right'),
				"id" => plumbing_parts_get_sc_param('id'),
				"class" => plumbing_parts_get_sc_param('class'),
				"css" => plumbing_parts_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_icon_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_icon_reg_shortcodes_vc');
	function plumbing_parts_sc_icon_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_icon",
			"name" => esc_html__("Icon", 'plumbing-parts'),
			"description" => wp_kses_data( __("Insert the icon", 'plumbing-parts') ),
			"category" => esc_html__('Content', 'plumbing-parts'),
			'icon' => 'icon_trx_icon',
			"class" => "trx_sc_single trx_sc_icon",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select icon class from Fontello icons set", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => plumbing_parts_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'plumbing-parts'),
					"description" => wp_kses_data( __("Icon's color", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'plumbing-parts'),
					"description" => wp_kses_data( __("Background color for the icon", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_shape",
					"heading" => esc_html__("Background shape", 'plumbing-parts'),
					"description" => wp_kses_data( __("Shape of the icon background", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('None', 'plumbing-parts') => 'none',
						esc_html__('Round', 'plumbing-parts') => 'round',
						esc_html__('Square', 'plumbing-parts') => 'square'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'plumbing-parts'),
					"description" => wp_kses_data( __("Icon's font size", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'plumbing-parts'),
					"description" => wp_kses_data( __("Icon's font weight", 'plumbing-parts') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'plumbing-parts') => 'inherit',
						esc_html__('Thin (100)', 'plumbing-parts') => '100',
						esc_html__('Light (300)', 'plumbing-parts') => '300',
						esc_html__('Normal (400)', 'plumbing-parts') => '400',
						esc_html__('Bold (700)', 'plumbing-parts') => '700'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Icon's alignment", 'plumbing-parts'),
					"description" => wp_kses_data( __("Align icon to left, center or right", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(plumbing_parts_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'plumbing-parts'),
					"description" => wp_kses_data( __("Link URL from this icon (if not empty)", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				plumbing_parts_get_vc_param('id'),
				plumbing_parts_get_vc_param('class'),
				plumbing_parts_get_vc_param('css'),
				plumbing_parts_get_vc_param('margin_top'),
				plumbing_parts_get_vc_param('margin_bottom'),
				plumbing_parts_get_vc_param('margin_left'),
				plumbing_parts_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Icon extends PLUMBING_PARTS_VC_ShortCodeSingle {}
	}
}
?>