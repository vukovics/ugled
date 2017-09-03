<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_title_description_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_title_description_theme_setup' );
	function plumbing_parts_sc_title_description_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_title_description_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_title_description_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title_description id="unique_id" style="1|2" align="left|center|right"]
	[inner shortcodes]
[/trx_title_description]
*/

if (!function_exists('plumbing_parts_sc_title_description')) {	
	function plumbing_parts_sc_title_description($atts, $content=null){	
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			"align" => "center",
			"custom" => "no",
			"accent" => "no",
			"image" => "",
			"video" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_caption" => esc_html__('Learn more', 'plumbing-parts'),
			"link2" => '',
			"link2_caption" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($id)) $id = "sc_title_description_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		if (!empty($image)) {
			$thumb_sizes = plumbing_parts_get_thumb_sizes(array('layout' => 'excerpt'));
			$image = !empty($video) 
				? plumbing_parts_get_resized_image_url($image, $thumb_sizes['w'], $thumb_sizes['h']) 
				: plumbing_parts_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}
	
		if (!empty($video)) {
			$video = '<video' . ($id ? ' id="' . esc_attr($id.'_video') . '"' : '') 
				. ' class="sc_video"'
				. ' src="' . esc_url(plumbing_parts_get_video_player_url($video)) . '"'
				. ' width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' 
				. ' data-width="' . esc_attr($width) . '" data-height="' . esc_attr($height) . '"' 
				. ' data-ratio="16:9"'
				. ($image ? ' poster="'.esc_attr($image).'" data-image="'.esc_attr($image).'"' : '') 
				. ' controls="controls" loop="loop"'
				. '>'
				. '</video>';
			if (plumbing_parts_get_custom_option('substitute_video')=='no') {
				$video = plumbing_parts_get_video_frame($video, $image, '', '');
			} else {
				if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
					$video = plumbing_parts_substitute_video($video, $width, $height, false);
				}
			}
			if (plumbing_parts_get_theme_option('use_mediaelement')=='yes')
				plumbing_parts_enqueue_script('wp-mediaelement');
		}
		
		$class .= ($class ? ' ' : '') . plumbing_parts_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= plumbing_parts_get_css_dimensions_from_values($width, $height);
		
		$content = do_shortcode($content);
		
		$featured = ($style==1 && (!empty($content) || !empty($image) || !empty($video))
					? '<div class="sc_title_description_featured column-1_2">'
						. (!empty($content) 
							? $content 
							: (!empty($video) 
								? $video 
								: $image)
							)
						. '</div>'
					: '');
	
		$need_columns = ($featured || $style==2) && !in_array($align, array('center', 'none'))
							? ($style==2 ? 4 : 2)
							: 0;
		
		$buttons = (!empty($link) || !empty($link2) 
						? '<div class="sc_title_description_buttons">'
							. (!empty($link) 
								? '<div class="sc_title_description_button sc_item_button">'.do_shortcode('[trx_button style="filled2" link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>'
								: '')
							. (!empty($link2) 
								? '<div class="sc_title_description_button sc_item_button">'.do_shortcode('[trx_button style="filled2" link="'.esc_url($link2).'" icon="icon-right"]'.esc_html($link2_caption).'[/trx_button]').'</div>'
								: '')
							. '</div>'
						: '');
	
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_title_description'
					. (plumbing_parts_param_is_on($accent) ? ' sc_title_description_accented' : '')
					. ' sc_title_description_style_' . esc_attr($style) 
					. ' sc_title_description_align_'.esc_attr($align)
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. '"'
				. (!plumbing_parts_param_is_off($animation) ? ' data-animation="'.esc_attr(plumbing_parts_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
//				. (plumbing_parts_param_is_on($accent) ? '<div class="content_wrap">' : '')
				. ($need_columns ? '<div class="columns_wrap">' : '')
				. ($align!='right' ? $featured : '')
				. '<div class="sc_title_description_info">'
					. (!empty($subtitle) ? '<h6 class="sc_title_description_subtitle sc_item_subtitle">' . trim(plumbing_parts_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_title_description_title sc_item_title">' . trim(plumbing_parts_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_title_description_descr sc_item_descr">' . trim(plumbing_parts_strmacros($description)) . '</div>' : '')
					. ($style==1 ? $buttons : '')
					. ($style==2 ? $buttons : '')
				. '</div>'
				. ($style==2 && $align!='right' ? $buttons : '')
				. ($align=='right' ? $featured : '')
				. ($need_columns ? '</div>' : '')
//				. (plumbing_parts_param_is_on($accent) ? '</div>' : '')
			. '</div>';
	
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_title_description', $atts, $content);
	}
	plumbing_parts_require_shortcode('trx_title_description', 'plumbing_parts_sc_title_description');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_title_description_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_title_description_reg_shortcodes');
	function plumbing_parts_sc_title_description_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_title_description", array(
			"title" => esc_html__("Title and description", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Insert title and description block in your page (post)", 'plumbing-parts') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Title for the block", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Description", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Short description for the block", 'plumbing-parts') ),
					"value" => "",
					"type" => "textarea"
				),
				"style" => array(
					"title" => esc_html__("Style", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select style to display block", 'plumbing-parts') ),
					"value" => "1",
					"type" => "checklist",
					"options" => plumbing_parts_get_list_styles(1, 2)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Alignment elements in the block", 'plumbing-parts') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => plumbing_parts_get_sc_param('align')
				),
//				"accent" => array(
//					"title" => esc_html__("Accented", 'plumbing-parts'),
//					"desc" => wp_kses_data( __("Fill entire block with Accent1 color from current color scheme", 'plumbing-parts') ),
//					"divider" => true,
//					"value" => "no",
//					"type" => "switch",
//					"options" => plumbing_parts_get_sc_param('yes_no')
//				),
//				"custom" => array(
//					"title" => esc_html__("Custom", 'plumbing-parts'),
//					"desc" => wp_kses_data( __("Allow get featured image or video from inner shortcodes (custom) or get it from shortcode parameters below", 'plumbing-parts') ),
//					"divider" => true,
//					"value" => "no",
//					"type" => "switch",
//					"options" => plumbing_parts_get_sc_param('yes_no')
//				),
//				"image" => array(
//					"title" => esc_html__("Image", 'plumbing-parts'),
//					"desc" => wp_kses_data( __("Select or upload image or write URL from other site to include image into this block", 'plumbing-parts') ),
//					"divider" => true,
//					"readonly" => false,
//					"value" => "",
//					"type" => "media"
//				),
//				"video" => array(
//					"title" => esc_html__("URL for video file", 'plumbing-parts'),
//					"desc" => wp_kses_data( __("Select video from media library or paste URL for video file from other site to include video into this block", 'plumbing-parts') ),
//					"readonly" => false,
//					"value" => "",
//					"type" => "media",
//					"before" => array(
//						'title' => esc_html__('Choose video', 'plumbing-parts'),
//						'action' => 'media_upload',
//						'type' => 'video',
//						'multiple' => false,
//						'linked_field' => '',
//						'captions' => array(
//							'choose' => esc_html__('Choose video file', 'plumbing-parts'),
//							'update' => esc_html__('Select video file', 'plumbing-parts')
//						)
//					),
//					"after" => array(
//						'icon' => 'icon-cancel',
//						'action' => 'media_reset'
//					)
//				),
				"link" => array(
					"title" => esc_html__("Button URL", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'plumbing-parts') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"link2" => array(
					"title" => esc_html__("Button 2 URL", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Link URL for the second button at the bottom of the block", 'plumbing-parts') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link2_caption" => array(
					"title" => esc_html__("Button 2 caption", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Caption for the second button at the bottom of the block", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"width" => plumbing_parts_shortcodes_width(),
				"height" => plumbing_parts_shortcodes_height(),
				"top" => plumbing_parts_get_sc_param('top'),
				"bottom" => plumbing_parts_get_sc_param('bottom'),
				"left" => plumbing_parts_get_sc_param('left'),
				"right" => plumbing_parts_get_sc_param('right'),
				"id" => plumbing_parts_get_sc_param('id'),
				"class" => plumbing_parts_get_sc_param('class'),
				"animation" => plumbing_parts_get_sc_param('animation'),
				"css" => plumbing_parts_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_title_description_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_title_description_reg_shortcodes_vc');
	function plumbing_parts_sc_title_description_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title_description",
			"name" => esc_html__("Title and description", 'plumbing-parts'),
			"description" => wp_kses_data( __("Insert title and description block in your page (post)", 'plumbing-parts') ),
			"category" => esc_html__('Content', 'plumbing-parts'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_collection trx_sc_title_description",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block's style", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select style to display this block", 'plumbing-parts') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(plumbing_parts_get_list_styles(1, 2)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select block alignment", 'plumbing-parts') ),
					"class" => "",
					"value" => array_flip(plumbing_parts_get_sc_param('align')),
					"type" => "dropdown"
				),
//				array(
//					"param_name" => "accent",
//					"heading" => esc_html__("Accent", 'plumbing-parts'),
//					"description" => wp_kses_data( __("Fill entire block with Accent1 color from current color scheme", 'plumbing-parts') ),
//					"class" => "",
//					"value" => array("Fill with Accent1 color" => "yes" ),
//					"type" => "checkbox"
//				),
//				array(
//					"param_name" => "custom",
//					"heading" => esc_html__("Custom", 'plumbing-parts'),
//					"description" => wp_kses_data( __("Allow get featured image or video from inner shortcodes (custom) or get it from shortcode parameters below", 'plumbing-parts') ),
//					"class" => "",
//					"value" => array("Custom content" => "yes" ),
//					"type" => "checkbox"
//				),
//				array(
//					"param_name" => "image",
//					"heading" => esc_html__("Image", 'plumbing-parts'),
//					"description" => wp_kses_data( __("Image to display inside block", 'plumbing-parts') ),
//					'dependency' => array(
//						'element' => 'custom',
//						'is_empty' => true
//					),
//					"admin_label" => true,
//					"class" => "",
//					"value" => "",
//					"type" => "attach_image"
//				),
//				array(
//					"param_name" => "video",
//					"heading" => esc_html__("URL for video file", 'plumbing-parts'),
//					"description" => wp_kses_data( __("Paste URL for video file to display inside block", 'plumbing-parts') ),
//					'dependency' => array(
//						'element' => 'custom',
//						'is_empty' => true
//					),
//					"admin_label" => true,
//					"class" => "",
//					"value" => "",
//					"type" => "textfield"
//				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'plumbing-parts'),
					"description" => wp_kses_data( __("Title for the block", 'plumbing-parts') ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'plumbing-parts'),
					"description" => wp_kses_data( __("Subtitle for the block", 'plumbing-parts') ),
					"group" => esc_html__('Captions', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'plumbing-parts'),
					"description" => wp_kses_data( __("Description for the block", 'plumbing-parts') ),
					"group" => esc_html__('Captions', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'plumbing-parts'),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'plumbing-parts') ),
					"group" => esc_html__('Captions', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'plumbing-parts'),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'plumbing-parts') ),
					"group" => esc_html__('Captions', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link2",
					"heading" => esc_html__("Button 2 URL", 'plumbing-parts'),
					"description" => wp_kses_data( __("Link URL for the second button at the bottom of the block", 'plumbing-parts') ),
					"group" => esc_html__('Captions', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link2_caption",
					"heading" => esc_html__("Button 2 caption", 'plumbing-parts'),
					"description" => wp_kses_data( __("Caption for the second button at the bottom of the block", 'plumbing-parts') ),
					"group" => esc_html__('Captions', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				plumbing_parts_get_vc_param('id'),
				plumbing_parts_get_vc_param('class'),
				plumbing_parts_get_vc_param('animation'),
				plumbing_parts_get_vc_param('css'),
				plumbing_parts_vc_width(),
				plumbing_parts_vc_height(),
				plumbing_parts_get_vc_param('margin_top'),
				plumbing_parts_get_vc_param('margin_bottom'),
				plumbing_parts_get_vc_param('margin_left'),
				plumbing_parts_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Title_Description extends PLUMBING_PARTS_VC_ShortCodeCollection {}
	}
}
?>