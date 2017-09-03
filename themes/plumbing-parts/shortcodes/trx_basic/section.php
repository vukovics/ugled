<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_section_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_section_theme_setup' );
	function plumbing_parts_sc_section_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_section_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_section_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_section id="unique_id" class="class_name" style="css-styles" dedicated="yes|no"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_section]
*/

plumbing_parts_storage_set('sc_section_dedicated', '');

if (!function_exists('plumbing_parts_sc_section')) {	
	function plumbing_parts_sc_section($atts, $content=null){	
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"dedicated" => "no",
			"align" => "none",
			"columns" => "none",
			"pan" => "no",
			"scroll" => "no",
			"scroll_dir" => "horizontal",
			"scroll_controls" => "hide",
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"bg_tile" => "no",
			"bg_padding" => "yes",
			"font_size" => "",
			"font_weight" => "",
            "reverse" => "no",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'plumbing-parts'),
			"link" => '',
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
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = plumbing_parts_get_scheme_color('bg');
			$rgb = plumbing_parts_hex2rgb($bg_color);
		}
	
		$class .= ($class ? ' ' : '') . plumbing_parts_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(plumbing_parts_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;') : '')
			.(!plumbing_parts_param_is_off($pan) ? 'position:relative;' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(plumbing_parts_prepare_css_value($font_size)) . '; line-height: 1.3em;' : '')
			.($font_weight != '' && !plumbing_parts_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) . ';' : '');
		$css_dim = plumbing_parts_get_css_dimensions_from_values($width, $height);
		if ($bg_image == '' && $bg_color == '' && $bg_overlay==0 && $bg_texture==0 && plumbing_parts_strlen($bg_texture)<2) $css .= $css_dim;
		
		$width  = plumbing_parts_prepare_css_value($width);
		$height = plumbing_parts_prepare_css_value($height);
	
		if ((!plumbing_parts_param_is_off($scroll) || !plumbing_parts_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());
	
		if (!plumbing_parts_param_is_off($scroll)) plumbing_parts_enqueue_slider();
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_section sc_section_reverse_'.esc_attr($reverse)
					. ($class ? ' ' . esc_attr($class) : '')
					. ($scheme && !plumbing_parts_param_is_off($scheme) && !plumbing_parts_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '') 
					. (plumbing_parts_param_is_on($scroll) && !plumbing_parts_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
					. '"'
				. (!plumbing_parts_param_is_off($animation) ? ' data-animation="'.esc_attr(plumbing_parts_get_animation_classes($animation)).'"' : '')
				. ($css!='' || $css_dim!='' ? ' style="'.esc_attr($css.$css_dim).'"' : '')
				.'>' 
				. '<div class="sc_section_inner">'
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay>0 || $bg_texture>0 || plumbing_parts_strlen($bg_texture)>2
						? '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
							. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
								. (plumbing_parts_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
								. '"'
								. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
								. '>'
								. '<div class="sc_section_content' . (plumbing_parts_param_is_on($bg_padding) ? ' padding_on' : ' padding_off') . '"'
									. ' style="'.esc_attr($css_dim).'"'
									. '>'
						: '')
					. (plumbing_parts_param_is_on($scroll) 
						? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
							. ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
							. '>'
							. '<div class="sc_scroll_wrapper swiper-wrapper">' 
							. '<div class="sc_scroll_slide swiper-slide">' 
						: '')
					. (plumbing_parts_param_is_on($pan) 
						? '<div id="'.esc_attr($id).'_pan" class="sc_pan sc_pan_'.esc_attr($scroll_dir).'">' 
						: '')
							. (!empty($subtitle) ? '<h6 class="sc_section_subtitle sc_item_subtitle">' . trim(plumbing_parts_strmacros($subtitle)) . '</h6>' : '')
							. (!empty($title) ? '<h2 class="sc_section_title sc_item_title">' . trim(plumbing_parts_strmacros($title)) . '</h2>' : '')
							. (!empty($description) ? '<div class="sc_section_descr sc_item_descr">' . trim(plumbing_parts_strmacros($description)) . '</div>' : '')
							. '<div class="sc_section_content_wrap">' . do_shortcode($content) . '</div>'
							. (!empty($link) ? '<div class="sc_section_button sc_item_button">'.plumbing_parts_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. (plumbing_parts_param_is_on($pan) ? '</div>' : '')
					. (plumbing_parts_param_is_on($scroll) 
						? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
							. (!plumbing_parts_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
						: '')
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay > 0 || $bg_texture>0 || plumbing_parts_strlen($bg_texture)>2 ? '</div></div>' : '')
					. '</div>'
				. '</div>';
		if (plumbing_parts_param_is_on($dedicated)) {
			if (plumbing_parts_storage_get('sc_section_dedicated')=='') {
				plumbing_parts_storage_set('sc_section_dedicated', $output);
			}
			$output = '';
		}
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_section', $atts, $content);
	}
	plumbing_parts_require_shortcode('trx_section', 'plumbing_parts_sc_section');
}

if (!function_exists('plumbing_parts_sc_block')) {	
	function plumbing_parts_sc_block($atts, $content=null) {
		$atts['class'] = (!empty($atts['class']) ? ' ' : '') . 'sc_section_block';
		return apply_filters('plumbing_parts_shortcode_output', plumbing_parts_sc_section($atts, $content), 'trx_block', $atts, $content);
	}
	plumbing_parts_require_shortcode('trx_block', 'plumbing_parts_sc_block');
}


/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_section_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_section_reg_shortcodes');
	function plumbing_parts_sc_section_reg_shortcodes() {
	
		$sc = array(
			"title" => esc_html__("Block container", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'plumbing-parts') ),
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
				"link" => array(
					"title" => esc_html__("Button URL", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"dedicated" => array(
					"title" => esc_html__("Dedicated", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'plumbing-parts') ),
					"value" => "no",
					"type" => "switch",
					"options" => plumbing_parts_get_sc_param('yes_no')
				),
                "reverse" => array(
                    "title" => esc_html__("Change the color", 'plumbing-parts'),
                    "desc" => esc_html__("Change the color of text", 'plumbing-parts'),
                    "value" => "no",
                    "size" => "small",
                    "options" => plumbing_parts_get_sc_param('yes_no'),
                    "type" => "switch"
                ),
				"align" => array(
					"title" => esc_html__("Align", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select block alignment", 'plumbing-parts') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => plumbing_parts_get_sc_param('align')
				),
				"columns" => array(
					"title" => esc_html__("Columns emulation", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select width for columns emulation", 'plumbing-parts') ),
					"value" => "none",
					"type" => "checklist",
					"options" => plumbing_parts_get_sc_param('columns')
				), 
				"pan" => array(
					"title" => esc_html__("Use pan effect", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Use pan effect to show section content", 'plumbing-parts') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => plumbing_parts_get_sc_param('yes_no')
				),
				"scroll" => array(
					"title" => esc_html__("Use scroller", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Use scroller to show section content", 'plumbing-parts') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => plumbing_parts_get_sc_param('yes_no')
				),
				"scroll_dir" => array(
					"title" => esc_html__("Scroll and Pan direction", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", 'plumbing-parts') ),
					"dependency" => array(
						'pan' => array('yes'),
						'scroll' => array('yes')
					),
					"value" => "horizontal",
					"type" => "switch",
					"size" => "big",
					"options" => plumbing_parts_get_sc_param('dir')
				),
				"scroll_controls" => array(
					"title" => esc_html__("Scroll controls", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'plumbing-parts') ),
					"dependency" => array(
						'scroll' => array('yes')
					),
					"value" => "hide",
					"type" => "checklist",
					"options" => plumbing_parts_get_sc_param('controls')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'plumbing-parts') ),
					"value" => "",
					"type" => "checklist",
					"options" => plumbing_parts_get_sc_param('schemes')
				),
				"color" => array(
					"title" => esc_html__("Fore color", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Any color for objects in this section", 'plumbing-parts') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Any background color for this section", 'plumbing-parts') ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image URL", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'plumbing-parts') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_tile" => array(
					"title" => esc_html__("Tile background image", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'plumbing-parts') ),
					"value" => "no",
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => plumbing_parts_get_sc_param('yes_no')
				),
				"bg_overlay" => array(
					"title" => esc_html__("Overlay", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'plumbing-parts') ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_texture" => array(
					"title" => esc_html__("Texture", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", 'plumbing-parts') ),
					"min" => "0",
					"max" => "11",
					"step" => "1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_padding" => array(
					"title" => esc_html__("Paddings around content", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'plumbing-parts') ),
					"value" => "yes",
					"dependency" => array(
						'compare' => 'or',
						'bg_color' => array('not_empty'),
						'bg_texture' => array('not_empty'),
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => plumbing_parts_get_sc_param('yes_no')
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Font weight of the text", 'plumbing-parts') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'plumbing-parts'),
						'300' => esc_html__('Light (300)', 'plumbing-parts'),
						'400' => esc_html__('Normal (400)', 'plumbing-parts'),
						'700' => esc_html__('Bold (700)', 'plumbing-parts')
					)
				),
				"_content_" => array(
					"title" => esc_html__("Container content", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Content for section container", 'plumbing-parts') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
		);
		plumbing_parts_sc_map("trx_block", $sc);
		$sc["title"] = esc_html__("Section container", 'plumbing-parts');
		$sc["desc"] = esc_html__("Container for any section ([trx_block] analog - to enable nesting)", 'plumbing-parts');
		plumbing_parts_sc_map("trx_section", $sc);
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_section_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_section_reg_shortcodes_vc');
	function plumbing_parts_sc_section_reg_shortcodes_vc() {
	
		$sc = array(
			"base" => "trx_block",
			"name" => esc_html__("Block container", 'plumbing-parts'),
			"description" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'plumbing-parts') ),
			"category" => esc_html__('Content', 'plumbing-parts'),
			'icon' => 'icon_trx_block',
			"class" => "trx_sc_collection trx_sc_block",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "dedicated",
					"heading" => esc_html__("Dedicated", 'plumbing-parts'),
					"description" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Use as dedicated content', 'plumbing-parts') => 'yes'),
					"type" => "checkbox"
				),
                array(
                    "param_name" => "reverse",
                    "heading" => esc_html__("Change the color", 'plumbing-parts'),
                    "description" => esc_html__("Change the color of text ", 'plumbing-parts'),
                    "class" => "",
                    "value" => array(esc_html__('Change the color', 'plumbing-parts') => 'yes'),
                    "type" => "checkbox"
                ),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select block alignment", 'plumbing-parts') ),
					"class" => "",
					"value" => array_flip(plumbing_parts_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns emulation", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select width for columns emulation", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(plumbing_parts_get_sc_param('columns')),
					"type" => "dropdown"
				),
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
					"param_name" => "pan",
					"heading" => esc_html__("Use pan effect", 'plumbing-parts'),
					"description" => wp_kses_data( __("Use pan effect to show section content", 'plumbing-parts') ),
					"group" => esc_html__('Scroll', 'plumbing-parts'),
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'plumbing-parts') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll",
					"heading" => esc_html__("Use scroller", 'plumbing-parts'),
					"description" => wp_kses_data( __("Use scroller to show section content", 'plumbing-parts') ),
					"group" => esc_html__('Scroll', 'plumbing-parts'),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'plumbing-parts') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll_dir",
					"heading" => esc_html__("Scroll direction", 'plumbing-parts'),
					"description" => wp_kses_data( __("Scroll direction (if Use scroller = yes)", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"group" => esc_html__('Scroll', 'plumbing-parts'),
					"value" => array_flip(plumbing_parts_get_sc_param('dir')),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scroll_controls",
					"heading" => esc_html__("Scroll controls", 'plumbing-parts'),
					"description" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'plumbing-parts') ),
					"class" => "",
					"group" => esc_html__('Scroll', 'plumbing-parts'),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"value" => array_flip(plumbing_parts_get_sc_param('controls')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'plumbing-parts') ),
					"group" => esc_html__('Colors and Images', 'plumbing-parts'),
					"class" => "",
					"value" => array_flip(plumbing_parts_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Fore color", 'plumbing-parts'),
					"description" => wp_kses_data( __("Any color for objects in this section", 'plumbing-parts') ),
					"group" => esc_html__('Colors and Images', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'plumbing-parts'),
					"description" => wp_kses_data( __("Any background color for this section", 'plumbing-parts') ),
					"group" => esc_html__('Colors and Images', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image URL", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select background image from library for this section", 'plumbing-parts') ),
					"group" => esc_html__('Colors and Images', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_tile",
					"heading" => esc_html__("Tile background image", 'plumbing-parts'),
					"description" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'plumbing-parts') ),
					"group" => esc_html__('Colors and Images', 'plumbing-parts'),
					"class" => "",
					'dependency' => array(
						'element' => 'bg_image',
						'not_empty' => true
					),
					"std" => "no",
					"value" => array(esc_html__('Tile background image', 'plumbing-parts') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", 'plumbing-parts'),
					"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'plumbing-parts') ),
					"group" => esc_html__('Colors and Images', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", 'plumbing-parts'),
					"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", 'plumbing-parts') ),
					"group" => esc_html__('Colors and Images', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_padding",
					"heading" => esc_html__("Paddings around content", 'plumbing-parts'),
					"description" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'plumbing-parts') ),
					"group" => esc_html__('Colors and Images', 'plumbing-parts'),
					"class" => "",
					'dependency' => array(
						'element' => array('bg_color','bg_texture','bg_image'),
						'not_empty' => true
					),
					"std" => "yes",
					"value" => array(esc_html__('Disable padding around content in this block', 'plumbing-parts') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'plumbing-parts'),
					"description" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'plumbing-parts'),
					"description" => wp_kses_data( __("Font weight of the text", 'plumbing-parts') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'plumbing-parts') => 'inherit',
						esc_html__('Thin (100)', 'plumbing-parts') => '100',
						esc_html__('Light (300)', 'plumbing-parts') => '300',
						esc_html__('Normal (400)', 'plumbing-parts') => '400',
						esc_html__('Bold (700)', 'plumbing-parts') => '700'
					),
					"type" => "dropdown"
				),
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Container content", 'plumbing-parts'),
					"description" => wp_kses_data( __("Content for section container", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
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
		);
		
		// Block
		vc_map($sc);
		
		// Section
		$sc["base"] = 'trx_section';
		$sc["name"] = esc_html__("Section container", 'plumbing-parts');
		$sc["description"] = wp_kses_data( __("Container for any section ([trx_block] analog - to enable nesting)", 'plumbing-parts') );
		$sc["class"] = "trx_sc_collection trx_sc_section";
		$sc["icon"] = 'icon_trx_section';
		vc_map($sc);
		
		class WPBakeryShortCode_Trx_Block extends PLUMBING_PARTS_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Section extends PLUMBING_PARTS_VC_ShortCodeCollection {}
	}
}
?>