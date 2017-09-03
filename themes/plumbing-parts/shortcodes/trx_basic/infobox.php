<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_infobox_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_infobox_theme_setup' );
	function plumbing_parts_sc_infobox_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_infobox_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_infobox_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_infobox id="unique_id" style="regular|info|success|error|result" static="0|1"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_infobox]
*/

if (!function_exists('plumbing_parts_sc_infobox')) {	
	function plumbing_parts_sc_infobox($atts, $content=null){	
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . plumbing_parts_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');
		if (empty($icon)) {
			if ($icon=='none')
				$icon = '';
			else if ($style=='regular')
				$icon = 'icon-cogs';
			else if ($style=='success')
				$icon = 'icon-check';
			else if ($style=='error')
				$icon = 'icon-hand';
			else if ($style=='info')
				$icon = 'icon-info-1';
            else if ($style=='result')
                $icon = 'icon-attention';
		}
		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (plumbing_parts_param_is_on($closeable) ? ' sc_infobox_closeable' : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='' && !plumbing_parts_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '') 
					. '"'
				. (!plumbing_parts_param_is_off($animation) ? ' data-animation="'.esc_attr(plumbing_parts_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	plumbing_parts_require_shortcode('trx_infobox', 'plumbing_parts_sc_infobox');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_infobox_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_infobox_reg_shortcodes');
	function plumbing_parts_sc_infobox_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_infobox", array(
			"title" => esc_html__("Infobox", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Insert infobox into your post (page)", 'plumbing-parts') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Infobox style", 'plumbing-parts') ),
					"value" => "regular",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'regular' => esc_html__('Regular', 'plumbing-parts'),
						'info' => esc_html__('Info', 'plumbing-parts'),
						'success' => esc_html__('Success', 'plumbing-parts'),
						'error' => esc_html__('Error', 'plumbing-parts')
					)
				),
				"closeable" => array(
					"title" => esc_html__("Closeable box", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Create closeable box (with close button)", 'plumbing-parts') ),
					"value" => "no",
					"type" => "switch",
					"options" => plumbing_parts_get_sc_param('yes_no')
				),
				"icon" => array(
					"title" => esc_html__("Custom icon",  'plumbing-parts'),
					"desc" => wp_kses_data( __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'plumbing-parts') ),
					"value" => "",
					"type" => "icons",
					"options" => plumbing_parts_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Text color", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Any color for text and headers", 'plumbing-parts') ),
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Any background color for this infobox", 'plumbing-parts') ),
					"value" => "",
					"type" => "color"
				),
				"_content_" => array(
					"title" => esc_html__("Infobox content", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Content for infobox", 'plumbing-parts') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
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
if ( !function_exists( 'plumbing_parts_sc_infobox_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_infobox_reg_shortcodes_vc');
	function plumbing_parts_sc_infobox_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_infobox",
			"name" => esc_html__("Infobox", 'plumbing-parts'),
			"description" => wp_kses_data( __("Box with info or error message", 'plumbing-parts') ),
			"category" => esc_html__('Content', 'plumbing-parts'),
			'icon' => 'icon_trx_infobox',
			"class" => "trx_sc_container trx_sc_infobox",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'plumbing-parts'),
					"description" => wp_kses_data( __("Infobox style", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Regular', 'plumbing-parts') => 'regular',
							esc_html__('Info', 'plumbing-parts') => 'info',
							esc_html__('Success', 'plumbing-parts') => 'success',
							esc_html__('Error', 'plumbing-parts') => 'error',
							esc_html__('Result', 'plumbing-parts') => 'result'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "closeable",
					"heading" => esc_html__("Closeable", 'plumbing-parts'),
					"description" => wp_kses_data( __("Create closeable box (with close button)", 'plumbing-parts') ),
					"class" => "",
					"value" => array(esc_html__('Close button', 'plumbing-parts') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Custom icon", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select icon for the infobox from Fontello icons set. If empty - use default icon", 'plumbing-parts') ),
					"class" => "",
					"value" => plumbing_parts_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'plumbing-parts'),
					"description" => wp_kses_data( __("Any color for the text and headers", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'plumbing-parts'),
					"description" => wp_kses_data( __("Any background color for this infobox", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Message text", 'plumbing-parts'),
					"description" => wp_kses_data( __("Message for the infobox", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
				plumbing_parts_get_vc_param('id'),
				plumbing_parts_get_vc_param('class'),
				plumbing_parts_get_vc_param('animation'),
				plumbing_parts_get_vc_param('css'),
				plumbing_parts_get_vc_param('margin_top'),
				plumbing_parts_get_vc_param('margin_bottom'),
				plumbing_parts_get_vc_param('margin_left'),
				plumbing_parts_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Infobox extends PLUMBING_PARTS_VC_ShortCodeContainer {}
	}
}
?>