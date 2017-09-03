<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_emailer_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_emailer_theme_setup' );
	function plumbing_parts_sc_emailer_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_emailer_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_emailer_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_emailer group=""]

if (!function_exists('plumbing_parts_sc_emailer')) {	
	function plumbing_parts_sc_emailer($atts, $content = null) {
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"group" => "",
			"open" => "yes",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . plumbing_parts_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= plumbing_parts_get_css_dimensions_from_values($width, $height);
		// Load core messages
		plumbing_parts_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
					. ' class="sc_emailer' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (plumbing_parts_param_is_on($open) ? ' sc_emailer_opened' : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
					. ($css ? ' style="'.esc_attr($css).'"' : '') 
					. (!plumbing_parts_param_is_off($animation) ? ' data-animation="'.esc_attr(plumbing_parts_get_animation_classes($animation)).'"' : '')
					. '>'
				. '<form class="sc_emailer_form">'
				. '<input type="text" class="sc_emailer_input" name="email" value="" placeholder="'.esc_attr__('Enter your e-mail', 'plumbing-parts').'">'
				. '<a href="#" class="sc_emailer_button" title="'.esc_attr__('Submit', 'plumbing-parts').'" data-group="'.esc_attr($group ? $group : esc_html__('E-mailer subscription', 'plumbing-parts')).'">subscribe</a>'
				. '</form>'
			. '</div>';
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_emailer', $atts, $content);
	}
	plumbing_parts_require_shortcode("trx_emailer", "plumbing_parts_sc_emailer");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_emailer_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_emailer_reg_shortcodes');
	function plumbing_parts_sc_emailer_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_emailer", array(
			"title" => esc_html__("E-mail collector", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Collect the e-mail address into specified group", 'plumbing-parts') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"group" => array(
					"title" => esc_html__("Group", 'plumbing-parts'),
					"desc" => wp_kses_data( __("The name of group to collect e-mail address", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"open" => array(
					"title" => esc_html__("Open", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Initially open the input field on show object", 'plumbing-parts') ),
					"divider" => true,
					"value" => "yes",
					"type" => "switch",
					"options" => plumbing_parts_get_sc_param('yes_no')
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Align object to left, center or right", 'plumbing-parts') ),
					"divider" => true,
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => plumbing_parts_get_sc_param('align')
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
if ( !function_exists( 'plumbing_parts_sc_emailer_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_emailer_reg_shortcodes_vc');
	function plumbing_parts_sc_emailer_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_emailer",
			"name" => esc_html__("E-mail collector", 'plumbing-parts'),
			"description" => wp_kses_data( __("Collect e-mails into specified group", 'plumbing-parts') ),
			"category" => esc_html__('Content', 'plumbing-parts'),
			'icon' => 'icon_trx_emailer',
			"class" => "trx_sc_single trx_sc_emailer",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "group",
					"heading" => esc_html__("Group", 'plumbing-parts'),
					"description" => wp_kses_data( __("The name of group to collect e-mail address", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "open",
					"heading" => esc_html__("Opened", 'plumbing-parts'),
					"description" => wp_kses_data( __("Initially open the input field on show object", 'plumbing-parts') ),
					"class" => "",
					"value" => array(esc_html__('Initially opened', 'plumbing-parts') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'plumbing-parts'),
					"description" => wp_kses_data( __("Align field to left, center or right", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(plumbing_parts_get_sc_param('align')),
					"type" => "dropdown"
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Emailer extends PLUMBING_PARTS_VC_ShortCodeSingle {}
	}
}
?>