<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_sc_price_block_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_price_block_theme_setup' );
	function plumbing_parts_sc_price_block_theme_setup() {
		add_action('plumbing_parts_action_shortcodes_list', 		'plumbing_parts_sc_price_block_reg_shortcodes');
		if (function_exists('plumbing_parts_exists_visual_composer') && plumbing_parts_exists_visual_composer())
			add_action('plumbing_parts_action_shortcodes_list_vc','plumbing_parts_sc_price_block_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('plumbing_parts_sc_price_block')) {	
	function plumbing_parts_sc_price_block($atts, $content=null){	
		if (plumbing_parts_in_shortcode_blogger()) return '';
		extract(plumbing_parts_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"title" => "",
			"link" => "",
			"link_text" => "",
			"icon" => "",
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			"scheme" => "",
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
		$output = '';
		$class .= ($class ? ' ' : '') . plumbing_parts_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= plumbing_parts_get_css_dimensions_from_values($width, $height);
		if ($money) $money = do_shortcode('[trx_price money="'.esc_attr($money).'" period="'.esc_attr($period).'"'.($currency ? ' currency="'.esc_attr($currency).'"' : '').']');
		$content = do_shortcode(plumbing_parts_sc_clear_around($content));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price_block sc_price_block_style_'.max(1, min(3, $style))
						. (!empty($class) ? ' '.esc_attr($class) : '')
						. ($scheme && !plumbing_parts_param_is_off($scheme) && !plumbing_parts_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!plumbing_parts_param_is_off($animation) ? ' data-animation="'.esc_attr(plumbing_parts_get_animation_classes($animation)).'"' : '')
					. '>'
				. (!empty($title) ? '<div class="sc_price_block_title"><span>'.($title).'</span></div>' : '')
				. '<div class="sc_price_block_money">'
					. (!empty($icon) ? '<div class="sc_price_block_icon '.esc_attr($icon).'"></div>' : '')
					. ($money)
				. '</div>'
				. (!empty($content) ? '<div class="sc_price_block_description">'.($content).'</div>' : '')
				. (!empty($link_text) ? '<div class="sc_price_block_link">'.do_shortcode('[trx_button link="'.($link ? esc_url($link) : '#').'"]'.($link_text).'[/trx_button]').'</div>' : '')
			. '</div>';
		return apply_filters('plumbing_parts_shortcode_output', $output, 'trx_price_block', $atts, $content);
	}
	plumbing_parts_require_shortcode('trx_price_block', 'plumbing_parts_sc_price_block');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_price_block_reg_shortcodes' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list', 'plumbing_parts_sc_price_block_reg_shortcodes');
	function plumbing_parts_sc_price_block_reg_shortcodes() {
	
		plumbing_parts_sc_map("trx_price_block", array(
			"title" => esc_html__("Price block", 'plumbing-parts'),
			"desc" => wp_kses_data( __("Insert price block with title, price and description", 'plumbing-parts') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Block style", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select style for this price block", 'plumbing-parts') ),
					"value" => 1,
					"options" => plumbing_parts_get_list_styles(1, 3),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Block title", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Link URL", 'plumbing-parts'),
					"desc" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"link_text" => array(
					"title" => esc_html__("Link text", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon",  'plumbing-parts'),
					"desc" => wp_kses_data( __('Select icon from Fontello icons set (placed before/instead price)',  'plumbing-parts') ),
					"value" => "",
					"type" => "icons",
					"options" => plumbing_parts_get_sc_param('icons')
				),
				"money" => array(
					"title" => esc_html__("Money", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'plumbing-parts') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"currency" => array(
					"title" => esc_html__("Currency", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Currency character", 'plumbing-parts') ),
					"value" => "$",
					"type" => "text"
				),
				"period" => array(
					"title" => esc_html__("Period", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'plumbing-parts') ),
					"value" => "",
					"type" => "text"
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'plumbing-parts') ),
					"value" => "",
					"type" => "checklist",
					"options" => plumbing_parts_get_sc_param('schemes')
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'plumbing-parts') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => plumbing_parts_get_sc_param('float')
				), 
				"_content_" => array(
					"title" => esc_html__("Description", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Description for this price block", 'plumbing-parts') ),
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
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_sc_price_block_reg_shortcodes_vc' ) ) {
	//add_action('plumbing_parts_action_shortcodes_list_vc', 'plumbing_parts_sc_price_block_reg_shortcodes_vc');
	function plumbing_parts_sc_price_block_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price_block",
			"name" => esc_html__("Price block", 'plumbing-parts'),
			"description" => wp_kses_data( __("Insert price block with title, price and description", 'plumbing-parts') ),
			"category" => esc_html__('Content', 'plumbing-parts'),
			'icon' => 'icon_trx_price_block',
			"class" => "trx_sc_single trx_sc_price_block",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block style", 'plumbing-parts'),
					"desc" => wp_kses_data( __("Select style of this price block", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"std" => 1,
					"value" => array_flip(plumbing_parts_get_list_styles(1, 3)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'plumbing-parts'),
					"description" => wp_kses_data( __("Block title", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'plumbing-parts'),
					"description" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_text",
					"heading" => esc_html__("Link text", 'plumbing-parts'),
					"description" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set (placed before/instead price)", 'plumbing-parts') ),
					"class" => "",
					"value" => plumbing_parts_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'plumbing-parts'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'plumbing-parts') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'plumbing-parts'),
					"description" => wp_kses_data( __("Currency character", 'plumbing-parts') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'plumbing-parts'),
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'plumbing-parts'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'plumbing-parts') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
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
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'plumbing-parts'),
					"description" => wp_kses_data( __("Align price to left or right side", 'plumbing-parts') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(plumbing_parts_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Description", 'plumbing-parts'),
					"description" => wp_kses_data( __("Description for this price block", 'plumbing-parts') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
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
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_PriceBlock extends PLUMBING_PARTS_VC_ShortCodeSingle {}
	}
}
?>