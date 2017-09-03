<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_button_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_button_theme_setup' );
	function plumbing_parts_sc_button_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_button_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('plumbing_parts_sc_button')) {	
	function plumbing_parts_sc_button($atts, $content=null){	
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "small",
			//"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
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
		$css .= plumbing_parts_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (plumbing_parts_param_is_on($popup)) plumbing_parts_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!plumbing_parts_param_is_off($animation) ? ' data-animation="'.esc_attr(plumbing_parts_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					//. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '')
					. (plumbing_parts_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</a>';
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	plumbing_parts_require_shortcode('trx_button', 'plumbing_parts_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_button_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_button_reg_shortcodes');
	function plumbing_parts_sc_button_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_button", array(
			"title" => esc_html__("Button", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Button with link", 'plumbing-parts') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Button caption", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"type" => array(
					"title" => esc_html__("Button's shape", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select button's shape", 'plumbing-parts') ),
					"value" => "square",
					"size" => "medium",
					"options" => array(
						'square' => esc_html__('Square', 'plumbing-parts'),
						'round' => esc_html__('Round', 'plumbing-parts')
					),
					"type" => "switch"
				), 
				"style" => array(
					"title" => esc_html__("Button's style", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select button's style", 'plumbing-parts') ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'plumbing-parts'),
						'filled2' => esc_html__('Filled2', 'plumbing-parts'),
						'border' => esc_html__('Border', 'plumbing-parts')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select button's size", 'plumbing-parts') ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'small' => esc_html__('Small', 'plumbing-parts'),
						'medium' => esc_html__('Medium', 'plumbing-parts'),
						'large' => esc_html__('Large', 'plumbing-parts')
					),
					"type" => "checklist"
				),
//				"icon" => array(
//					"title" => esc_html__("Button's icon",  'plumbing-parts'),
//					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'plumbing-parts') ),
//					"value" => "",
//					"type" => "icons",
//					"options" => plumbing_parts_get_sc_param('icons')
//				),
				"color" => array(
					"title" => esc_html__("Button's text color", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Any color for button's caption", 'plumbing-parts') ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Any color for button's background", 'plumbing-parts') ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Align button to left, center or right", 'plumbing-parts') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => plumbing_parts_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'plumbing-parts'),
					"desc" => wp_kses_data( __("URL for link on button click", 'plumbing-parts') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Target for link on button click", 'plumbing-parts') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Open link target in popup window", 'plumbing-parts') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => plumbing_parts_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Rel attribute for button's link (if need)", 'plumbing-parts') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
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
if ( !function_exists( 'plumbing_parts_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_button_reg_shortcodes_vc');
	function plumbing_parts_sc_button_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", 'plumbing-parts'),
			"description" => wp_kses_data( __("Button with link", 'plumbing-parts') ),
			"category" => esc_html__('Content', 'plumbing-parts'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", 'plumbing-parts'),
					"description" => wp_kses_data( __("Button caption", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Button's shape", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select button's shape", 'plumbing-parts') ),
					"class" => "",
					"value" => array(
						esc_html__('Square', 'plumbing-parts') => 'square',
						esc_html__('Round', 'plumbing-parts') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select button's style", 'plumbing-parts') ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'plumbing-parts') => 'filled',
						esc_html__('Filled2', 'plumbing-parts') => 'filled2',
						esc_html__('Border', 'plumbing-parts') => 'border'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select button's size", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Small', 'plumbing-parts') => 'small',
						esc_html__('Medium', 'plumbing-parts') => 'medium',
						esc_html__('Large', 'plumbing-parts') => 'large'
					),
					"type" => "dropdown"
				),
//				array(
//					"param_name" => "icon",
//					"heading" => esc_html__("Button's icon", 'plumbing-parts'),
//					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'plumbing-parts') ),
//					"class" => "",
//					"value" => plumbing_parts_get_sc_param('icons'),
//					"type" => "dropdown"
//				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", 'plumbing-parts'),
					"description" => wp_kses_data( __("Any color for button's caption", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", 'plumbing-parts'),
					"description" => wp_kses_data( __("Any color for button's background", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", 'plumbing-parts'),
					"description" => wp_kses_data( __("Align button to left, center or right", 'plumbing-parts') ),
					"class" => "",
					"value" => array_flip(plumbing_parts_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'plumbing-parts'),
					"description" => wp_kses_data( __("URL for the link on button click", 'plumbing-parts') ),
					"class" => "",
					"group" => esc_html__('Link', 'plumbing-parts'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'plumbing-parts'),
					"description" => wp_kses_data( __("Target for the link on button click", 'plumbing-parts') ),
					"class" => "",
					"group" => esc_html__('Link', 'plumbing-parts'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", 'plumbing-parts'),
					"description" => wp_kses_data( __("Open link target in popup window", 'plumbing-parts') ),
					"class" => "",
					"group" => esc_html__('Link', 'plumbing-parts'),
					"value" => array(esc_html__('Open in popup', 'plumbing-parts') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", 'plumbing-parts'),
					"description" => wp_kses_data( __("Rel attribute for the button's link (if need", 'plumbing-parts') ),
					"class" => "",
					"group" => esc_html__('Link', 'plumbing-parts'),
					"value" => "",
					"type" => "textfield"
				),
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
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends PLUMBING_PARTS_VC_ShortCodeSingle {}
	}
}
?>