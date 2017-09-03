<?php
if (is_admin() 
		|| (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true' )
		|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline')
	) {
    require_once trailingslashit( get_template_directory() ) . PLUMBING_PARTS_FW_DIR . '/core/core.shortcodes/shortcodes_vc_classes.php';
}

// Width and height params
if ( !function_exists( 'plumbing_parts_vc_width' ) ) {
	function plumbing_parts_vc_width($w='') {
		return array(
			"param_name" => "width",
			"heading" => esc_html__("Width", 'plumbing-parts'),
			"description" => wp_kses_data( __("Width of the element", 'plumbing-parts') ),
			"group" => esc_html__('Size &amp; Margins', 'plumbing-parts'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'plumbing_parts_vc_height' ) ) {
	function plumbing_parts_vc_height($h='') {
		return array(
			"param_name" => "height",
			"heading" => esc_html__("Height", 'plumbing-parts'),
			"description" => wp_kses_data( __("Height of the element", 'plumbing-parts') ),
			"group" => esc_html__('Size &amp; Margins', 'plumbing-parts'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'plumbing_parts_shortcodes_vc_scripts_admin' ) ) {
	//add_action( 'admin_enqueue_scripts', 'plumbing_parts_shortcodes_vc_scripts_admin' );
	function plumbing_parts_shortcodes_vc_scripts_admin() {
		// Include CSS 
		plumbing_parts_enqueue_style ( 'shortcodes_vc_admin-style', plumbing_parts_get_file_url('shortcodes/theme.shortcodes_vc_admin.css'), array(), null );
		// Include JS
		plumbing_parts_enqueue_script( 'shortcodes_vc_admin-script', plumbing_parts_get_file_url('core/core.shortcodes/shortcodes_vc_admin.js'), array('jquery'), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'plumbing_parts_shortcodes_vc_scripts_front' ) ) {
	//add_action( 'wp_enqueue_scripts', 'plumbing_parts_shortcodes_vc_scripts_front' );
	function plumbing_parts_shortcodes_vc_scripts_front() {
		if (plumbing_parts_vc_is_frontend()) {
			// Include CSS 
			plumbing_parts_enqueue_style ( 'shortcodes_vc_front-style', plumbing_parts_get_file_url('shortcodes/theme.shortcodes_vc_front.css'), array(), null );
			// Include JS
			plumbing_parts_enqueue_script( 'shortcodes_vc_front-script', plumbing_parts_get_file_url('core/core.shortcodes/shortcodes_vc_front.js'), array('jquery'), null, true );
			plumbing_parts_enqueue_script( 'shortcodes_vc_theme-script', plumbing_parts_get_file_url('shortcodes/theme.shortcodes_vc_front.js'), array('jquery'), null, true );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'plumbing_parts_shortcodes_vc_add_init_script' ) ) {
	//add_filter('plumbing_parts_shortcode_output', 'plumbing_parts_shortcodes_vc_add_init_script', 10, 4);
	function plumbing_parts_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (plumbing_parts_strpos($output, 'plumbing_parts_vc_init_shortcodes')===false) {
				$id = "plumbing_parts_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				$output .= '
					<script id="'.esc_attr($id).'">
						try {
							plumbing_parts_init_post_formats();
							plumbing_parts_init_shortcodes(jQuery("body").eq(0));
							plumbing_parts_scroll_actions();
						} catch (e) { };
					</script>
				';
			}
		}
		return $output;
	}
}

// Return vc_param value
if ( !function_exists( 'plumbing_parts_get_vc_param' ) ) {
	function plumbing_parts_get_vc_param($prm) {
		return plumbing_parts_storage_get_array('vc_params', $prm);
	}
}

// Set vc_param value
if ( !function_exists( 'plumbing_parts_set_vc_param' ) ) {
	function plumbing_parts_set_vc_param($prm, $val) {
		plumbing_parts_storage_set_array('vc_params', $prm, $val);
	}
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'plumbing_parts_shortcodes_vc_theme_setup' ) ) {
	//if ( plumbing_parts_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'plumbing_parts_action_after_init_theme', 'plumbing_parts_shortcodes_vc_theme_setup' );
	function plumbing_parts_shortcodes_vc_theme_setup() {


		// Set dir with theme specific VC shortcodes
		if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
			vc_set_shortcodes_templates_dir( plumbing_parts_get_folder_dir('shortcodes/vc' ) );
		}
		
		// Add/Remove params in the standard VC shortcodes
		vc_add_param("vc_row", array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'plumbing-parts') ),
					"group" => esc_html__('Color scheme', 'plumbing-parts'),
					"class" => "",
					"value" => array_flip(plumbing_parts_get_list_color_schemes(true)),
					"type" => "dropdown"
		));
		vc_add_param("vc_row", array(
					"param_name" => "inverse",
					"heading" => esc_html__("Inverse colors", 'plumbing-parts'),
					"description" => wp_kses_data( __("Inverse all colors of this block", 'plumbing-parts') ),
					"group" => esc_html__('Color scheme', 'plumbing-parts'),
					"class" => "",
					"std" => "no",
					"value" => array(esc_html__('Inverse colors', 'plumbing-parts') => 'yes'),
					"type" => "checkbox"
		));

		if (plumbing_parts_shortcodes_is_used() && class_exists('PLUMBING_PARTS_VC_ShortCodeSingle')) {

			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team') );
			
			// Disable frontend editor
			//vc_disable_frontend();

			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'plumbing_parts_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'plumbing_parts_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('plumbing_parts_shortcode_output', 'plumbing_parts_shortcodes_vc_add_init_script', 10, 4);

			// Remove standard VC shortcodes
			vc_remove_element("vc_button");
			vc_remove_element("vc_posts_slider");
			vc_remove_element("vc_gmaps");
			vc_remove_element("vc_teaser_grid");
			vc_remove_element("vc_progress_bar");
//			vc_remove_element("vc_facebook");
//			vc_remove_element("vc_tweetmeme");
//			vc_remove_element("vc_googleplus");
//			vc_remove_element("vc_facebook");
//			vc_remove_element("vc_pinterest");
			vc_remove_element("vc_message");
			vc_remove_element("vc_posts_grid");
//			vc_remove_element("vc_carousel");
//			vc_remove_element("vc_flickr");
			vc_remove_element("vc_tour");
//			vc_remove_element("vc_separator");
//			vc_remove_element("vc_single_image");
			vc_remove_element("vc_cta_button");
//			vc_remove_element("vc_accordion");
//			vc_remove_element("vc_accordion_tab");
			vc_remove_element("vc_toggle");
			vc_remove_element("vc_tabs");
			vc_remove_element("vc_tab");
//			vc_remove_element("vc_images_carousel");
			
			// Remove standard WP widgets
			vc_remove_element("vc_wp_archives");
			vc_remove_element("vc_wp_calendar");
			vc_remove_element("vc_wp_categories");
			vc_remove_element("vc_wp_custommenu");
			vc_remove_element("vc_wp_links");
			vc_remove_element("vc_wp_meta");
			vc_remove_element("vc_wp_pages");
			vc_remove_element("vc_wp_posts");
			vc_remove_element("vc_wp_recentcomments");
			vc_remove_element("vc_wp_rss");
			vc_remove_element("vc_wp_search");
			vc_remove_element("vc_wp_tagcloud");
			vc_remove_element("vc_wp_text");
			
			
			plumbing_parts_storage_set('vc_params', array(
				
				// Common arrays and strings
				'category' => esc_html__("ThemeREX shortcodes", 'plumbing-parts'),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => esc_html__("Element ID", 'plumbing-parts'),
					"description" => wp_kses_data( __("ID for the element", 'plumbing-parts') ),
					"group" => esc_html__('ID &amp; Class', 'plumbing-parts'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => esc_html__("Element CSS class", 'plumbing-parts'),
					"description" => wp_kses_data( __("CSS class for the element", 'plumbing-parts') ),
					"group" => esc_html__('ID &amp; Class', 'plumbing-parts'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => esc_html__("Animation", 'plumbing-parts'),
					"description" => wp_kses_data( __("Select animation while object enter in the visible area of page", 'plumbing-parts') ),
					"group" => esc_html__('ID &amp; Class', 'plumbing-parts'),
					"class" => "",
					"value" => array_flip(plumbing_parts_get_sc_param('animations')),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => esc_html__("CSS styles", 'plumbing-parts'),
					"description" => wp_kses_data( __("Any additional CSS rules (if need)", 'plumbing-parts') ),
					"group" => esc_html__('ID &amp; Class', 'plumbing-parts'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => esc_html__("Top margin", 'plumbing-parts'),
					"description" => wp_kses_data( __("Margin above this shortcode", 'plumbing-parts') ),
					"group" => esc_html__('Size &amp; Margins', 'plumbing-parts'),
					"std" => "inherit",
					"value" => array_flip(plumbing_parts_get_sc_param('margins')),
					"type" => "dropdown"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => esc_html__("Bottom margin", 'plumbing-parts'),
					"description" => wp_kses_data( __("Margin below this shortcode", 'plumbing-parts') ),
					"group" => esc_html__('Size &amp; Margins', 'plumbing-parts'),
					"std" => "inherit",
					"value" => array_flip(plumbing_parts_get_sc_param('margins')),
					"type" => "dropdown"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => esc_html__("Left margin", 'plumbing-parts'),
					"description" => wp_kses_data( __("Margin on the left side of this shortcode", 'plumbing-parts') ),
					"group" => esc_html__('Size &amp; Margins', 'plumbing-parts'),
					"std" => "inherit",
					"value" => array_flip(plumbing_parts_get_sc_param('margins')),
					"type" => "dropdown"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => esc_html__("Right margin", 'plumbing-parts'),
					"description" => wp_kses_data( __("Margin on the right side of this shortcode", 'plumbing-parts') ),
					"group" => esc_html__('Size &amp; Margins', 'plumbing-parts'),
					"std" => "inherit",
					"value" => array_flip(plumbing_parts_get_sc_param('margins')),
					"type" => "dropdown"
				)
			) );
			
			// Add theme-specific shortcodes
			do_action('plumbing_parts_action_shortcodes_list_vc');

		}
	}
}
?>