<?php
/**
 * Plumbing Parts Framework: return lists
 *
 * @package plumbing_parts
 * @since plumbing_parts 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'plumbing_parts_get_list_styles' ) ) {
	function plumbing_parts_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'plumbing-parts'), $i);
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'plumbing_parts_get_list_margins' ) ) {
	function plumbing_parts_get_list_margins($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'plumbing-parts'),
				'tiny'		=> esc_html__('Tiny',		'plumbing-parts'),
				'small'		=> esc_html__('Small',		'plumbing-parts'),
				'medium'	=> esc_html__('Medium',		'plumbing-parts'),
				'large'		=> esc_html__('Large',		'plumbing-parts'),
				'huge'		=> esc_html__('Huge',		'plumbing-parts'),
				'very_huge'		=> esc_html__('Very Huge',		'plumbing-parts'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'plumbing-parts'),
				'small-'	=> esc_html__('Small (negative)',	'plumbing-parts'),
				'medium-'	=> esc_html__('Medium (negative)',	'plumbing-parts'),
				'large-'	=> esc_html__('Large (negative)',	'plumbing-parts'),
				'huge-'		=> esc_html__('Huge (negative)',	'plumbing-parts')
				);
			$list = apply_filters('plumbing_parts_filter_list_margins', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'plumbing_parts_get_list_animations' ) ) {
	function plumbing_parts_get_list_animations($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'plumbing-parts'),
				'bounced'		=> esc_html__('Bounced',		'plumbing-parts'),
				'flash'			=> esc_html__('Flash',		'plumbing-parts'),
				'flip'			=> esc_html__('Flip',		'plumbing-parts'),
				'pulse'			=> esc_html__('Pulse',		'plumbing-parts'),
				'rubberBand'	=> esc_html__('Rubber Band',	'plumbing-parts'),
				'shake'			=> esc_html__('Shake',		'plumbing-parts'),
				'swing'			=> esc_html__('Swing',		'plumbing-parts'),
				'tada'			=> esc_html__('Tada',		'plumbing-parts'),
				'wobble'		=> esc_html__('Wobble',		'plumbing-parts')
				);
			$list = apply_filters('plumbing_parts_filter_list_animations', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'plumbing_parts_get_list_line_styles' ) ) {
	function plumbing_parts_get_list_line_styles($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'plumbing-parts'),
				'dashed'=> esc_html__('Dashed', 'plumbing-parts'),
				'dotted'=> esc_html__('Dotted', 'plumbing-parts'),
				'double'=> esc_html__('Double', 'plumbing-parts'),
				'image'	=> esc_html__('Image', 'plumbing-parts')
				);
			$list = apply_filters('plumbing_parts_filter_list_line_styles', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'plumbing_parts_get_list_animations_in' ) ) {
	function plumbing_parts_get_list_animations_in($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'plumbing-parts'),
				'bounceIn'			=> esc_html__('Bounce In',			'plumbing-parts'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'plumbing-parts'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'plumbing-parts'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'plumbing-parts'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'plumbing-parts'),
				'fadeIn'			=> esc_html__('Fade In',			'plumbing-parts'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'plumbing-parts'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'plumbing-parts'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'plumbing-parts'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'plumbing-parts'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'plumbing-parts'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'plumbing-parts'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'plumbing-parts'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'plumbing-parts'),
				'flipInX'			=> esc_html__('Flip In X',			'plumbing-parts'),
				'flipInY'			=> esc_html__('Flip In Y',			'plumbing-parts'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'plumbing-parts'),
				'rotateIn'			=> esc_html__('Rotate In',			'plumbing-parts'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','plumbing-parts'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'plumbing-parts'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'plumbing-parts'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','plumbing-parts'),
				'rollIn'			=> esc_html__('Roll In',			'plumbing-parts'),
				'slideInUp'			=> esc_html__('Slide In Up',		'plumbing-parts'),
				'slideInDown'		=> esc_html__('Slide In Down',		'plumbing-parts'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'plumbing-parts'),
				'slideInRight'		=> esc_html__('Slide In Right',		'plumbing-parts'),
				'zoomIn'			=> esc_html__('Zoom In',			'plumbing-parts'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'plumbing-parts'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'plumbing-parts'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'plumbing-parts'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'plumbing-parts')
				);
			$list = apply_filters('plumbing_parts_filter_list_animations_in', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'plumbing_parts_get_list_animations_out' ) ) {
	function plumbing_parts_get_list_animations_out($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',	'plumbing-parts'),
				'bounceOut'			=> esc_html__('Bounce Out',			'plumbing-parts'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'plumbing-parts'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',		'plumbing-parts'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',		'plumbing-parts'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'plumbing-parts'),
				'fadeOut'			=> esc_html__('Fade Out',			'plumbing-parts'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',			'plumbing-parts'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'plumbing-parts'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'plumbing-parts'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'plumbing-parts'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',		'plumbing-parts'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'plumbing-parts'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'plumbing-parts'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'plumbing-parts'),
				'flipOutX'			=> esc_html__('Flip Out X',			'plumbing-parts'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'plumbing-parts'),
				'hinge'				=> esc_html__('Hinge Out',			'plumbing-parts'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',		'plumbing-parts'),
				'rotateOut'			=> esc_html__('Rotate Out',			'plumbing-parts'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left',	'plumbing-parts'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right',		'plumbing-parts'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',		'plumbing-parts'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right',	'plumbing-parts'),
				'rollOut'			=> esc_html__('Roll Out',		'plumbing-parts'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'plumbing-parts'),
				'slideOutDown'		=> esc_html__('Slide Out Down',	'plumbing-parts'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',	'plumbing-parts'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'plumbing-parts'),
				'zoomOut'			=> esc_html__('Zoom Out',			'plumbing-parts'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'plumbing-parts'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',	'plumbing-parts'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',	'plumbing-parts'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',	'plumbing-parts')
				);
			$list = apply_filters('plumbing_parts_filter_list_animations_out', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('plumbing_parts_get_animation_classes')) {
	function plumbing_parts_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return plumbing_parts_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!plumbing_parts_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'plumbing_parts_get_list_categories' ) ) {
	function plumbing_parts_get_list_categories($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'plumbing_parts_get_list_terms' ) ) {
	function plumbing_parts_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = plumbing_parts_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = plumbing_parts_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'plumbing_parts_get_list_posts_types' ) ) {
	function plumbing_parts_get_list_posts_types($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_posts_types'))=='') {
			/* 
			// This way to return all registered post types
			$types = get_post_types();
			if (in_array('post', $types)) $list['post'] = esc_html__('Post', 'plumbing-parts');
			if (is_array($types) && count($types) > 0) {
				foreach ($types as $t) {
					if ($t == 'post') continue;
					$list[$t] = plumbing_parts_strtoproper($t);
				}
			}
			*/
			// Return only theme inheritance supported post types
			$list = apply_filters('plumbing_parts_filter_list_post_types', array());
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'plumbing_parts_get_list_posts' ) ) {
	function plumbing_parts_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = plumbing_parts_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'plumbing-parts');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set($hash, $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'plumbing_parts_get_list_pages' ) ) {
	function plumbing_parts_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return plumbing_parts_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'plumbing_parts_get_list_users' ) ) {
	function plumbing_parts_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = plumbing_parts_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'plumbing-parts');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_users', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'plumbing_parts_get_list_sliders' ) ) {
	function plumbing_parts_get_list_sliders($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'plumbing-parts')
			);
			$list = apply_filters('plumbing_parts_filter_list_sliders', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'plumbing_parts_get_list_slider_controls' ) ) {
	function plumbing_parts_get_list_slider_controls($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'plumbing-parts'),
				'side'		=> esc_html__('Side', 'plumbing-parts'),
				'bottom'	=> esc_html__('Bottom', 'plumbing-parts'),
				'pagination'=> esc_html__('Pagination', 'plumbing-parts')
				);
			$list = apply_filters('plumbing_parts_filter_list_slider_controls', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'plumbing_parts_get_slider_controls_classes' ) ) {
	function plumbing_parts_get_slider_controls_classes($controls) {
		if (plumbing_parts_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'plumbing_parts_get_list_popup_engines' ) ) {
	function plumbing_parts_get_list_popup_engines($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'plumbing-parts'),
				"magnific"	=> esc_html__("Magnific popup", 'plumbing-parts')
				);
			$list = apply_filters('plumbing_parts_filter_list_popup_engines', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_menus' ) ) {
	function plumbing_parts_get_list_menus($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'plumbing-parts');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'plumbing_parts_get_list_sidebars' ) ) {
	function plumbing_parts_get_list_sidebars($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_sidebars'))=='') {
			if (($list = plumbing_parts_storage_get('registered_sidebars'))=='') $list = array();
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'plumbing_parts_get_list_sidebars_positions' ) ) {
	function plumbing_parts_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'plumbing-parts'),
				'left'  => esc_html__('Left',  'plumbing-parts'),
				'right' => esc_html__('Right', 'plumbing-parts')
				);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'plumbing_parts_get_sidebar_class' ) ) {
	function plumbing_parts_get_sidebar_class() {
		$sb_main = plumbing_parts_get_custom_option('show_sidebar_main');
		$sb_outer = plumbing_parts_get_custom_option('show_sidebar_outer');
		return (plumbing_parts_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (plumbing_parts_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_body_styles' ) ) {
	function plumbing_parts_get_list_body_styles($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_body_styles'))=='') {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'plumbing-parts'),
				'wide'	=> esc_html__('Wide',		'plumbing-parts')
				);
			if (plumbing_parts_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'plumbing-parts');
				$list['fullscreen']	= esc_html__('Fullscreen',	'plumbing-parts');
			}
			$list = apply_filters('plumbing_parts_filter_list_body_styles', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_skins' ) ) {
	function plumbing_parts_get_list_skins($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_skins'))=='') {
			$list = plumbing_parts_get_list_folders("skins");
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_skins', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'plumbing_parts_get_list_themes' ) ) {
	function plumbing_parts_get_list_themes($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_themes'))=='') {
			$list = plumbing_parts_get_list_files("css/themes");
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_themes', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_templates' ) ) {
	function plumbing_parts_get_list_templates($mode='') {
		if (($list = plumbing_parts_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = plumbing_parts_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: plumbing_parts_strtoproper($v['layout'])
										);
				}
			}
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_templates_blog' ) ) {
	function plumbing_parts_get_list_templates_blog($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_templates_blog'))=='') {
			$list = plumbing_parts_get_list_templates('blog');
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_templates_blogger' ) ) {
	function plumbing_parts_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_templates_blogger'))=='') {
			$list = plumbing_parts_array_merge(plumbing_parts_get_list_templates('blogger'), plumbing_parts_get_list_templates('blog'));
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_templates_single' ) ) {
	function plumbing_parts_get_list_templates_single($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_templates_single'))=='') {
			$list = plumbing_parts_get_list_templates('single');
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_templates_header' ) ) {
	function plumbing_parts_get_list_templates_header($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_templates_header'))=='') {
			$list = plumbing_parts_get_list_templates('header');
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_templates_forms' ) ) {
	function plumbing_parts_get_list_templates_forms($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_templates_forms'))=='') {
			$list = plumbing_parts_get_list_templates('forms');
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_article_styles' ) ) {
	function plumbing_parts_get_list_article_styles($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'plumbing-parts'),
				"stretch" => esc_html__('Stretch', 'plumbing-parts')
				);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_post_formats_filters' ) ) {
	function plumbing_parts_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'plumbing-parts'),
				"thumbs"  => esc_html__('With thumbs', 'plumbing-parts'),
				"reviews" => esc_html__('With reviews', 'plumbing-parts'),
				"video"   => esc_html__('With videos', 'plumbing-parts'),
				"audio"   => esc_html__('With audios', 'plumbing-parts'),
				"gallery" => esc_html__('With galleries', 'plumbing-parts')
				);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_portfolio_filters' ) ) {
	function plumbing_parts_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'plumbing-parts'),
				"tags"		=> esc_html__('Tags', 'plumbing-parts'),
				"categories"=> esc_html__('Categories', 'plumbing-parts')
				);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_hovers' ) ) {
	function plumbing_parts_get_list_hovers($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'plumbing-parts');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'plumbing-parts');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'plumbing-parts');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'plumbing-parts');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'plumbing-parts');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'plumbing-parts');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'plumbing-parts');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'plumbing-parts');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'plumbing-parts');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'plumbing-parts');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'plumbing-parts');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'plumbing-parts');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'plumbing-parts');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'plumbing-parts');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'plumbing-parts');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'plumbing-parts');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'plumbing-parts');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'plumbing-parts');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'plumbing-parts');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'plumbing-parts');
			$list['square effect1']  = esc_html__('Square Effect 1',  'plumbing-parts');
			$list['square effect2']  = esc_html__('Square Effect 2',  'plumbing-parts');
			$list['square effect3']  = esc_html__('Square Effect 3',  'plumbing-parts');
	//		$list['square effect4']  = esc_html__('Square Effect 4',  'plumbing-parts');
			$list['square effect5']  = esc_html__('Square Effect 5',  'plumbing-parts');
			$list['square effect6']  = esc_html__('Square Effect 6',  'plumbing-parts');
			$list['square effect7']  = esc_html__('Square Effect 7',  'plumbing-parts');
			$list['square effect8']  = esc_html__('Square Effect 8',  'plumbing-parts');
			$list['square effect9']  = esc_html__('Square Effect 9',  'plumbing-parts');
			$list['square effect10'] = esc_html__('Square Effect 10',  'plumbing-parts');
			$list['square effect11'] = esc_html__('Square Effect 11',  'plumbing-parts');
			$list['square effect12'] = esc_html__('Square Effect 12',  'plumbing-parts');
			$list['square effect13'] = esc_html__('Square Effect 13',  'plumbing-parts');
			$list['square effect14'] = esc_html__('Square Effect 14',  'plumbing-parts');
			$list['square effect15'] = esc_html__('Square Effect 15',  'plumbing-parts');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'plumbing-parts');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'plumbing-parts');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'plumbing-parts');
			$list['square effect_more']  = esc_html__('Square Effect More',  'plumbing-parts');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'plumbing-parts');
			$list = apply_filters('plumbing_parts_filter_portfolio_hovers', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'plumbing_parts_get_list_blog_counters' ) ) {
	function plumbing_parts_get_list_blog_counters($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'plumbing-parts'),
				'likes'		=> esc_html__('Likes', 'plumbing-parts'),
				'rating'	=> esc_html__('Rating', 'plumbing-parts'),
				'comments'	=> esc_html__('Comments', 'plumbing-parts')
				);
			$list = apply_filters('plumbing_parts_filter_list_blog_counters', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_alter_sizes' ) ) {
	function plumbing_parts_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'plumbing-parts'),
					'1_2' => esc_html__('1x2', 'plumbing-parts'),
					'2_1' => esc_html__('2x1', 'plumbing-parts'),
					'2_2' => esc_html__('2x2', 'plumbing-parts'),
					'1_3' => esc_html__('1x3', 'plumbing-parts'),
					'2_3' => esc_html__('2x3', 'plumbing-parts'),
					'3_1' => esc_html__('3x1', 'plumbing-parts'),
					'3_2' => esc_html__('3x2', 'plumbing-parts'),
					'3_3' => esc_html__('3x3', 'plumbing-parts')
					);
			$list = apply_filters('plumbing_parts_filter_portfolio_alter_sizes', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_hovers_directions' ) ) {
	function plumbing_parts_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'plumbing-parts'),
				'right_to_left' => esc_html__('Right to Left',  'plumbing-parts'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'plumbing-parts'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'plumbing-parts'),
				'scale_up'      => esc_html__('Scale Up',  'plumbing-parts'),
				'scale_down'    => esc_html__('Scale Down',  'plumbing-parts'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'plumbing-parts'),
				'from_left_and_right' => esc_html__('From Left and Right',  'plumbing-parts'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'plumbing-parts')
			);
			$list = apply_filters('plumbing_parts_filter_portfolio_hovers_directions', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'plumbing_parts_get_list_label_positions' ) ) {
	function plumbing_parts_get_list_label_positions($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'plumbing-parts'),
				'bottom'	=> esc_html__('Bottom',		'plumbing-parts'),
				'left'		=> esc_html__('Left',		'plumbing-parts'),
				'over'		=> esc_html__('Over',		'plumbing-parts')
			);
			$list = apply_filters('plumbing_parts_filter_label_positions', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'plumbing_parts_get_list_bg_image_positions' ) ) {
	function plumbing_parts_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'plumbing-parts'),
				'center top'   => esc_html__("Center Top", 'plumbing-parts'),
				'right top'    => esc_html__("Right Top", 'plumbing-parts'),
				'left center'  => esc_html__("Left Center", 'plumbing-parts'),
				'center center'=> esc_html__("Center Center", 'plumbing-parts'),
				'right center' => esc_html__("Right Center", 'plumbing-parts'),
				'left bottom'  => esc_html__("Left Bottom", 'plumbing-parts'),
				'center bottom'=> esc_html__("Center Bottom", 'plumbing-parts'),
				'right bottom' => esc_html__("Right Bottom", 'plumbing-parts')
			);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'plumbing_parts_get_list_bg_image_repeats' ) ) {
	function plumbing_parts_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'plumbing-parts'),
				'repeat-x'	=> esc_html__('Repeat X', 'plumbing-parts'),
				'repeat-y'	=> esc_html__('Repeat Y', 'plumbing-parts'),
				'no-repeat'	=> esc_html__('No Repeat', 'plumbing-parts')
			);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'plumbing_parts_get_list_bg_image_attachments' ) ) {
	function plumbing_parts_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'plumbing-parts'),
				'fixed'		=> esc_html__('Fixed', 'plumbing-parts'),
				'local'		=> esc_html__('Local', 'plumbing-parts')
			);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'plumbing_parts_get_list_bg_tints' ) ) {
	function plumbing_parts_get_list_bg_tints($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'plumbing-parts'),
				'light'	=> esc_html__('Light', 'plumbing-parts'),
				'dark'	=> esc_html__('Dark', 'plumbing-parts')
			);
			$list = apply_filters('plumbing_parts_filter_bg_tints', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_field_types' ) ) {
	function plumbing_parts_get_list_field_types($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'plumbing-parts'),
				'textarea' => esc_html__('Text Area','plumbing-parts'),
				'password' => esc_html__('Password',  'plumbing-parts'),
				'radio'    => esc_html__('Radio',  'plumbing-parts'),
				'checkbox' => esc_html__('Checkbox',  'plumbing-parts'),
				'select'   => esc_html__('Select',  'plumbing-parts'),
				'date'     => esc_html__('Date','plumbing-parts'),
				'time'     => esc_html__('Time','plumbing-parts'),
				'button'   => esc_html__('Button','plumbing-parts')
			);
			$list = apply_filters('plumbing_parts_filter_field_types', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'plumbing_parts_get_list_googlemap_styles' ) ) {
	function plumbing_parts_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'plumbing-parts')
			);
			$list = apply_filters('plumbing_parts_filter_googlemap_styles', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'plumbing_parts_get_list_icons' ) ) {
	function plumbing_parts_get_list_icons($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_icons'))=='') {
			$list = plumbing_parts_parse_icons_classes(plumbing_parts_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'plumbing_parts_get_list_socials' ) ) {
	function plumbing_parts_get_list_socials($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_socials'))=='') {
			$list = plumbing_parts_get_list_files("images/socials", "png");
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'plumbing_parts_get_list_flags' ) ) {
	function plumbing_parts_get_list_flags($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_flags'))=='') {
			$list = plumbing_parts_get_list_files("images/flags", "png");
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_flags', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'plumbing_parts_get_list_yesno' ) ) {
	function plumbing_parts_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'plumbing-parts'),
			'no'  => esc_html__("No", 'plumbing-parts')
		);
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'plumbing_parts_get_list_onoff' ) ) {
	function plumbing_parts_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'plumbing-parts'),
			"off" => esc_html__("Off", 'plumbing-parts')
		);
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'plumbing_parts_get_list_showhide' ) ) {
	function plumbing_parts_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'plumbing-parts'),
			"hide" => esc_html__("Hide", 'plumbing-parts')
		);
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'plumbing_parts_get_list_orderings' ) ) {
	function plumbing_parts_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"asc" => esc_html__("Ascending", 'plumbing-parts'),
			"desc" => esc_html__("Descending", 'plumbing-parts')
		);
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'plumbing_parts_get_list_directions' ) ) {
	function plumbing_parts_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'plumbing-parts'),
			"vertical" => esc_html__("Vertical", 'plumbing-parts')
		);
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'plumbing_parts_get_list_shapes' ) ) {
	function plumbing_parts_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'plumbing-parts'),
			"square" => esc_html__("Square", 'plumbing-parts')
		);
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'plumbing_parts_get_list_sizes' ) ) {
	function plumbing_parts_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'plumbing-parts'),
			"small"  => esc_html__("Small", 'plumbing-parts'),
			"medium" => esc_html__("Medium", 'plumbing-parts'),
			"large"  => esc_html__("Large", 'plumbing-parts')
		);
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list with slider (scroll) controls positions
if ( !function_exists( 'plumbing_parts_get_list_controls' ) ) {
	function plumbing_parts_get_list_controls($prepend_inherit=false) {
		$list = array(
			"hide" => esc_html__("Hide", 'plumbing-parts'),
			"side" => esc_html__("Side", 'plumbing-parts'),
			"bottom" => esc_html__("Bottom", 'plumbing-parts')
		);
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'plumbing_parts_get_list_floats' ) ) {
	function plumbing_parts_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'plumbing-parts'),
			"left" => esc_html__("Float Left", 'plumbing-parts'),
			"right" => esc_html__("Float Right", 'plumbing-parts')
		);
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'plumbing_parts_get_list_alignments' ) ) {
	function plumbing_parts_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'plumbing-parts'),
			"left" => esc_html__("Left", 'plumbing-parts'),
			"center" => esc_html__("Center", 'plumbing-parts'),
			"right" => esc_html__("Right", 'plumbing-parts')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'plumbing-parts');
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'plumbing_parts_get_list_hpos' ) ) {
	function plumbing_parts_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'plumbing-parts');
		if ($center) $list['center'] = esc_html__("Center", 'plumbing-parts');
		$list['right'] = esc_html__("Right", 'plumbing-parts');
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'plumbing_parts_get_list_vpos' ) ) {
	function plumbing_parts_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'plumbing-parts');
		if ($center) $list['center'] = esc_html__("Center", 'plumbing-parts');
		$list['bottom'] = esc_html__("Bottom", 'plumbing-parts');
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'plumbing_parts_get_list_sortings' ) ) {
	function plumbing_parts_get_list_sortings($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'plumbing-parts'),
				"title" => esc_html__("Alphabetically", 'plumbing-parts'),
				"views" => esc_html__("Popular (views count)", 'plumbing-parts'),
				"comments" => esc_html__("Most commented (comments count)", 'plumbing-parts'),
				"author_rating" => esc_html__("Author rating", 'plumbing-parts'),
				"users_rating" => esc_html__("Visitors (users) rating", 'plumbing-parts'),
				"random" => esc_html__("Random", 'plumbing-parts')
			);
			$list = apply_filters('plumbing_parts_filter_list_sortings', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'plumbing_parts_get_list_columns' ) ) {
	function plumbing_parts_get_list_columns($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'plumbing-parts'),
				"1_1" => esc_html__("100%", 'plumbing-parts'),
				"1_2" => esc_html__("1/2", 'plumbing-parts'),
				"1_3" => esc_html__("1/3", 'plumbing-parts'),
				"2_3" => esc_html__("2/3", 'plumbing-parts'),
				"1_4" => esc_html__("1/4", 'plumbing-parts'),
				"3_4" => esc_html__("3/4", 'plumbing-parts'),
				"1_5" => esc_html__("1/5", 'plumbing-parts'),
				"2_5" => esc_html__("2/5", 'plumbing-parts'),
				"3_5" => esc_html__("3/5", 'plumbing-parts'),
				"4_5" => esc_html__("4/5", 'plumbing-parts'),
				"1_6" => esc_html__("1/6", 'plumbing-parts'),
				"5_6" => esc_html__("5/6", 'plumbing-parts'),
				"1_7" => esc_html__("1/7", 'plumbing-parts'),
				"2_7" => esc_html__("2/7", 'plumbing-parts'),
				"3_7" => esc_html__("3/7", 'plumbing-parts'),
				"4_7" => esc_html__("4/7", 'plumbing-parts'),
				"5_7" => esc_html__("5/7", 'plumbing-parts'),
				"6_7" => esc_html__("6/7", 'plumbing-parts'),
				"1_8" => esc_html__("1/8", 'plumbing-parts'),
				"3_8" => esc_html__("3/8", 'plumbing-parts'),
				"5_8" => esc_html__("5/8", 'plumbing-parts'),
				"7_8" => esc_html__("7/8", 'plumbing-parts'),
				"1_9" => esc_html__("1/9", 'plumbing-parts'),
				"2_9" => esc_html__("2/9", 'plumbing-parts'),
				"4_9" => esc_html__("4/9", 'plumbing-parts'),
				"5_9" => esc_html__("5/9", 'plumbing-parts'),
				"7_9" => esc_html__("7/9", 'plumbing-parts'),
				"8_9" => esc_html__("8/9", 'plumbing-parts'),
				"1_10"=> esc_html__("1/10", 'plumbing-parts'),
				"3_10"=> esc_html__("3/10", 'plumbing-parts'),
				"7_10"=> esc_html__("7/10", 'plumbing-parts'),
				"9_10"=> esc_html__("9/10", 'plumbing-parts'),
				"1_11"=> esc_html__("1/11", 'plumbing-parts'),
				"2_11"=> esc_html__("2/11", 'plumbing-parts'),
				"3_11"=> esc_html__("3/11", 'plumbing-parts'),
				"4_11"=> esc_html__("4/11", 'plumbing-parts'),
				"5_11"=> esc_html__("5/11", 'plumbing-parts'),
				"6_11"=> esc_html__("6/11", 'plumbing-parts'),
				"7_11"=> esc_html__("7/11", 'plumbing-parts'),
				"8_11"=> esc_html__("8/11", 'plumbing-parts'),
				"9_11"=> esc_html__("9/11", 'plumbing-parts'),
				"10_11"=> esc_html__("10/11", 'plumbing-parts'),
				"1_12"=> esc_html__("1/12", 'plumbing-parts'),
				"5_12"=> esc_html__("5/12", 'plumbing-parts'),
				"7_12"=> esc_html__("7/12", 'plumbing-parts'),
				"10_12"=> esc_html__("10/12", 'plumbing-parts'),
				"11_12"=> esc_html__("11/12", 'plumbing-parts')
			);
			$list = apply_filters('plumbing_parts_filter_list_columns', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'plumbing_parts_get_list_dedicated_locations' ) ) {
	function plumbing_parts_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'plumbing-parts'),
				"center"  => esc_html__('Above the text of the post', 'plumbing-parts'),
				"left"    => esc_html__('To the left the text of the post', 'plumbing-parts'),
				"right"   => esc_html__('To the right the text of the post', 'plumbing-parts'),
				"alter"   => esc_html__('Alternates for each post', 'plumbing-parts')
			);
			$list = apply_filters('plumbing_parts_filter_list_dedicated_locations', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'plumbing_parts_get_post_format_name' ) ) {
	function plumbing_parts_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'plumbing-parts') : esc_html__('galleries', 'plumbing-parts');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'plumbing-parts') : esc_html__('videos', 'plumbing-parts');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'plumbing-parts') : esc_html__('audios', 'plumbing-parts');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'plumbing-parts') : esc_html__('images', 'plumbing-parts');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'plumbing-parts') : esc_html__('quotes', 'plumbing-parts');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'plumbing-parts') : esc_html__('links', 'plumbing-parts');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'plumbing-parts') : esc_html__('statuses', 'plumbing-parts');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'plumbing-parts') : esc_html__('asides', 'plumbing-parts');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'plumbing-parts') : esc_html__('chats', 'plumbing-parts');
		else						$name = $single ? esc_html__('standard', 'plumbing-parts') : esc_html__('standards', 'plumbing-parts');
		return apply_filters('plumbing_parts_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'plumbing_parts_get_post_format_icon' ) ) {
	function plumbing_parts_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('plumbing_parts_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'plumbing_parts_get_list_fonts_styles' ) ) {
	function plumbing_parts_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','plumbing-parts'),
				'u' => esc_html__('U', 'plumbing-parts')
			);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'plumbing_parts_get_list_fonts' ) ) {
	function plumbing_parts_get_list_fonts($prepend_inherit=false) {
		if (($list = plumbing_parts_storage_get('list_fonts'))=='') {
			$list = array();
			$list = plumbing_parts_array_merge($list, plumbing_parts_get_list_font_faces());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>plumbing_parts_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list = plumbing_parts_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('plumbing_parts_filter_list_fonts', $list);
			if (plumbing_parts_get_theme_setting('use_list_cache')) plumbing_parts_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? plumbing_parts_array_merge(array('inherit' => esc_html__("Inherit", 'plumbing-parts')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'plumbing_parts_get_list_font_faces' ) ) {
	function plumbing_parts_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = plumbing_parts_get_folder_dir("css/font-face");
		if ( is_dir($dir) ) {
			$hdir = @ opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( ($dir) . '/' . ($file) );
					if ( substr($file, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file) ) )
						continue;
					$css = file_exists( ($dir) . '/' . ($file) . '/' . ($file) . '.css' ) 
						? plumbing_parts_get_folder_url("css/font-face/".($file).'/'.($file).'.css')
						: (file_exists( ($dir) . '/' . ($file) . '/stylesheet.css' ) 
							? plumbing_parts_get_folder_url("css/font-face/".($file).'/stylesheet.css')
							: '');
					if ($css != '')
						$list[$file.' ('.esc_html__('uploaded font', 'plumbing-parts').')'] = array('css' => $css);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}
?>