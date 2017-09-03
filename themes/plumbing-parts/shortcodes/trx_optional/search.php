<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_search_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_search_theme_setup' );
	function plumbing_parts_sc_search_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_search_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('plumbing_parts_sc_search')) {	
	function plumbing_parts_sc_search($atts, $content=null){	
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"state" => "fixed",
			"scheme" => "original",
			"ajax" => "",
			"title" => esc_html__('Search', 'plumbing-parts'),
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
		if (empty($ajax)) $ajax = plumbing_parts_get_theme_option('use_ajax_search');
		// Load core messages
		plumbing_parts_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (plumbing_parts_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!plumbing_parts_param_is_off($animation) ? ' data-animation="'.esc_attr(plumbing_parts_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit icon-search" title="' . ($state=='closed' ? esc_attr__('Open search', 'plumbing-parts') : esc_attr__('Start search', 'plumbing-parts')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />
							</form>
						</div>
						<div class="search_results widget_area' . ($scheme && !plumbing_parts_param_is_off($scheme) && !plumbing_parts_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>
				</div>';
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	plumbing_parts_require_shortcode('trx_search', 'plumbing_parts_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_search_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_search_reg_shortcodes');
	function plumbing_parts_sc_search_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_search", array(
			"title" => esc_html__("Search", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Show search form", 'plumbing-parts') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select style to display search field", 'plumbing-parts') ),
					"value" => "regular",
					"options" => array(
						"regular" => esc_html__('Regular', 'plumbing-parts'),
						"rounded" => esc_html__('Rounded', 'plumbing-parts')
					),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select search field initial state", 'plumbing-parts') ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'plumbing-parts'),
						"opened" => esc_html__('Opened', 'plumbing-parts'),
						"closed" => esc_html__('Closed', 'plumbing-parts')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Title (placeholder) for the search field", 'plumbing-parts') ),
					"value" => esc_html__("Search &hellip;", 'plumbing-parts'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Search via AJAX or reload page", 'plumbing-parts') ),
					"value" => "yes",
					"options" => plumbing_parts_get_sc_param('yes_no'),
					"type" => "switch"
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
if ( !function_exists( 'plumbing_parts_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_search_reg_shortcodes_vc');
	function plumbing_parts_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", 'plumbing-parts'),
			"description" => wp_kses_data( __("Insert search form", 'plumbing-parts') ),
			"category" => esc_html__('Content', 'plumbing-parts'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select style to display search field", 'plumbing-parts') ),
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'plumbing-parts') => "regular",
						esc_html__('Flat', 'plumbing-parts') => "flat"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select search field initial state", 'plumbing-parts') ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'plumbing-parts')  => "fixed",
						esc_html__('Opened', 'plumbing-parts') => "opened",
						esc_html__('Closed', 'plumbing-parts') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'plumbing-parts'),
					"description" => wp_kses_data( __("Title (placeholder) for the search field", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'plumbing-parts'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", 'plumbing-parts'),
					"description" => wp_kses_data( __("Search via AJAX or reload page", 'plumbing-parts') ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'plumbing-parts') => 'yes'),
					"type" => "checkbox"
				),
				plumbing_parts_get_vc_param('id'),
				plumbing_parts_get_vc_param('class'),
				plumbing_parts_get_vc_param('animation'),
				plumbing_parts_get_vc_param('css'),
				plumbing_parts_get_vc_param('margin_top'),
				plumbing_parts_get_vc_param('margin_bottom'),
				plumbing_parts_get_vc_param('margin_left'),
				plumbing_parts_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Search extends PLUMBING_PARTS_VC_ShortCodeSingle {}
	}
}
?>