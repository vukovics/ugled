<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'plumbing_parts_template_no_articles_theme_setup' ) ) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_template_no_articles_theme_setup', 1 );
	function plumbing_parts_template_no_articles_theme_setup() {
		plumbing_parts_add_template(array(
			'layout' => 'no-articles',
			'mode'   => 'internal',
			'title'  => esc_html__('No articles found', 'plumbing-parts')
		));
	}
}

// Template output
if ( !function_exists( 'plumbing_parts_template_no_articles_output' ) ) {
	function plumbing_parts_template_no_articles_output($post_options, $post_data) {
		?>
		<article class="post_item">
			<div class="post_content">
				<h2 class="post_title"><?php esc_html_e('No posts found', 'plumbing-parts'); ?></h2>
				<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria.', 'plumbing-parts' ); ?></p>
				<p><?php echo wp_kses_data( sprintf(__('Go back, or return to <a href="%s">%s</a> home page to choose a new page.', 'plumbing-parts'), esc_url(home_url('/')), get_bloginfo()) ); ?>
				<br><?php esc_html_e('Please report any broken links to our team.', 'plumbing-parts'); ?></p>
				<?php plumbing_parts_show_layout(plumbing_parts_sc_search(array('state'=>"fixed"))); ?>
			</div>	<!-- /.post_content -->
		</article>	<!-- /.post_item -->
		<?php
	}
}
?>