<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_tooltip_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_tooltip_theme_setup' );
	function plumbing_parts_sc_tooltip_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_tooltip_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_tooltip id="unique_id" title="Tooltip text here"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/tooltip]
*/

if (!function_exists('plumbing_parts_sc_tooltip')) {	
	function plumbing_parts_sc_tooltip($atts, $content=null){	
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_tooltip_parent'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. do_shortcode($content)
						. '<span class="sc_tooltip">' . ($title) . '</span>'
					. '</span>';
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_tooltip', $atts, $content);
	}
	plumbing_parts_require_shortcode('trx_tooltip', 'plumbing_parts_sc_tooltip');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_tooltip_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_tooltip_reg_shortcodes');
	function plumbing_parts_sc_tooltip_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_tooltip", array(
			"title" => esc_html__("Tooltip", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Create tooltip for selected text", 'plumbing-parts') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Tooltip title (required)", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Tipped content", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Highlighted content with tooltip", 'plumbing-parts') ),
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
?>