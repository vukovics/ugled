<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_title_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_title_theme_setup' );
	function plumbing_parts_sc_title_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_title_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('plumbing_parts_sc_title')) {	
	function plumbing_parts_sc_title($atts, $content=null){	
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . plumbing_parts_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= plumbing_parts_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !plumbing_parts_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !plumbing_parts_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(plumbing_parts_strpos($image, 'http:')!==false ? $image : plumbing_parts_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !plumbing_parts_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!plumbing_parts_param_is_off($animation) ? ' data-animation="'.esc_attr(plumbing_parts_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	plumbing_parts_require_shortcode('trx_title', 'plumbing_parts_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_title_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_title_reg_shortcodes');
	function plumbing_parts_sc_title_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_title", array(
			"title" => esc_html__("Title", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'plumbing-parts') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Title content", 'plumbing-parts') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Title type (header level)", 'plumbing-parts') ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'plumbing-parts'),
						'2' => esc_html__('Header 2', 'plumbing-parts'),
						'3' => esc_html__('Header 3', 'plumbing-parts'),
						'4' => esc_html__('Header 4', 'plumbing-parts'),
						'5' => esc_html__('Header 5', 'plumbing-parts'),
						'6' => esc_html__('Header 6', 'plumbing-parts'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Title style", 'plumbing-parts') ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'plumbing-parts'),
						'underline' => esc_html__('Underline', 'plumbing-parts'),
						'divider' => esc_html__('Divider', 'plumbing-parts'),
						'iconed' => esc_html__('With icon (image)', 'plumbing-parts')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Title text alignment", 'plumbing-parts') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => plumbing_parts_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Custom font size. If empty - use theme default", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'plumbing-parts') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'plumbing-parts'),
						'100' => esc_html__('Thin (100)', 'plumbing-parts'),
						'300' => esc_html__('Light (300)', 'plumbing-parts'),
						'400' => esc_html__('Normal (400)', 'plumbing-parts'),
						'600' => esc_html__('Semibold (600)', 'plumbing-parts'),
						'700' => esc_html__('Bold (700)', 'plumbing-parts'),
						'900' => esc_html__('Black (900)', 'plumbing-parts')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select color for the title", 'plumbing-parts') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'plumbing-parts'),
					"desc" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'plumbing-parts') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => plumbing_parts_get_sc_param('icons')
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'plumbing-parts'),
					"desc" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)",  'plumbing-parts') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => plumbing_parts_get_sc_param('images')
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'plumbing-parts') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select image (picture) size (if style='iconed')", 'plumbing-parts') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'plumbing-parts'),
						'medium' => esc_html__('Medium', 'plumbing-parts'),
						'large' => esc_html__('Large', 'plumbing-parts')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'plumbing-parts') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'plumbing-parts'),
						'left' => esc_html__('Left', 'plumbing-parts')
					)
				),
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
if ( !function_exists( 'plumbing_parts_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_title_reg_shortcodes_vc');
	function plumbing_parts_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", 'plumbing-parts'),
			"description" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'plumbing-parts') ),
			"category" => esc_html__('Content', 'plumbing-parts'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", 'plumbing-parts'),
					"description" => wp_kses_data( __("Title content", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", 'plumbing-parts'),
					"description" => wp_kses_data( __("Title type (header level)", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'plumbing-parts') => '1',
						esc_html__('Header 2', 'plumbing-parts') => '2',
						esc_html__('Header 3', 'plumbing-parts') => '3',
						esc_html__('Header 4', 'plumbing-parts') => '4',
						esc_html__('Header 5', 'plumbing-parts') => '5',
						esc_html__('Header 6', 'plumbing-parts') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", 'plumbing-parts'),
					"description" => wp_kses_data( __("Title style: only text (regular) or with icon/image (iconed)", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'plumbing-parts') => 'regular',
						esc_html__('Underline', 'plumbing-parts') => 'underline',
						esc_html__('Divider', 'plumbing-parts') => 'divider',
						esc_html__('With icon (image)', 'plumbing-parts') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'plumbing-parts'),
					"description" => wp_kses_data( __("Title text alignment", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(plumbing_parts_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'plumbing-parts'),
					"description" => wp_kses_data( __("Custom font size. If empty - use theme default", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'plumbing-parts'),
					"description" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'plumbing-parts') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'plumbing-parts') => 'inherit',
						esc_html__('Thin (100)', 'plumbing-parts') => '100',
						esc_html__('Light (300)', 'plumbing-parts') => '300',
						esc_html__('Normal (400)', 'plumbing-parts') => '400',
						esc_html__('Semibold (600)', 'plumbing-parts') => '600',
						esc_html__('Bold (700)', 'plumbing-parts') => '700',
						esc_html__('Black (900)', 'plumbing-parts') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select color for the title", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)", 'plumbing-parts') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'plumbing-parts'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => plumbing_parts_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)", 'plumbing-parts') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'plumbing-parts'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => plumbing_parts_get_sc_param('images'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'plumbing-parts') ),
					"group" => esc_html__('Icon &amp; Image', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select image (picture) size (if style=iconed)", 'plumbing-parts') ),
					"group" => esc_html__('Icon &amp; Image', 'plumbing-parts'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'plumbing-parts') => 'small',
						esc_html__('Medium', 'plumbing-parts') => 'medium',
						esc_html__('Large', 'plumbing-parts') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'plumbing-parts') ),
					"group" => esc_html__('Icon &amp; Image', 'plumbing-parts'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'plumbing-parts') => 'top',
						esc_html__('Left', 'plumbing-parts') => 'left'
					),
					"type" => "dropdown"
				),
				plumbing_parts_get_vc_param('id'),
				plumbing_parts_get_vc_param('class'),
				plumbing_parts_get_vc_param('animation'),
				plumbing_parts_get_vc_param('css'),
				plumbing_parts_get_vc_param('margin_top'),
				plumbing_parts_get_vc_param('margin_bottom'),
				plumbing_parts_get_vc_param('margin_left'),
				plumbing_parts_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends PLUMBING_PARTS_VC_ShortCodeSingle {}
	}
}
?>