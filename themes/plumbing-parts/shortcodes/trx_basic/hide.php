<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_hide_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_hide_theme_setup' );
	function plumbing_parts_sc_hide_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('plumbing_parts_sc_hide')) {	
	function plumbing_parts_sc_hide($atts, $content=null){	
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		$output = $selector == '' ? '' : 
			'<script type="text/javascript">
				jQuery(document).ready(function() {
					'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
					'.($delay>0 ? '},'.($delay).');' : '').'
				});
			</script>';
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	plumbing_parts_require_shortcode('trx_hide', 'plumbing_parts_sc_hide');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_hide_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_hide_reg_shortcodes');
	function plumbing_parts_sc_hide_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_hide", array(
			"title" => esc_html__("Hide/Show any block", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Hide or Show any block with desired CSS-selector", 'plumbing-parts') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Any block's CSS-selector", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", 'plumbing-parts'),
					"desc" => wp_kses_data( __("New state for the block: hide or show", 'plumbing-parts') ),
					"value" => "yes",
					"size" => "small",
					"options" => plumbing_parts_get_sc_param('yes_no'),
					"type" => "switch"
				)
			)
		));
	}
}
?>