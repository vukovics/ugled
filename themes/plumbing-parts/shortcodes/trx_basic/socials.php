<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_socials_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_socials_theme_setup' );
	function plumbing_parts_sc_socials_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_socials_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('plumbing_parts_sc_socials')) {	
	function plumbing_parts_sc_socials($atts, $content=null){	
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => plumbing_parts_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . plumbing_parts_get_css_position_as_classes($top, $right, $bottom, $left);
		plumbing_parts_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? plumbing_parts_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) plumbing_parts_storage_set_array('sc_social_data', 'icons', $list);
		} else if (plumbing_parts_param_is_off($custom))
			$content = do_shortcode($content);
		if (plumbing_parts_storage_get_array('sc_social_data', 'icons')===false) plumbing_parts_storage_set_array('sc_social_data', 'icons', plumbing_parts_get_custom_option('social_icons'));
		$output = plumbing_parts_prepare_socials(plumbing_parts_storage_get_array('sc_social_data', 'icons'));
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!plumbing_parts_param_is_off($animation) ? ' data-animation="'.esc_attr(plumbing_parts_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	plumbing_parts_require_shortcode('trx_socials', 'plumbing_parts_sc_socials');
}


if (!function_exists('plumbing_parts_sc_social_item')) {	
	function plumbing_parts_sc_social_item($atts, $content=null){	
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (!empty($name) && empty($icon)) {
			$type = plumbing_parts_storage_get_array('sc_social_data', 'type');
			if ($type=='images') {
				if (file_exists(plumbing_parts_get_socials_dir($name.'.png')))
					$icon = plumbing_parts_get_socials_url($name.'.png');
			} else
				$icon = 'icon-'.esc_attr($name);
		}
		if (!empty($icon) && !empty($url)) {
			if (plumbing_parts_storage_get_array('sc_social_data', 'icons')===false) plumbing_parts_storage_set_array('sc_social_data', 'icons', array());
			plumbing_parts_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
				)
			);
		}
		return '';
	}
	plumbing_parts_require_shortcode('trx_social_item', 'plumbing_parts_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_socials_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_socials_reg_shortcodes');
	function plumbing_parts_sc_socials_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", 'plumbing-parts'),
			"desc" => wp_kses_data( __("List of social icons (with hovers)", 'plumbing-parts') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Type of the icons - images or font icons", 'plumbing-parts') ),
					"value" => plumbing_parts_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'plumbing-parts'),
						'images' => esc_html__('Images', 'plumbing-parts')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Size of the icons", 'plumbing-parts') ),
					"value" => "small",
					"options" => plumbing_parts_get_sc_param('sizes'),
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Shape of the icons", 'plumbing-parts') ),
					"value" => "square",
					"options" => plumbing_parts_get_sc_param('shapes'),
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'plumbing-parts') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'plumbing-parts') ),
					"divider" => true,
					"value" => "no",
					"options" => plumbing_parts_get_sc_param('yes_no'),
					"type" => "switch"
				),
				"top" => plumbing_parts_get_sc_param('top'),
				"bottom" => plumbing_parts_get_sc_param('bottom'),
				"left" => plumbing_parts_get_sc_param('left'),
				"right" => plumbing_parts_get_sc_param('right'),
				"id" => plumbing_parts_get_sc_param('id'),
				"class" => plumbing_parts_get_sc_param('class'),
				"animation" => plumbing_parts_get_sc_param('animation'),
				"css" => plumbing_parts_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", 'plumbing-parts'),
				"desc" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'plumbing-parts') ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", 'plumbing-parts'),
						"desc" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'plumbing-parts') ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", 'plumbing-parts'),
						"desc" => wp_kses_data( __("URL of your profile in specified social network", 'plumbing-parts') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", 'plumbing-parts'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'plumbing-parts') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_socials_reg_shortcodes_vc');
	function plumbing_parts_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", 'plumbing-parts'),
			"description" => wp_kses_data( __("Custom social icons", 'plumbing-parts') ),
			"category" => esc_html__('Content', 'plumbing-parts'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", 'plumbing-parts'),
					"description" => wp_kses_data( __("Type of the icons - images or font icons", 'plumbing-parts') ),
					"class" => "",
					"std" => plumbing_parts_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'plumbing-parts') => 'icons',
						esc_html__('Images', 'plumbing-parts') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", 'plumbing-parts'),
					"description" => wp_kses_data( __("Size of the icons", 'plumbing-parts') ),
					"class" => "",
					"std" => "small",
					"value" => array_flip(plumbing_parts_get_sc_param('sizes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", 'plumbing-parts'),
					"description" => wp_kses_data( __("Shape of the icons", 'plumbing-parts') ),
					"class" => "",
					"std" => "square",
					"value" => array_flip(plumbing_parts_get_sc_param('shapes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", 'plumbing-parts'),
					"description" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", 'plumbing-parts'),
					"description" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'plumbing-parts') ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'plumbing-parts') => 'yes'),
					"type" => "checkbox"
				),
				plumbing_parts_get_vc_param('id'),
				plumbing_parts_get_vc_param('class'),
				plumbing_parts_get_vc_param('animation'),
				plumbing_parts_get_vc_param('css'),
				plumbing_parts_get_vc_param('margin_top'),
				plumbing_parts_get_vc_param('margin_bottom'),
				plumbing_parts_get_vc_param('margin_left'),
				plumbing_parts_get_vc_param('margin_right')
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", 'plumbing-parts'),
			"description" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'plumbing-parts') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", 'plumbing-parts'),
					"description" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", 'plumbing-parts'),
					"description" => wp_kses_data( __("URL of your profile in specified social network", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends PLUMBING_PARTS_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends PLUMBING_PARTS_VC_ShortCodeSingle {}
	}
}
?>