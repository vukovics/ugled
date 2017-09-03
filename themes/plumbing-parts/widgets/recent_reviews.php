<?php
/**
 * Theme Widget: Recent reviews
 */

// Theme init
if (!function_exists('plumbing_parts_widget_recent_reviews_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_widget_recent_reviews_theme_setup', 1 );
	function plumbing_parts_widget_recent_reviews_theme_setup() {

		// Register shortcodes in the shortcodes list
		//add_action('plumbing_parts_action_shortcodes_list',	'plumbing_parts_widget_recent_reviews_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_widget_recent_reviews_reg_shortcodes_vc');
	}
}

// Load widget
if (!function_exists('plumbing_parts_widget_recent_reviews_load')) {
	add_action( 'widgets_init', 'plumbing_parts_widget_recent_reviews_load' );
	function plumbing_parts_widget_recent_reviews_load() {
		register_widget('plumbing_parts_widget_recent_reviews');
	}
}

// Widget Class
class plumbing_parts_widget_recent_reviews extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_reviews', 'description' => esc_html__('Recent reviews', 'plumbing-parts'));
		parent::__construct( 'plumbing_parts_widget_recent_reviews', esc_html__('Plumbing Parts - Recent reviews', 'plumbing-parts'), $widget_ops );

		// Add thumb sizes into list
		plumbing_parts_add_thumb_sizes( array( 'layout' => 'widgets', 'w' => 75, 'h' => 75, 'title'=>esc_html__('Widgets', 'plumbing-parts') ) );
	}

	// Show widget
	function widget($args, $instance) {
		extract($args);

		global $post;

		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '');
		
		$post_type = isset($instance['post_type']) ? $instance['post_type'] : 'post';
		$category = isset($instance['category']) ? (int) $instance['category'] : 0;
		$taxonomy = plumbing_parts_get_taxonomy_categories_by_post_type($post_type);

		$number = isset($instance['number']) ? (int) $instance['number'] : '';

		$show_date = isset($instance['show_date']) ? (int) $instance['show_date'] : 0;
		$show_image = isset($instance['show_image']) ? (int) $instance['show_image'] : 0;
		$show_author = isset($instance['show_author']) ? (int) $instance['show_author'] : 0;
		$show_counters = isset($instance['show_counters']) ? (int) $instance['show_counters'] : 0;
		$show_counters = $show_counters==2 ? 'stars' : ($show_counters==1 ? 'rating' : '');

		$output = '';

		$post_rating = 'plumbing_parts_reviews_avg'.(plumbing_parts_get_theme_option('reviews_first')=='author' ? '' : '2');
		
		$args = array(
			'post_type' => $post_type,
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'post_password' => '',
			'posts_per_page' => $number,
			'ignore_sticky_posts' => true,
			'order' => 'DESC',
			'orderby' => 'date',
			'meta_query' => array(
				array(
					'key' => $post_rating,
					'value' => 0,
					'compare' => '>',
					'type' => 'NUMERIC'
				)
			)
		);
		if ($category > 0) {
			if ($taxonomy=='category')
				$args['cat'] = $category;
			else {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $taxonomy,
						'field' => 'id',
						'terms' => $category
					)
				);
			}
		}
		$ex = plumbing_parts_get_theme_option('exclude_cats');
		if (!empty($ex)) {
			$args['category__not_in'] = explode(',', $ex);
		}
		
		$q = new WP_Query($args); 
			
		if ($q->have_posts()) {
			$post_number = 0;
			while ($q->have_posts()) { $q->the_post();
				$post_number++;
				plumbing_parts_template_set_args('widgets-posts', array(
					'post_number' => $post_number,
					'post_rating' => $post_rating,
					'show_date' => $show_date,
					'show_image' => $show_image,
					'show_author' => $show_author,
					'show_counters' => $show_counters
				));
				get_template_part(plumbing_parts_get_file_slug('templates/_parts/widgets-posts.php'));
				$output .= plumbing_parts_storage_get('widgets_posts_output');
				if ($post_number >= $number) break;
			}
		}

		wp_reset_postdata();

		if (!empty($output)) {
	
			// Before widget (defined by themes)
			plumbing_parts_show_layout($before_widget);
			
			// Display the widget title if one was input (before and after defined by themes)
			if ($title) plumbing_parts_show_layout($title, $before_title, $after_title);
	
			echo '
				<div class="recent_reviews">
					' . ($output) . '
				</div>
			';
			
			// After widget (defined by themes)
			plumbing_parts_show_layout($after_widget);
		}
	}

	// Update the widget settings.
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = (int) $new_instance['show_date'];
		$instance['show_image'] = (int) $new_instance['show_image'];
		$instance['show_author'] = (int) $new_instance['show_author'];
		$instance['show_counters'] = (int) $new_instance['show_counters'];
		$instance['category'] = (int) $new_instance['category'];
		$instance['post_type'] = strip_tags( $new_instance['post_type'] );
		return $instance;
	}

	// Displays the widget settings controls on the widget panel.
	function form($instance) {

		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'number' => '4',
			'show_date' => '1',
			'show_image' => '1',
			'show_author' => '1',
			'show_counters' => '1',
			'category' => '0',
			'post_type' => 'post'
			)
		);
		$title = $instance['title'];
		$number = (int) $instance['number'];
		$show_date = (int) $instance['show_date'];
		$show_image = (int) $instance['show_image'];
		$show_author = (int) $instance['show_author'];
		$show_counters = (int) $instance['show_counters'];
		$post_type = $instance['post_type'];
		$category = (int) $instance['category'];

		$posts_types = plumbing_parts_get_list_posts_types(false);
		$categories = plumbing_parts_get_list_terms(false, plumbing_parts_get_taxonomy_categories_by_post_type($post_type));
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Widget title:', 'plumbing-parts'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('post_type')); ?>"><?php esc_html_e('Post type:', 'plumbing-parts'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('post_type')); ?>" name="<?php echo esc_attr($this->get_field_name('post_type')); ?>" class="widgets_param_fullwidth widgets_param_post_type_selector">
			<?php
				if (is_array($posts_types) && count($posts_types) > 0) {
					foreach ($posts_types as $type => $type_name) {
						echo '<option value="'.esc_attr($type).'"'.($post_type==$type ? ' selected="selected"' : '').'>'.esc_html($type_name).'</option>';
					}
				}
			?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php esc_html_e('Category:', 'plumbing-parts'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('category')); ?>" name="<?php echo esc_attr($this->get_field_name('category')); ?>" class="widgets_param_fullwidth">
				<option value="0"><?php esc_html_e('-- Any category --', 'plumbing-parts'); ?></option> 
			<?php
				if (is_array($categories) && count($categories) > 0) {
					foreach ($categories as $cat_id => $cat_name) {
						echo '<option value="'.esc_attr($cat_id).'"'.($category==$cat_id ? ' selected="selected"' : '').'>'.esc_html($cat_name).'</option>';
					}
				}
			?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number posts to show:', 'plumbing-parts'); ?></label>
			<input type="text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" value="<?php echo esc_attr($number); ?>" class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>_1"><?php esc_html_e('Show post image:', 'plumbing-parts'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_image')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_image')); ?>" value="1" <?php echo (1==$show_image ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>_1"><?php esc_html_e('Show', 'plumbing-parts'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_image')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_image')); ?>" value="0" <?php echo (0==$show_image ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>_0"><?php esc_html_e('Hide', 'plumbing-parts'); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>_1"><?php esc_html_e('Show post author:', 'plumbing-parts'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_author')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_author')); ?>" value="1" <?php echo (1==$show_author ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>_1"><?php esc_html_e('Show', 'plumbing-parts'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_author')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_author')); ?>" value="0" <?php echo (0==$show_author ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>_0"><?php esc_html_e('Hide', 'plumbing-parts'); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>_1"><?php esc_html_e('Show post date:', 'plumbing-parts'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_date')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>" value="1" <?php echo (1==$show_date ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>_1"><?php esc_html_e('Show', 'plumbing-parts'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_date')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>" value="0" <?php echo (0==$show_date ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>_0"><?php esc_html_e('Hide', 'plumbing-parts'); ?></label>
		</p>


		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_1"><?php esc_html_e('Show post counters:', 'plumbing-parts'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_2" name="<?php echo esc_attr($this->get_field_name('show_counters')); ?>" value="2" <?php echo (2==$show_counters ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_2"><?php esc_html_e('As stars', 'plumbing-parts'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_counters')); ?>" value="1" <?php echo (1==$show_counters ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_1"><?php esc_html_e('As icon', 'plumbing-parts'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_counters')); ?>" value="0" <?php echo (0==$show_counters ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_0"><?php esc_html_e('Hide', 'plumbing-parts'); ?></label>
		</p>

	<?php
	}
}



// trx_widget_recent_reviews
//-------------------------------------------------------------
/*
[trx_widget_recent_reviews id="unique_id" title="Widget title" number="4" show_date="0|1" show_image="0|1" show_author="0|1" show_counters="0|1|2"]
*/
if ( !function_exists( 'plumbing_parts_sc_widget_recent_reviews' ) ) {
	function plumbing_parts_sc_widget_recent_reviews($atts, $content=null){	
		$atts = plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"number" => 4,
			"show_date" => 1,
			"show_image" => 1,
			"show_author" => 1,
			"show_counters" => 2,
			'category' 		=> '',
			'cat' 			=> 0,
			'post_type'		=> 'post',
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts));
		if ($atts['post_type']=='') $atts['post_type'] = 'post';
		if ($atts['cat']!='' && $atts['category']=='') $atts['category'] = $atts['cat'];
		if ($atts['show_date']=='') $atts['show_date'] = 0;
		if ($atts['show_image']=='') $atts['show_image'] = 0;
		if ($atts['show_author']=='') $atts['show_author'] = 0;
		if ($atts['show_counters']=='') $atts['show_counters'] = 0;
		extract($atts);
		$type = 'plumbing_parts_widget_recent_reviews';
		$output = '';
		global $wp_widget_factory;
		if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
							. ' class="widget_area sc_widget_recent_reviews' 
								. (plumbing_parts_exists_visual_composer() ? ' vc_widget_recent_posts wpb_content_element' : '') 
								. (!empty($class) ? ' ' . esc_attr($class) : '') 
						. '">';
			ob_start();
			the_widget( $type, $atts, plumbing_parts_prepare_widgets_args(plumbing_parts_storage_get('widgets_args'), $id ? $id.'_widget' : 'widget_recent_reviews', 'widget_recent_reviews') );
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
		}
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_widget_recent_reviews', $atts, $content);
	}
	plumbing_parts_require_shortcode("trx_widget_recent_reviews", "plumbing_parts_sc_widget_recent_reviews");
}


// Add [trx_widget_recent_reviews] in the VC shortcodes list
if (!function_exists('plumbing_parts_widget_recent_reviews_reg_shortcodes_vc')) {
	function plumbing_parts_widget_recent_reviews_reg_shortcodes_vc() {
		
		$posts_types = plumbing_parts_get_list_posts_types(false);
		$categories = plumbing_parts_get_list_terms(false, plumbing_parts_get_taxonomy_categories_by_post_type('post'));

		vc_map( array(
				"base" => "trx_widget_recent_reviews",
				"name" => esc_html__("Widget Recent Reviews", 'plumbing-parts'),
				"description" => wp_kses_data( __("Insert recent reviews list with thumbs and post's meta", 'plumbing-parts') ),
				"category" => esc_html__('Content', 'plumbing-parts'),
				"icon" => 'icon_trx_widget_recent_reviews',
				"class" => "trx_widget_recent_reviews",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Widget title", 'plumbing-parts'),
						"description" => wp_kses_data( __("Title of the widget", 'plumbing-parts') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "number",
						"heading" => esc_html__("Number posts to show", 'plumbing-parts'),
						"description" => wp_kses_data( __("How many posts display in widget?", 'plumbing-parts') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => esc_html__("Post type", 'plumbing-parts'),
						"description" => wp_kses_data( __("Select post type to show", 'plumbing-parts') ),
						"class" => "",
						"std" => "post",
						"value" => array_flip($posts_types),
						"type" => "dropdown"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Parent category", 'plumbing-parts'),
						"description" => wp_kses_data( __("Select parent category. If empty - show posts from any category", 'plumbing-parts') ),
						"class" => "",
						"value" => array_flip(plumbing_parts_array_merge(array(0 => esc_html__('- Select category -', 'plumbing-parts')), $categories)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "show_image",
						"heading" => esc_html__("Show post's image", 'plumbing-parts'),
						"description" => wp_kses_data( __("Do you want display post's featured image?", 'plumbing-parts') ),
						"group" => esc_html__('Details', 'plumbing-parts'),
						"class" => "",
						"std" => 1,
						"value" => array("Show image" => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "show_author",
						"heading" => esc_html__("Show post's author", 'plumbing-parts'),
						"description" => wp_kses_data( __("Do you want display post's author?", 'plumbing-parts') ),
						"group" => esc_html__('Details', 'plumbing-parts'),
						"class" => "",
						"std" => 1,
						"value" => array("Show author" => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "show_date",
						"heading" => esc_html__("Show post's date", 'plumbing-parts'),
						"description" => wp_kses_data( __("Do you want display post's publish date?", 'plumbing-parts') ),
						"group" => esc_html__('Details', 'plumbing-parts'),
						"class" => "",
						"std" => 1,
						"value" => array("Show date" => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "show_counters",
						"heading" => esc_html__("Show post's counters", 'plumbing-parts'),
						"description" => wp_kses_data( __("Do you want display post's counters?", 'plumbing-parts') ),
						"admin_label" => true,
						"group" => esc_html__('Details', 'plumbing-parts'),
						"class" => "",
						"value" => array_flip(array(
							'2' => esc_html__('As stars', 'plumbing-parts'),
							'1' => esc_html__('As text', 'plumbing-parts'),
							'0' => esc_html__('Hide', 'plumbing-parts')
						)),
						"type" => "dropdown"
					),
					plumbing_parts_get_vc_param('id'),
					plumbing_parts_get_vc_param('class'),
					plumbing_parts_get_vc_param('css')
				)
			) );
			
		class WPBakeryShortCode_Trx_Widget_Recent_Reviews extends WPBakeryShortCode {}

	}
}
?>