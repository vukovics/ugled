<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move plumbing_parts_set_post_views to the javascript - counter will work under cache system
	if (plumbing_parts_get_custom_option('use_ajax_views_counter')=='no') {
		plumbing_parts_set_post_views(get_the_ID());
	}

	plumbing_parts_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !plumbing_parts_param_is_off(plumbing_parts_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>