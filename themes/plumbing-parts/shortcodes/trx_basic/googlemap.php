<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_googlemap_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_googlemap_theme_setup' );
	function plumbing_parts_sc_googlemap_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_googlemap_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_googlemap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_googlemap id="unique_id" width="width_in_pixels_or_percent" height="height_in_pixels"]
//	[trx_googlemap_marker address="your_address"]
//[/trx_googlemap]

if (!function_exists('plumbing_parts_sc_googlemap')) {	
	function plumbing_parts_sc_googlemap($atts, $content = null) {
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'default',
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . plumbing_parts_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= plumbing_parts_get_css_dimensions_from_values($width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = plumbing_parts_get_custom_option('googlemap_style');

        $api_key = plumbing_parts_get_theme_option('api_google');
        plumbing_parts_enqueue_script( 'googlemap', plumbing_parts_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
        plumbing_parts_enqueue_script( 'plumbing_parts-googlemap-script', plumbing_parts_get_file_url('js/core.googlemap.js'), array(), null, true );

		$content = do_shortcode($content);
		$output = '';
		$markers = plumbing_parts_storage_get('sc_googlemap_markers');
		if (count($markers) == 0) {
			$markers[] = array(
				'title' => plumbing_parts_get_custom_option('googlemap_title'),
				'description' => plumbing_parts_strmacros(plumbing_parts_get_custom_option('googlemap_description')),
				'latlng' => plumbing_parts_get_custom_option('googlemap_latlng'),
				'address' => plumbing_parts_get_custom_option('googlemap_address'),
				'point' => plumbing_parts_get_custom_option('googlemap_marker')
			);
		}
		$output .= 
			($content ? '<div id="'.esc_attr($id).'_wrap" class="sc_googlemap_wrap'
					. ($scheme && !plumbing_parts_param_is_off($scheme) && !plumbing_parts_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">' : '')
			. '<div id="'.esc_attr($id).'"'
				. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!plumbing_parts_param_is_off($animation) ? ' data-animation="'.esc_attr(plumbing_parts_get_animation_classes($animation)).'"' : '')
				. ' data-zoom="'.esc_attr($zoom).'"'
				. ' data-style="'.esc_attr($style).'"'
				. '>';
		$cnt = 0;
		foreach ($markers as $marker) {
			$cnt++;
			if (empty($marker['id'])) $marker['id'] = $id.'_'.intval($cnt);
			$output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
				. ' data-title="'.esc_attr($marker['title']).'"'
				. ' data-description="'.esc_attr(plumbing_parts_strmacros($marker['description'])).'"'
				. ' data-address="'.esc_attr($marker['address']).'"'
				. ' data-latlng="'.esc_attr($marker['latlng']).'"'
				. ' data-point="'.esc_attr($marker['point']).'"'
				. '></div>';
		}
		$output .= '</div>'
			. ($content ? '<div class="sc_googlemap_content">' . trim($content) . '</div></div>' : '');
			
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	plumbing_parts_require_shortcode("trx_googlemap", "plumbing_parts_sc_googlemap");
}


if (!function_exists('plumbing_parts_sc_googlemap_marker')) {	
	function plumbing_parts_sc_googlemap_marker($atts, $content = null) {
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		$content = do_shortcode($content);
		plumbing_parts_storage_set_array('sc_googlemap_markers', '', array(
			'id' => $id,
			'title' => $title,
			'description' => !empty($content) ? $content : $address,
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : plumbing_parts_get_custom_option('googlemap_marker')
			)
		);
		return '';
	}
	plumbing_parts_require_shortcode("trx_googlemap_marker", "plumbing_parts_sc_googlemap_marker");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_googlemap_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_googlemap_reg_shortcodes');
	function plumbing_parts_sc_googlemap_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_googlemap", array(
			"title" => esc_html__("Google map", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Insert Google map with specified markers", 'plumbing-parts') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"zoom" => array(
					"title" => esc_html__("Zoom", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Map zoom factor", 'plumbing-parts') ),
					"divider" => true,
					"value" => 16,
					"min" => 1,
					"max" => 20,
					"type" => "spinner"
				),
				"style" => array(
					"title" => esc_html__("Map style", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select map style", 'plumbing-parts') ),
					"value" => "default",
					"type" => "checklist",
					"options" => plumbing_parts_get_sc_param('googlemap_styles')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'plumbing-parts') ),
					"value" => "",
					"type" => "checklist",
					"options" => plumbing_parts_get_sc_param('schemes')
				),
				"width" => plumbing_parts_shortcodes_width('100%'),
				"height" => plumbing_parts_shortcodes_height(240),
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
				"name" => "trx_googlemap_marker",
				"title" => esc_html__("Google map marker", 'plumbing-parts'),
				"desc" => wp_kses_data( __("Google map marker", 'plumbing-parts') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"address" => array(
						"title" => esc_html__("Address", 'plumbing-parts'),
						"desc" => wp_kses_data( __("Address of this marker", 'plumbing-parts') ),
						"value" => "",
						"type" => "text"
					),
					"latlng" => array(
						"title" => esc_html__("Latitude and Longitude", 'plumbing-parts'),
						"desc" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'plumbing-parts') ),
						"value" => "",
						"type" => "text"
					),
					"point" => array(
						"title" => esc_html__("URL for marker image file", 'plumbing-parts'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'plumbing-parts') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"title" => array(
						"title" => esc_html__("Title", 'plumbing-parts'),
						"desc" => wp_kses_data( __("Title for this marker", 'plumbing-parts') ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Description", 'plumbing-parts'),
						"desc" => wp_kses_data( __("Description for this marker", 'plumbing-parts') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => plumbing_parts_get_sc_param('id')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_googlemap_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_googlemap_reg_shortcodes_vc');
	function plumbing_parts_sc_googlemap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_googlemap",
			"name" => esc_html__("Google map", 'plumbing-parts'),
			"description" => wp_kses_data( __("Insert Google map with desired address or coordinates", 'plumbing-parts') ),
			"category" => esc_html__('Content', 'plumbing-parts'),
			'icon' => 'icon_trx_googlemap',
			"class" => "trx_sc_collection trx_sc_googlemap",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('only' => 'trx_googlemap_marker,trx_form,trx_section,trx_block,trx_promo'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "zoom",
					"heading" => esc_html__("Zoom", 'plumbing-parts'),
					"description" => wp_kses_data( __("Map zoom factor", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => "16",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'plumbing-parts'),
					"description" => wp_kses_data( __("Map custom style", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(plumbing_parts_get_sc_param('googlemap_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'plumbing-parts') ),
					"class" => "",
					"value" => array_flip(plumbing_parts_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				plumbing_parts_get_vc_param('id'),
				plumbing_parts_get_vc_param('class'),
				plumbing_parts_get_vc_param('animation'),
				plumbing_parts_get_vc_param('css'),
				plumbing_parts_vc_width('100%'),
				plumbing_parts_vc_height(240),
				plumbing_parts_get_vc_param('margin_top'),
				plumbing_parts_get_vc_param('margin_bottom'),
				plumbing_parts_get_vc_param('margin_left'),
				plumbing_parts_get_vc_param('margin_right')
			)
		) );
		
		vc_map( array(
			"base" => "trx_googlemap_marker",
			"name" => esc_html__("Googlemap marker", 'plumbing-parts'),
			"description" => wp_kses_data( __("Insert new marker into Google map", 'plumbing-parts') ),
			"class" => "trx_sc_collection trx_sc_googlemap_marker",
			'icon' => 'icon_trx_googlemap_marker',
			//"allowed_container_element" => 'vc_row',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "address",
					"heading" => esc_html__("Address", 'plumbing-parts'),
					"description" => wp_kses_data( __("Address of this marker", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "latlng",
					"heading" => esc_html__("Latitude and Longitude", 'plumbing-parts'),
					"description" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'plumbing-parts'),
					"description" => wp_kses_data( __("Title for this marker", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "point",
					"heading" => esc_html__("URL for marker image file", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				plumbing_parts_get_vc_param('id')
			)
		) );
		
		class WPBakeryShortCode_Trx_Googlemap extends PLUMBING_PARTS_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Googlemap_Marker extends PLUMBING_PARTS_VC_ShortCodeCollection {}
	}
}
?>