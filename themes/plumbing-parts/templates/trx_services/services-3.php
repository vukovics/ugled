<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'plumbing_parts_template_services_3_theme_setup' ) ) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_template_services_3_theme_setup', 1 );
	function plumbing_parts_template_services_3_theme_setup() {
		plumbing_parts_add_template(array(
			'layout' => 'services-3',
			'template' => 'services-3',
			'mode'   => 'services',
			'need_columns' => true,
			'title'  => esc_html__('Services /Style 3/', 'plumbing-parts'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'plumbing-parts'),
			'w'		 => 370,
			'h'		 => 370
		));
	}
}

// Template output
if ( !function_exists( 'plumbing_parts_template_services_3_output' ) ) {
	function plumbing_parts_template_services_3_output($post_options, $post_data) {
		$show_title = !empty($post_data['post_title']);
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		if (plumbing_parts_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><div class="sc_services_item_wrap"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php echo !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?>
				class="sc_services_item sc_services_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php echo (!empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
					. (!plumbing_parts_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(plumbing_parts_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>>
				<?php 
				if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
					?><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php
				}
				if ($post_data['post_icon'] && $post_options['tag_type']=='icons') {
					plumbing_parts_show_layout(plumbing_parts_do_shortcode('[trx_icon icon="'.esc_attr($post_data['post_icon']).'" shape="round"]'));
				} else {
					?>
					<div class="sc_services_item_featured post_featured">
						<?php
						plumbing_parts_template_set_args('post-featured', array(
							'post_options' => $post_options,
							'post_data' => $post_data
						));
						get_template_part(plumbing_parts_get_file_slug('templates/_parts/post-featured.php'));
						?>
					</div>
					<?php
				}
				if ($show_title) {
					?><h4 class="sc_services_item_title"><?php plumbing_parts_show_layout($post_data['post_title']); ?></h4><?php
				}
				if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
					?></a><?php
				}
				?>
			</div>
		<?php
		if (plumbing_parts_param_is_on($post_options['slider'])) {
			?></div></div><?php
		} else if ($columns > 1) {
			?></div><?php
		}
	}
}
?>