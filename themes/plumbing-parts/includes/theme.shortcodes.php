<?php
if (!function_exists('plumbing_parts_theme_shortcodes_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_theme_shortcodes_setup', 1 );
	function plumbing_parts_theme_shortcodes_setup() {
		add_filter('plumbing_parts_filter_googlemap_styles', 'plumbing_parts_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'plumbing_parts_theme_shortcodes_googlemap_styles' ) ) {
	function plumbing_parts_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'plumbing-parts');
		$list['greyscale']	= esc_html__('Greyscale', 'plumbing-parts');
		$list['inverse']	= esc_html__('Inverse', 'plumbing-parts');
		return $list;
	}
}
?>