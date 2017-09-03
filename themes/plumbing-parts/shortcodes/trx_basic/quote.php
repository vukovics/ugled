<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_quote_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_quote_theme_setup' );
	function plumbing_parts_sc_quote_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_quote_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_quote_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_quote id="unique_id" cite="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/quote]
*/

if (!function_exists('plumbing_parts_sc_quote')) {	
	function plumbing_parts_sc_quote($atts, $content=null){	
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
            "style" => "1",
			"cite" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . plumbing_parts_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= plumbing_parts_get_css_dimensions_from_values($width);
        $style = min(2, max(1, $style));
		$cite_param = $cite != '' ? ' cite="'.esc_attr($cite).'"' : '';
		$title = $title=='' ? $cite : $title;
		$content = do_shortcode($content);
		if (plumbing_parts_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<blockquote' 
			. ($id ? ' id="'.esc_attr($id).'"' : '') . ($cite_param) 
			. ' class="sc_quote sc_quote_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '').'"'
			. (!plumbing_parts_param_is_off($animation) ? ' data-animation="'.esc_attr(plumbing_parts_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
				. ($content)
				. ($title == '' ? '' : ('<p class="sc_quote_title">' . ($cite!='' ? '<a href="'.esc_url($cite).'">' : '') . ($title) . ($cite!='' ? '</a>' : '') . '</p>'))
			.'</blockquote>';
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_quote', $atts, $content);
	}
	plumbing_parts_require_shortcode('trx_quote', 'plumbing_parts_sc_quote');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_quote_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_quote_reg_shortcodes');
	function plumbing_parts_sc_quote_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_quote", array(
			"title" => esc_html__("Quote", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Quote text", 'plumbing-parts') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"cite" => array(
					"title" => esc_html__("Quote cite", 'plumbing-parts'),
					"desc" => wp_kses_data( __("URL for quote cite", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
                "style" => array(
                    "title" => esc_html__("Style", 'plumbing-parts'),
                    "desc" => wp_kses_data( __("Quote style", 'plumbing-parts') ),
                    "value" => "1",
                    "type" => "checklist",
                    "options" => plumbing_parts_get_list_styles(1, 2)
                ),
				"title" => array(
					"title" => esc_html__("Title (author)", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Quote title (author name)", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Quote content", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Quote content", 'plumbing-parts') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"width" => plumbing_parts_shortcodes_width(),
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
if ( !function_exists( 'plumbing_parts_sc_quote_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_quote_reg_shortcodes_vc');
	function plumbing_parts_sc_quote_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_quote",
			"name" => esc_html__("Quote", 'plumbing-parts'),
			"description" => wp_kses_data( __("Quote text", 'plumbing-parts') ),
			"category" => esc_html__('Content', 'plumbing-parts'),
			'icon' => 'icon_trx_quote',
			"class" => "trx_sc_single trx_sc_quote",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "cite",
					"heading" => esc_html__("Quote cite", 'plumbing-parts'),
					"description" => wp_kses_data( __("URL for the quote cite link", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
                array(
                    "param_name" => "style",
                    "heading" => esc_html__("Style", 'plumbing-parts'),
                    "description" => wp_kses_data( __("Quote style", 'plumbing-parts') ),
                    "admin_label" => true,
                    "class" => "",
                    "value" => array_flip(plumbing_parts_get_list_styles(1, 2)),
                    "type" => "dropdown"
                ),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title (author)", 'plumbing-parts'),
					"description" => wp_kses_data( __("Quote title (author name)", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Quote content", 'plumbing-parts'),
					"description" => wp_kses_data( __("Quote content", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				plumbing_parts_get_vc_param('id'),
				plumbing_parts_get_vc_param('class'),
				plumbing_parts_get_vc_param('animation'),
				plumbing_parts_get_vc_param('css'),
				plumbing_parts_vc_width(),
				plumbing_parts_get_vc_param('margin_top'),
				plumbing_parts_get_vc_param('margin_bottom'),
				plumbing_parts_get_vc_param('margin_left'),
				plumbing_parts_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Quote extends PLUMBING_PARTS_VC_ShortCodeSingle {}
	}
}
?>