<?php
/**
 * Single post
 */
get_header(); 

$single_style = plumbing_parts_storage_get('single_style');
if (empty($single_style)) $single_style = plumbing_parts_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	plumbing_parts_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !plumbing_parts_param_is_off(plumbing_parts_get_custom_option('show_sidebar_main')),
			'content' => plumbing_parts_get_template_property($single_style, 'need_content'),
			'terms_list' => plumbing_parts_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>