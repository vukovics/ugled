<?php
/**
 * Skin file for the theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if ( !function_exists( 'plumbing_parts_options_settings_theme_setup' ) ) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_options_settings_theme_setup3', 3 );	// Priority 1 for add plumbing_parts_filter handlers, 2 for create Theme Options
	function plumbing_parts_options_settings_theme_setup3() {
	    $bg_tints = plumbing_parts_get_options_param('list_bg_tints');
	    plumbing_parts_storage_set_array2('options', 'top_panel_scheme',		'options', $bg_tints);
		plumbing_parts_storage_set_array2('options', 'sidebar_main_scheme',	'options', $bg_tints);
		plumbing_parts_storage_set_array2('options', 'sidebar_outer_scheme',	'options', $bg_tints);
		plumbing_parts_storage_set_array2('options', 'sidebar_footer_scheme',	'options', $bg_tints);
		plumbing_parts_storage_set_array2('options', 'testimonials_scheme',	'options', $bg_tints);
		plumbing_parts_storage_set_array2('options', 'twitter_scheme',		'options', $bg_tints);
		plumbing_parts_storage_set_array2('options', 'contacts_scheme',		'options', $bg_tints);
		plumbing_parts_storage_set_array2('options', 'copyright_scheme',		'options', $bg_tints);
	}
}

if (!function_exists('plumbing_parts_action_skin_theme_setup')) {
	add_action( 'plumbing_parts_action_init_theme', 'plumbing_parts_action_skin_theme_setup', 1 );
	function plumbing_parts_action_skin_theme_setup() {

		// Disable less compilation
		plumbing_parts_set_theme_setting('less_compiler', 'no');
		// Disable customizer demo
		plumbing_parts_set_theme_setting('customizer_demo', false);

		// Add skin fonts in the used fonts list
		add_filter('plumbing_parts_filter_used_fonts',			'plumbing_parts_filter_skin_used_fonts');
		// Add skin fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('plumbing_parts_filter_list_fonts',			'plumbing_parts_filter_skin_list_fonts');

		// Add skin stylesheets
		add_action('plumbing_parts_action_add_styles',			'plumbing_parts_action_skin_add_styles');
		// Add skin inline styles
		add_filter('plumbing_parts_filter_add_styles_inline',		'plumbing_parts_filter_skin_add_styles_inline');
		// Add skin responsive styles
		add_action('plumbing_parts_action_add_responsive',		'plumbing_parts_action_skin_add_responsive');
		// Add skin responsive inline styles
		add_filter('plumbing_parts_filter_add_responsive_inline',	'plumbing_parts_filter_skin_add_responsive_inline');

		// Add skin scripts
		add_action('plumbing_parts_action_add_scripts',			'plumbing_parts_action_skin_add_scripts');
		// Add skin scripts inline
		add_action('plumbing_parts_action_add_scripts_inline',	'plumbing_parts_action_skin_add_scripts_inline');

		// Add skin less files into list for compilation
		add_filter('plumbing_parts_filter_compile_less',			'plumbing_parts_filter_skin_compile_less');


		/* Color schemes
		
		// Accenterd colors
		accent1			- theme accented color 1
		accent1_hover	- theme accented color 1 (hover state)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		*/

		// Add color schemes
		plumbing_parts_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'plumbing-parts'),

			// Accent colors
			'accent1'				=> '#3c3b43',
			'accent1_hover'			=> '#131314',
			'accent2'				=> '#1bbde8',
			'accent2_hover'			=> '#0590b2',
//			'accent3'				=> '',
//			'accent3_hover'			=> '',
			
			)
		);




		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		// Add Custom fonts
		plumbing_parts_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'plumbing-parts'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '4.375em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.1em',
			'margin-bottom'	=> '0.03em'
			)
		);
		plumbing_parts_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'plumbing-parts'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '3.4375em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.6667em',
			'margin-bottom'	=> '0.19em'
			)
		);
		plumbing_parts_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'plumbing-parts'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.5em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.6667em',
			'margin-bottom'	=> '0.42em'
			)
		);
		plumbing_parts_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'plumbing-parts'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.375em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.2em',
			'margin-bottom'	=> '0.5em'
			)
		);
		plumbing_parts_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'plumbing-parts'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.875em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.2em',
			'margin-bottom'	=> '0.75em'
			)
		);
		plumbing_parts_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'plumbing-parts'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.875em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.25em',
			'margin-bottom'	=> '0.65em'
			)
		);
		plumbing_parts_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'plumbing-parts'),
			'description'	=> '',
			'font-family'	=> 'Hind',
			'font-size' 	=> '16px',
			'font-weight'	=> '300',
			'font-style'	=> '',
			'line-height'	=> '1.65em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		plumbing_parts_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'plumbing-parts'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		plumbing_parts_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'plumbing-parts'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.7em'
			)
		);
		plumbing_parts_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'plumbing-parts'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.3em',
			'margin-bottom'	=> '1.3em'
			)
		);
		plumbing_parts_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'plumbing-parts'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.5em',
			'margin-bottom'	=> '0.5em'
			)
		);
		plumbing_parts_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'plumbing-parts'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1em',
			'margin-top'	=> '0.85em',
			'margin-bottom'	=> '2em'
			)
		);
		plumbing_parts_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'plumbing-parts'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em'
			)
		);
		plumbing_parts_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'plumbing-parts'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
if (!function_exists('plumbing_parts_filter_skin_used_fonts')) {
	//add_filter('plumbing_parts_filter_used_fonts', 'plumbing_parts_filter_skin_used_fonts');
	function plumbing_parts_filter_skin_used_fonts($theme_fonts) {
		$theme_fonts['Hind'] = 1;
		$theme_fonts['Playfair Display'] = 1;
		return $theme_fonts;
	}
}

// Add skin fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('plumbing_parts_filter_skin_list_fonts')) {
	//add_filter('plumbing_parts_filter_list_fonts', 'plumbing_parts_filter_skin_list_fonts');
	function plumbing_parts_filter_skin_list_fonts($list) {
		 if (!isset($list['Playfair Display'])) {
				$list['Playfair Display'] = array(
					'family' => 'serif',																						// (required) font family
					'link'   => 'Playfair+Display:400,700,900,400italic,900italic',	// (optional) if you use Google font repository
					//'css'    => plumbing_parts_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
					);
		 }
		if (!isset($list['Hind']))	$list['Hind'] = array('family'=>'sans-serif', 'link'   => 'Hind:400,500,600,700,300',);
		return $list;
	}
}



//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------
// Add skin stylesheets
if (!function_exists('plumbing_parts_action_skin_add_styles')) {
	//add_action('plumbing_parts_action_add_styles', 'plumbing_parts_action_skin_add_styles');
	function plumbing_parts_action_skin_add_styles() {
		// Add stylesheet files
		plumbing_parts_enqueue_style( 'plumbing_parts-skin-style', plumbing_parts_get_file_url('skin.css'), array(), null );
		if (file_exists(plumbing_parts_get_file_dir('skin.customizer.css')))
			plumbing_parts_enqueue_style( 'plumbing_parts-skin-customizer-style', plumbing_parts_get_file_url('skin.customizer.css'), array(), null );
	}
}

// Add skin inline styles
if (!function_exists('plumbing_parts_filter_skin_add_styles_inline')) {
	//add_filter('plumbing_parts_filter_add_styles_inline', 'plumbing_parts_filter_skin_add_styles_inline');
	function plumbing_parts_filter_skin_add_styles_inline($custom_style) {
		// Todo: add skin specific styles in the $custom_style to override
		//       rules from style.css and shortcodes.css
		// Example:
		//		$scheme = plumbing_parts_get_custom_option('body_scheme');
		//		if (empty($scheme)) $scheme = 'original';
		//		$clr = plumbing_parts_get_scheme_color('accent1');
		//		if (!empty($clr)) {
		// 			$custom_style .= '
		//				a,
		//				.bg_tint_light a,
		//				.top_panel .content .search_wrap.search_style_regular .search_form_wrap .search_submit,
		//				.top_panel .content .search_wrap.search_style_regular .search_icon,
		//				.search_results .post_more,
		//				.search_results .search_results_close {
		//					color:'.esc_attr($clr).';
		//				}
		//			';
		//		}
		$clr = plumbing_parts_get_scheme_colors();
		$clr['accent1_rgb'] = plumbing_parts_hex2rgb($clr['accent1']);
		$clr['accent1_hover_rgb'] = plumbing_parts_hex2rgb($clr['accent1_hover']);
        $clr['accent2_rgb'] = plumbing_parts_hex2rgb($clr['accent2']);
        $clr['accent2_hover_rgb'] = plumbing_parts_hex2rgb($clr['accent2_hover']);
		$fnt = plumbing_parts_get_custom_fonts_properties();
		$css = '

body {
	'.plumbing_parts_get_custom_font_css('p').';
}

h1 { '.plumbing_parts_get_custom_font_css('h1').'; '.plumbing_parts_get_custom_margins_css('h1').'; }
h2 { '.plumbing_parts_get_custom_font_css('h2').'; '.plumbing_parts_get_custom_margins_css('h2').'; }
h3 { '.plumbing_parts_get_custom_font_css('h3').'; '.plumbing_parts_get_custom_margins_css('h3').'; }
h4 { '.plumbing_parts_get_custom_font_css('h4').'; '.plumbing_parts_get_custom_margins_css('h4').'; }
h5 { '.plumbing_parts_get_custom_font_css('h5').'; '.plumbing_parts_get_custom_margins_css('h5').'; }
h6 { '.plumbing_parts_get_custom_font_css('h6').'; '.plumbing_parts_get_custom_margins_css('h6').'; }
a,
.scheme_dark a,
.scheme_light a {
	'.plumbing_parts_get_custom_font_css('link').';
	color: '.$clr['accent1'].';
}
h6 {
color: '.$clr['accent2'].';
}
a:hover,
.scheme_dark a:hover,
.scheme_light a:hover {
	color: '.$clr['accent1_hover'].';
}
ol li:before {
   color: '.$clr['accent2'].';
}

/* Accent1 color - use it as background and border with next classes */
.accent1 {			color: '.$clr['accent1'].'; }
.accent1_bgc {		background-color: '.$clr['accent1'].'; }
.accent1_bg {		background: '.$clr['accent1'].'; }
.accent1_border {	border-color: '.$clr['accent1'].'; }

a.accent1:hover {	color: '.$clr['accent1_hover'].'; }


/* 2.1 Common colors
-------------------------------------------------------------- */

/* Portfolio hovers */
.post_content.ih-item.circle.effect1.colored .info,
.post_content.ih-item.circle.effect2.colored .info,
.post_content.ih-item.circle.effect3.colored .info,
.post_content.ih-item.circle.effect4.colored .info,
.post_content.ih-item.circle.effect5.colored .info .info-back,
.post_content.ih-item.circle.effect6.colored .info,
.post_content.ih-item.circle.effect7.colored .info,
.post_content.ih-item.circle.effect8.colored .info,
.post_content.ih-item.circle.effect9.colored .info,
.post_content.ih-item.circle.effect10.colored .info,
.post_content.ih-item.circle.effect11.colored .info,
.post_content.ih-item.circle.effect12.colored .info,
.post_content.ih-item.circle.effect13.colored .info,
.post_content.ih-item.circle.effect14.colored .info,
.post_content.ih-item.circle.effect15.colored .info,
.post_content.ih-item.circle.effect16.colored .info,
.post_content.ih-item.circle.effect18.colored .info .info-back,
.post_content.ih-item.circle.effect19.colored .info,
.post_content.ih-item.circle.effect20.colored .info .info-back,
.post_content.ih-item.square.effect1.colored .info,
.post_content.ih-item.square.effect2.colored .info,
.post_content.ih-item.square.effect3.colored .info,
.post_content.ih-item.square.effect4.colored .mask1,
.post_content.ih-item.square.effect4.colored .mask2,
.post_content.ih-item.square.effect5.colored .info,
.post_content.ih-item.square.effect6.colored .info,
.post_content.ih-item.square.effect7.colored .info,
.post_content.ih-item.square.effect8.colored .info,
.post_content.ih-item.square.effect9.colored .info .info-back,
.post_content.ih-item.square.effect10.colored .info,
.post_content.ih-item.square.effect11.colored .info,
.post_content.ih-item.square.effect12.colored .info,
.post_content.ih-item.square.effect13.colored .info,
.post_content.ih-item.square.effect14.colored .info,
.post_content.ih-item.square.effect15.colored .info,
.post_content.ih-item.circle.effect20.colored .info .info-back,
.post_content.ih-item.square.effect_book.colored .info {
	background: '.$clr['accent1'].';
}

.post_content.ih-item.circle.effect1.colored .info,
.post_content.ih-item.circle.effect2.colored .info,
.post_content.ih-item.circle.effect5.colored .info .info-back,
.post_content.ih-item.circle.effect19.colored .info,
.post_content.ih-item.square.effect4.colored .mask1,
.post_content.ih-item.square.effect4.colored .mask2,
.post_content.ih-item.square.effect6.colored .info,
.post_content.ih-item.square.effect7.colored .info,
.post_content.ih-item.square.effect12.colored .info,
.post_content.ih-item.square.effect13.colored .info,
.post_content.ih-item.square.effect_more.colored .info,
.post_content.ih-item.square.effect_fade.colored:hover .info,
.post_content.ih-item.square.effect_dir.colored .info,
.post_content.ih-item.square.effect_shift.colored .info {
	background: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6);
}

.post_content.ih-item.square.effect_fade.colored .info {
	background: -moz-linear-gradient(top, rgba(255,255,255,0) 70%, rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6) 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(70%,rgba(255,255,255,0)), color-stop(100%,rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6)));
	background: -webkit-linear-gradient(top, rgba(255,255,255,0) 70%, rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6) 100%);
	background: -o-linear-gradient(top, rgba(255,255,255,0) 70%, rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6) 100%);
	background: -ms-linear-gradient(top, rgba(255,255,255,0) 70%, rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6) 100%);
	background: linear-gradient(to bottom, rgba(255,255,255,0) 70%, rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6) 100%);
}

.post_content.ih-item.circle.effect17.colored:hover .img:before {
	-webkit-box-shadow: inset 0 0 0 110px rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6), inset 0 0 0 16px rgba(255, 255, 255, 0.8), 0 1px 2px rgba(0, 0, 0, 0.1);
	-moz-box-shadow: inset 0 0 0 110px rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6), inset 0 0 0 16px rgba(255, 255, 255, 0.8), 0 1px 2px rgba(0, 0, 0, 0.1);
	box-shadow: inset 0 0 0 110px rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6), inset 0 0 0 16px rgba(255, 255, 255, 0.8), 0 1px 2px rgba(0, 0, 0, 0.1);
}

.post_content.ih-item.circle.effect1 .spinner {
	border-right-color: '.$clr['accent1'].';
	border-bottom-color: '.$clr['accent1'].';
}


/* Tables */
.sc_table table tr:first-child th:first-child {
	background-color: '.$clr['accent2'].';
}
.sc_table table tr td:last-child {
color: '.$clr['accent2'].';
}

/* Table of contents */
pre.code,
#toc .toc_item.current,
#toc .toc_item:hover {
	border-color: '.$clr['accent1'].';
}


::selection,
::-moz-selection { 
	background-color: '.$clr['accent1'].';
}




/* 3. Form fields settings
-------------------------------------------------------------- */

input[type="text"],
input[type="number"],
input[type="email"],
input[type="search"],
input[type="password"],
select,
textarea {
	'.plumbing_parts_get_custom_font_css('input').';
}


/* 7.1 Top panel
-------------------------------------------------------------- */


.top_panel_style_8 .top_panel_buttons .top_panel_cart_button:before {
	background-color: '.$clr['accent1'].';
}

.top_panel_title_inner .post_navi .post_navi_item a:hover, .top_panel_title_inner .breadcrumbs a.breadcrumbs_item:hover {
    color: '.$clr['accent2'].';
}

.top_panel_top a:hover {
	color: '.$clr['accent1_hover'].';
}

.top_panel_inner_style_4.top_panel_position_over .top_panel_top_phone {
    color: '.$clr['accent2'].';
}
.top_panel_inner_style_4.top_panel_position_over .menu_user_nav>li>a.popup_register_link {
    color: '.$clr['accent2'].';
}


/* User menu */
.menu_user_nav > li > a:hover {
	color: '.$clr['accent1_hover'].';
}
.top_panel_inner_style_4 .menu_user_nav > li > a:hover, .menu_user_nav > li.menu_user_currency > a:hover:after {
    color: '.$clr['accent2'].';
}

.menu_user_nav li.menu_user_register a {
    color: '.$clr['accent2'].';
}

.menu_user_nav li.menu_user_register a:hover {
    color: '.$clr['accent1'].';
}
.menu_user_nav li.menu_user_login a, .menu_user_nav li.menu_user_logout a {
    background-color: '.$clr['accent2'].';
}
.menu_user_nav li.menu_user_login a:hover, .menu_user_nav li.menu_user_logout a:hover {
    background-color: '.$clr['accent1'].';
}


.top_panel_inner_style_3 .menu_user_nav > li > ul:after,
.top_panel_inner_style_4 .menu_user_nav > li > ul:after,
.top_panel_inner_style_5 .menu_user_nav > li > ul:after,
.top_panel_inner_style_3 .menu_user_nav > li ul,
.top_panel_inner_style_4 .menu_user_nav > li ul,
.top_panel_inner_style_5 .menu_user_nav > li ul {
	background-color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.top_panel_inner_style_3 .menu_user_nav > li ul li a:hover,
.top_panel_inner_style_3 .menu_user_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_3 .menu_user_nav > li ul li.current-menu-ancestor > a,
.top_panel_inner_style_4 .menu_user_nav > li ul li a:hover,
.top_panel_inner_style_4 .menu_user_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_4 .menu_user_nav > li ul li.current-menu-ancestor > a,
.top_panel_inner_style_5 .menu_user_nav > li ul li a:hover,
.top_panel_inner_style_5 .menu_user_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_5 .menu_user_nav > li ul li.current-menu-ancestor > a {
	background-color: '.$clr['accent2'].';
}



/* Top panel - middle area */
.top_panel_middle .logo {
	'.plumbing_parts_get_custom_margins_css('logo').';
}
.logo .logo_text {
	'.plumbing_parts_get_custom_font_css('logo').';
}

.top_panel_middle .menu_main_wrap {
	margin-top: calc('.$fnt['logo_mt'].'*0.75);
}
.top_panel_style_5 .top_panel_middle .logo {
	margin-bottom: calc('.$fnt['logo_mb'].'*0.5);
}


/* Top panel (bottom area) */
.top_panel_bottom {
	background-color: '.$clr['accent1'].';
}



/* Top panel image in the header 7  */
.top_panel_image_hover {
	background-color: rgba('.$clr['accent1_hover_rgb']['r'].','.$clr['accent1_hover_rgb']['b'].','.$clr['accent1_hover_rgb']['b'].', 0.8);
}


/* Main menu */
.menu_main_nav > li > a {
	padding:'.$fnt['menu_mt'].' 1.3em '.$fnt['menu_mb'].';
	'.plumbing_parts_get_custom_font_css('menu').';
}
.top_panel_position_over .menu_main_nav > li > a:hover {
	color: '.$clr['accent2'].';
}
.top_panel_fixed .top_panel_position_over .menu_main_nav > li > a {
	color: '.$clr['accent1'].';
}
.top_panel_fixed .top_panel_position_over .menu_main_nav > li > a {
	color: '.$clr['accent1'].';
}
.top_panel_fixed .top_panel_position_over .menu_main_nav > li > a:hover {
	color: '.$clr['accent2'].';
}
.top_panel_fixed .top_panel_position_over .menu_main_nav > li.current-menu-ancestor.current-menu-parent > a {
    color: '.$clr['accent2'].';
}
.menu_main_nav > li > a:hover,
.menu_main_nav > li.sfHover > a,
.menu_main_nav > li#blob,
.menu_main_nav > li.current-menu-item > a,
.menu_main_nav > li.current-menu-parent > a,
.menu_main_nav > li.current-menu-ancestor > a {
  color: '.$clr['accent2'].';
}
.top_panel_inner_style_1 .menu_main_nav > li > a:hover,
.top_panel_inner_style_2 .menu_main_nav > li > a:hover {
	background-color: '.$clr['accent1_hover'].';
}
.top_panel_inner_style_1 .menu_main_nav > li ul,
.top_panel_inner_style_2 .menu_main_nav > li ul {
	border-color: '.$clr['accent1_hover'].';
	background-color: '.$clr['accent1'].';
}
.top_panel_inner_style_1 .menu_main_nav > a:hover,
.top_panel_inner_style_1 .menu_main_nav > li.sfHover > a,
.top_panel_inner_style_1 .menu_main_nav > li#blob,
.top_panel_inner_style_1 .menu_main_nav > li.current-menu-item > a,
.top_panel_inner_style_1 .menu_main_nav > li.current-menu-parent > a,
.top_panel_inner_style_1 .menu_main_nav > li.current-menu-ancestor > a,
.top_panel_inner_style_2 .menu_main_nav > a:hover,
.top_panel_inner_style_2 .menu_main_nav > li.sfHover > a,
.top_panel_inner_style_2 .menu_main_nav > li#blob,
.top_panel_inner_style_2 .menu_main_nav > li.current-menu-item > a,
.top_panel_inner_style_2 .menu_main_nav > li.current-menu-parent > a,
.top_panel_inner_style_2 .menu_main_nav > li.current-menu-ancestor > a {
	background-color: '.$clr['accent1_hover'].';
}
.menu_main_nav > li ul {
	'.plumbing_parts_get_custom_font_css('submenu').';
}
.menu_main_nav > li > ul {
	top: calc('.$fnt['menu_mt'].'+'.$fnt['menu_mb'].'+'.$fnt['menu_lh'].');
}
.menu_main_nav > li > ul:after {
    border-color:'.$clr['accent1'].';
    background-color:'.$clr['accent1'].';
}
.menu_main_nav > li > ul, .menu_main_nav > li ul {
    background-color:'.$clr['accent1'].';
}
.menu_main_nav > li ul li a {
	padding: '.$fnt['submenu_mt'].' 1.5em '.$fnt['submenu_mb'].';
}

.top_panel_inner_style_1 .menu_main_nav > li ul li a:hover,
.top_panel_inner_style_1 .menu_main_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_1 .menu_main_nav > li ul li.current-menu-ancestor > a,
.top_panel_inner_style_2 .menu_main_nav > li ul li a:hover,
.top_panel_inner_style_2 .menu_main_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_2 .menu_main_nav > li ul li.current-menu-ancestor > a {
	background-color: '.$clr['accent1_hover'].';
}
.menu_main_nav > li ul li a:hover,
.menu_main_nav > li ul li.current-menu-item > a,
.menu_main_nav > li ul li.current-menu-ancestor > a {
      background-color:'.$clr['accent2'].';
}



/* Search field */

.top_panel_inner_style_1 .search_form_wrap,
.top_panel_inner_style_2 .search_form_wrap {
	background-color: rgba('.$clr['accent1_hover_rgb']['r'].','.$clr['accent1_hover_rgb']['g'].','.$clr['accent1_hover_rgb']['b'].', 0.2); 
}


.top_panel_icon.search_wrap {
	color: '.$clr['accent1'].';
}
.top_panel_icon .contact_icon,
.top_panel_icon .search_submit {
	color: '.$clr['accent1'].';
}

.top_panel_position_over .top_panel_icon .contact_icon:hover, .top_panel_position_over .top_panel_icon .search_submit:hover {
color: '.$clr['accent2'].';
}
.top_panel_fixed .top_panel_position_over .top_panel_icon .contact_icon, .top_panel_fixed .top_panel_position_over .top_panel_icon .search_submit {
    color: '.$clr['accent1'].';
}
.top_panel_middle a:hover .contact_icon,
.top_panel_icon.search_wrap:hover,
.top_panel_icon:hover .contact_icon,
.top_panel_icon:hover .search_submit {
	color: '.$clr['accent2'].';
}

/* Search results */
.search_results .post_more,
.search_results .search_results_close {
	color: '.$clr['accent1'].';
}
.search_results .post_more:hover,
.search_results .search_results_close:hover {
	color: '.$clr['accent1_hover'].';
}
.top_panel_inner_style_1 .search_results,
.top_panel_inner_style_1 .search_results:after,
.top_panel_inner_style_2 .search_results,
.top_panel_inner_style_2 .search_results:after,
.top_panel_inner_style_3 .search_results,
.top_panel_inner_style_3 .search_results:after {
	background-color: '.$clr['accent1'].'; 
	border-color: '.$clr['accent1_hover'].'; 
}


/* Fixed menu */
.top_panel_fixed .menu_main_wrap {
	padding-top:calc('.$fnt['menu_mt'].'*0.3);
}
.top_panel_fixed .top_panel_wrap .logo {
	margin-top: calc('.$fnt['menu_mt'].'*0.6);
	margin-bottom: calc('.$fnt['menu_mb'].'*0.6);
}


/* Header style 8 */
.top_panel_inner_style_8 .top_panel_buttons,
.top_panel_inner_style_8 .menu_pushy_wrap .menu_pushy_button {
	padding-top: '.$fnt['menu_mt'].';
	padding-bottom: '.$fnt['menu_mb'].';
}
.pushy_inner a {
	color: '.$clr['accent1'].'; 
}
.pushy_inner a:hover {
	color: '.$clr['accent1_hover'].'; 
}

/* Register and login popups */
.top_panel_inner_style_3 .popup_wrap a,
.top_panel_inner_style_3 .popup_wrap .sc_socials.sc_socials_type_icons a:hover,
.top_panel_inner_style_4 .popup_wrap a,
.top_panel_inner_style_4 .popup_wrap .sc_socials.sc_socials_type_icons a:hover,
.top_panel_inner_style_5 .popup_wrap a,
.top_panel_inner_style_5 .popup_wrap .sc_socials.sc_socials_type_icons a:hover {
	color: '.$clr['accent1'].'; 
}
.top_panel_inner_style_3 .popup_wrap a:hover,
.top_panel_inner_style_4 .popup_wrap a:hover,
.top_panel_inner_style_5 .popup_wrap a:hover {
	color: '.$clr['accent1_hover'].'; 
}



/* 7.4 Main content wrapper
-------------------------------------------------------------- */

/* Layout Excerpt */
.post_title .post_icon {
	color: '.$clr['accent1'].';
}

/* Blog pagination */
.pagination > a {
	border-color: '.$clr['accent1'].';
}




/* 7.5 Post formats
-------------------------------------------------------------- */

/* Aside */
.post_format_aside.post_item_single .post_content p,
.post_format_aside .post_descr {
	border-color: '.$clr['accent1'].';
}




/* 7.6 Posts layouts
-------------------------------------------------------------- */

.post_info {
	'.plumbing_parts_get_custom_font_css('info').';
	'.plumbing_parts_get_custom_margins_css('info').';
}
.post_info a[class*="icon-"] {
	color: '.$clr['accent1'].';
}
.post_info a, .post_info a > span {
    color: '.$clr['accent1'].';
}
.post_info a:hover,
.post_info a:hover > span {
	color: '.$clr['accent2'].';
}

.post_item .post_readmore:hover .post_readmore_label {
	color: '.$clr['accent1_hover'].';
}

.post_info.post_info_bottom .post_info_tags {
    color: '.$clr['accent1'].';
}



/* Socials Share */

.sc_socials.sc_socials_share .share_caption {
    color: '.$clr['accent1'].';
}
.sc_socials.sc_socials_share .sc_socials_item a {
    border-color: '.$clr['accent2'].';
}
.sc_socials.sc_socials_share .sc_socials_item a span {
    color: '.$clr['accent2'].';
}
.sc_socials.sc_socials_share .sc_socials_item a:hover {
    background-color: '.$clr['accent2'].';
}


/* Related posts */
.post_item_related .post_info a:hover,
.post_item_related .post_title a:hover {
	color: '.$clr['accent1_hover'].';
}


/* Style "Colored" */
.isotope_item_colored .post_featured .post_mark_new,
.isotope_item_colored .post_featured .post_title,
.isotope_item_colored .post_content.ih-item.square.colored .info {
	background-color: '.$clr['accent1'].';
}

.isotope_item_colored .post_category a,
.isotope_item_colored .post_rating .reviews_stars_bg,
.isotope_item_colored .post_rating .reviews_stars_hover,
.isotope_item_colored .post_rating .reviews_value {
	color: '.$clr['accent1'].';
}

.isotope_item_colored .post_info_wrap .post_button .sc_button {
	color: '.$clr['accent1'].';
}



/* Masonry and Portfolio */
.isotope_wrap .isotope_item_colored_1 .post_featured {
	border-color: '.$clr['accent1'].';
}

/* Isotope filters */
.isotope_filters a {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.isotope_filters a.active,
.isotope_filters a:hover {
	border-color: '.$clr['accent1_hover'].';
	background-color: '.$clr['accent1_hover'].';
}




/* 7.7 Paginations
-------------------------------------------------------------- */

/* Style Pages and Slider */
.pagination_single > .pager_numbers,
.pagination_single a,
.pagination_slider .pager_cur,
.pagination_pages > a,
.pagination_pages > span {
	color: '.$clr['accent2'].';
}
.pagination_single > .pager_numbers,
.pagination_single a:hover,
.pagination_slider .pager_cur:hover,
.pagination_slider .pager_cur:focus,
.pagination_pages > .active,
.pagination_pages > a:hover {
	color: '.$clr['accent1'].';
}

.pagination_wrap .pager_next,
.pagination_wrap .pager_prev,
.pagination_wrap .pager_last,
.pagination_wrap .pager_first {
	color: '.$clr['accent2'].';
}
.pagination_wrap .pager_next:hover,
.pagination_wrap .pager_prev:hover,
.pagination_wrap .pager_last:hover,
.pagination_wrap .pager_first:hover {
	color: '.$clr['accent1_hover'].';
}



/* Style Load more */
.pagination_viewmore > a {
	background-color: '.$clr['accent1'].';
}
.pagination_viewmore > a:hover {
	background-color: '.$clr['accent1_hover'].';
}

/* Loader picture */
.viewmore_loader,
.mfp-preloader span,
.sc_video_frame.sc_video_active:before {
	background-color: '.$clr['accent1_hover'].';
}


/* 8 Single page parts
-------------------------------------------------------------- */


/* 8.1 Attachment and Portfolio post navigation
------------------------------------------------------------- */
.post_featured .post_nav_item:before {
	background-color: '.$clr['accent1'].';
}
.post_featured .post_nav_item .post_nav_info {
	background-color: '.$clr['accent1'].';
}


/* 8.2 Reviews block
-------------------------------------------------------------- */
.reviews_block .reviews_summary .reviews_item {
	background-color: '.$clr['accent1'].';
}

.reviews_block .reviews_max_level_100 .reviews_stars_hover,
.reviews_block .reviews_item .reviews_slider {
	background-color: '.$clr['accent1'].';
}
.reviews_block .reviews_item .reviews_stars_hover {
	color: '.$clr['accent1'].';
}

/* Summary stars in the post item (under the title) */
.post_item .post_rating .reviews_stars_bg,
.post_item .post_rating .reviews_value {
	color: '.$clr['accent1'].';
}
.post_item .post_rating .reviews_stars_hover, .post_item .post_rating .reviews_stars_bg {
    color: '.$clr['accent2'].';
}


/* 8.3 Post author
-------------------------------------------------------------- */
.post_author .post_author_title a {
	color: '.$clr['accent1'].';
}
.post_author .post_author_title a:hover {
	color: '.$clr['accent1_hover'].';
}
.post_author .post_author_info .sc_socials a:hover {
	color: '.$clr['accent1_hover'].';
}
.post_author .post_author_title span a {
    color: '.$clr['accent2'].';
}
.post_author .post_author_title span a:hover {
    color: '.$clr['accent2_hover'].';
}


/* 8.4 Comments
-------------------------------------------------------- */
.comments_list_wrap ul.children,
.comments_list_wrap ul > li + li {
	border-top-color: '.$clr['accent1'].';
}
.comments_list_wrap .comment-respond {
	border-bottom-color: '.$clr['accent1'].';
}
.comments_list_wrap > ul {
	border-bottom-color: '.$clr['accent1'].';
}

.comments_list_wrap .comment_info > span.comment_author{
	color: '.$clr['accent1'].';
}
.comments_list_wrap .comments_list .comment_content .comment_by_label, .comments_list_wrap .comment_info .comment_date_label {
    color: '.$clr['accent1'].';
}
.comments_list_wrap .comment_info>span.comment_author, .comments_list_wrap .comment_info>.comment_date>.comment_date_value, .comments_list_wrap .comment_reply a {
    color: '.$clr['accent2'].';
}
.comments_list_wrap .comment_info .comment_time {
     color: '.$clr['accent1'].';
}
.comments_list_wrap .comment_reply a:hover {
      color: '.$clr['accent1'].';
}


/* 8.5 Page 404
-------------------------------------------------------------- */
.post_item_404 .page_title,
.post_item_404 .page_subtitle {
	font-family: '.$fnt['logo_ff'].';
	color: '.$clr['accent1'].';
}




/* 9. Sidebars
-------------------------------------------------------------- */

/* Side menu */
.sidebar_outer_menu .menu_side_nav > li > a,
.sidebar_outer_menu .menu_side_responsive > li > a {
	'.plumbing_parts_get_custom_font_css('menu').';
}
.sidebar_outer_menu .menu_side_nav > li ul,
.sidebar_outer_menu .menu_side_responsive > li ul {
	'.plumbing_parts_get_custom_font_css('submenu').';
}
.sidebar_outer_menu .menu_side_nav > li ul li a,
.sidebar_outer_menu .menu_side_responsive > li ul li a {
	padding: '.$fnt['submenu_mt'].' 1.5em '.$fnt['submenu_mb'].';
}
.sidebar_outer_menu .sidebar_outer_menu_buttons > a:hover,
.scheme_dark .sidebar_outer_menu .sidebar_outer_menu_buttons > a:hover,
.scheme_light .sidebar_outer_menu .sidebar_outer_menu_buttons > a:hover {
	color: '.$clr['accent1'].';
}

.widget_area .widget_title {
    background-color: '.$clr['accent2'].';
}

/* Search */
aside.widget_search {
    background-color: '.$clr['accent2'].';
}



/* Common rules */
.widget_area_inner a,
.widget_area_inner ul li:before,
.widget_area_inner ul li a:hover,
.widget_area_inner button:before {
	color: '.$clr['accent1'].';
}
.widget_area_inner a:hover,
.widget_area_inner ul li a,
.widget_area_inner button:hover:before {
	color: '.$clr['accent1_hover'].';
}

.widget_area_inner .widget_text a:not(.sc_button):hover,
.widget_area_inner .post_info a:hover {
	color: '.$clr['accent2'].';
}
.widget_area_inner .post_info a {
    color: '.$clr['accent1'].';
}
.widget_area ul li:before {
    color: '.$clr['accent2'].';
}

.sidebar.widget_area .post_item .post_title a:hover {
    color: '.$clr['accent2'].';
}

/* Widget: Calendar */
.widget_area_inner .widget_calendar .weekday {
    color: '.$clr['accent2'].';
}
.widget_area_inner .widget_calendar td a:hover {
	background-color: '.$clr['accent1'].';
}
.widget_area_inner .widget_calendar .today .day_wrap {
	background-color: '.$clr['accent2'].';
}

/* Widget: Rss */
.widget_area .widget_rss .widget_title a:hover {
    color: '.$clr['accent1'].';
}


/* Widget: Tag Cloud */
.widget_area_inner .widget_product_tag_cloud a:hover,
.widget_area_inner .widget_tag_cloud a:hover {
	color: '.$clr['accent1'].';
}
.widget_area .widget_product_tag_cloud a:hover,
.widget_area .widget_tag_cloud a:hover {
    background-color: '.$clr['accent2'].';
}



/* 10. Footer areas
-------------------------------------------------------------- */

/* Twitter and testimonials */
.testimonials_wrap_inner,
.twitter_wrap_inner {
  background-color: '.$clr['accent1'].';
}

/* Copyright */
.copyright_wrap_inner .menu_footer_nav li a:hover,
.scheme_dark .copyright_wrap_inner .menu_footer_nav li a:hover,
.scheme_light .copyright_wrap_inner .menu_footer_nav li a:hover {
    color: '.$clr['accent1'].';
}
.copyright_wrap_inner .copyright_text a {
  color: '.$clr['accent2'].';
}

.copyright_wrap_inner .copyright_text a:hover {
  color: '.$clr['accent1_hover'].';
}


/* 11. Utils
-------------------------------------------------------------- */

/* Scroll to top */
.scroll_to_top {
	background-color: '.$clr['accent1'].';
}
.scroll_to_top:hover {
	background-color: '.$clr['accent1_hover'].';
}
.custom_options #co_toggle {
	background-color: '.$clr['accent1_hover'].' !important;
}


/* 13.2 WooCommerce
------------------------------------------------------ */

/* Theme colors */
.woocommerce .woocommerce-message:before, .woocommerce-page .woocommerce-message:before,
.woocommerce div.product span.price, .woocommerce #content div.product span.price, .woocommerce #content div.product p.price, .woocommerce-page div.product span.price, , .woocommerce-page #content div.product span.price, .woocommerce-page #content div.product p.price,.woocommerce ul.products li.product .price,.woocommerce-page, ul.products li.product .price, .woocommerce a.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover,
.woocommerce .quantity input[type="button"]:hover, .woocommerce #content input[type="button"]:hover, .woocommerce-page .quantity input[type="button"]:hover, .woocommerce-page #content .quantity input[type="button"]:hover,
.woocommerce ul.cart_list li > .amount, .woocommerce ul.product_list_widget li > .amount, .woocommerce-page ul.cart_list li > .amount, .woocommerce-page ul.product_list_widget li > .amount,
.woocommerce ul.cart_list li span .amount, .woocommerce ul.product_list_widget li span .amount, .woocommerce-page ul.cart_list li span .amount, .woocommerce-page ul.product_list_widget li span .amount,
.woocommerce ul.cart_list li ins .amount, .woocommerce ul.product_list_widget li ins .amount, .woocommerce-page ul.cart_list li ins .amount, .woocommerce-page ul.product_list_widget li ins .amount,
.woocommerce.widget_shopping_cart .total .amount, .woocommerce .widget_shopping_cart .total .amount, .woocommerce-page.widget_shopping_cart .total .amount, .woocommerce-page .widget_shopping_cart .total .amount,
.woocommerce a:hover h3, .woocommerce-page a:hover h3,
.woocommerce .cart-collaterals .order-total strong, .woocommerce-page .cart-collaterals .order-total strong,
.woocommerce .checkout #order_review .order-total .amount, .woocommerce-page .checkout #order_review .order-total .amount,
.woocommerce .star-rating, .woocommerce-page .star-rating, .woocommerce .star-rating:before, .woocommerce-page .star-rating:before,
.widget_area_inner .widgetWrap ul > li .star-rating span, .woocommerce #review_form #respond .stars a, .woocommerce-page #review_form #respond .stars a
{
	color: '.$clr['accent1'].';
}
.woocommerce div.product p.price, .woocommerce-page div.product p.price {
    color: '.$clr['accent2'].';
}
.woocommerce a.added_to_cart, .woocommerce-page a.added_to_cart {
 color: '.$clr['accent1'].'!important;
}
.woocommerce ul.products li.product h3{
    color: '.$clr['accent1'].'!important;
}
.woocommerce ul.products li.product h3 a:hover{
    color: '.$clr['accent2'].';
}

.woocommerce ul.products li.product .price .amount {
  color: '.$clr['accent2'].'!important;
}

.woocommerce ul.cart_list li span .amount, .woocommerce ul.product_list_widget li span .amount, .woocommerce-page ul.cart_list li span .amount, .woocommerce-page ul.product_list_widget li span .amount, .woocommerce.widget_shopping_cart .total .amount, .woocommerce .widget_shopping_cart .total .amount, .woocommerce ul.cart_list li span .amount, .woocommerce ul.product_list_widget li span .amount, .woocommerce-page ul.cart_list li span .amount, .woocommerce-page ul.product_list_widget li span .amount, .woocommerce.widget_shopping_cart .total .amount, .woocommerce .widget_shopping_cart .total .amount{
    color: '.$clr['accent2'].';
}

.woocommerce span.new, .woocommerce-page span.new,
.woocommerce span.onsale, .woocommerce-page span.onsale,
.woocommerce ul.products li.product span.new, .woocommerce-page ul.products li.product span.new,
.woocommerce ul.products li.product span.onsale, .woocommerce-page ul.products li.product span.onsale {
    background-color: '.$clr['accent2'].';
}
.woocommerce ul.products li.product.product-category h3, 
.woocommerce ul.products li.product.product-category .woocommerce-loop-category__title,
.woocommerce ul.products li.product.product-category .woocommerce-loop-category__title a,
.woocommerce-page ul.products li.product.product-category h3, 
.woocommerce-page ul.products li.product.product-category .woocommerce-loop-category__title,
.woocommerce-page ul.products li.product.product-category .woocommerce-loop-category__title a{
    color: '.$clr['accent2'].'!important;
}

.woocommerce div.quantity span, .woocommerce-page div.quantity span {
	background-color: '.$clr['accent1'].';
}
.woocommerce div.quantity span:hover, .woocommerce-page div.quantity span:hover {
	background-color: '.$clr['accent1_hover'].';
}

.woocommerce .widget_price_filter .ui-slider .ui-slider-range,.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range
{ 
	background-color: '.$clr['accent1'].';
}

.woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle
{
	background: '.$clr['accent1'].';
}

.woocommerce .widget_price_filter .price_label span {
    color: '.$clr['accent2'].';
}
.woocommerce .shop_mode_thumbs .mode_buttons form a.woocommerce_thumbs:before {
    color: '.$clr['accent2'].';
}
.woocommerce .shop_mode_list .mode_buttons form a.woocommerce_list:before {
    color: '.$clr['accent2'].';
}

.woocommerce .woocommerce-message, .woocommerce-page .woocommerce-message,
.woocommerce a.button.alt:active, .woocommerce button.button.alt:active, .woocommerce input.button.alt:active, .woocommerce #respond input#submit.alt:active, .woocommerce #content input.button.alt:active, .woocommerce-page a.button.alt:active, .woocommerce-page button.button.alt:active, .woocommerce-page input.button.alt:active, .woocommerce-page #respond input#submit.alt:active, .woocommerce-page #content input.button.alt:active,
.woocommerce a.button:active, .woocommerce button.button:active, .woocommerce input.button:active, .woocommerce #respond input#submit:active, .woocommerce #content input.button:active, .woocommerce-page a.button:active, .woocommerce-page button.button:active, .woocommerce-page input.button:active, .woocommerce-page #respond input#submit:active, .woocommerce-page #content input.button:active
{ 
	border-top-color: '.$clr['accent1'].';
}

/* Buttons */
.woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce-account .addresses .title .edit {
	background-color: '.$clr['accent1'].';
}
.woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover, .woocommerce a.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover, .woocommerce-account .addresses .title .edit:hover {
	background-color: '.$clr['accent1_hover'].';
}

.woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce-page button.button:hover, .woocommerce button.button.alt:hover, .woocommerce-page button.button.alt:hover {
background-color: '.$clr['accent1'].';
}



/* Products stream */
.woocommerce span.new, .woocommerce-page span.new,
.woocommerce span.onsale, .woocommerce-page span.onsale {
	background-color: '.$clr['accent1_hover'].';
}

.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price {
    color: '.$clr['accent2'].';
}


.woocommerce ul.products li.product .star-rating:before, .woocommerce ul.products li.product .star-rating span {
	color: '.$clr['accent1'].';
}


/* Single product */
.single-product .woocommerce-tabs.trx-stretch-width .wc-tabs li.active a:after {
	background-color: '.$clr['accent1'].';
}
.single-product .woocommerce-tabs.trx-stretch-width .wc-tabs li.active a:hover:after {
	background-color: '.$clr['accent1_hover'].';
}
.single-product div.product .woocommerce-tabs.trx-stretch-width .wc-tabs li a:hover,
.single-product div.product .woocommerce-tabs.trx-stretch-width .wc-tabs li.active a {
    background-color: '.$clr['accent2'].';
}


/* Pagination */
.woocommerce nav.woocommerce-pagination ul li a, .woocommerce nav.woocommerce-pagination ul li span.current {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current {
	color: '.$clr['accent1'].';
}

/* Cart */
.woocommerce table.cart thead th, .woocommerce #content table.cart thead th, .woocommerce-page table.cart thead th, .woocommerce-page #content table.cart thead th {
	background-color: '.$clr['accent1'].';
}
.woocommerce a.remove:hover {
    color: '.$clr['accent2'].'!important;
}
.woocommerce-page table.cart .product-price .amount, .woocommerce-page table.cart .product-subtotal .amount {
     color: '.$clr['accent2'].';
}
.woocommerce-page table.cart td.actions .input-text + .button {
    background-color: '.$clr['accent2'].';
}
.woocommerce-page table.cart td.actions .button:hover {
    background-color: '.$clr['accent2'].';
}
.woocommerce-page table.cart td.actions .input-text + .button:hover {
    background-color: '.$clr['accent1'].';
}
.woocommerce-cart .cart-collaterals .cart_totals table tr th {
    color: '.$clr['accent1'].';
}
.woocommerce-cart .cart-collaterals .cart_totals table tr td span.amount {
    color: '.$clr['accent2'].';
}
.woocommerce-page table.shop_table .shipping td .shipping-calculator-button {
      color: '.$clr['accent2'].';
}
.woocommerce-cart .wc-proceed-to-checkout a.button{
     background-color: '.$clr['accent2'].';
}
.woocommerce-cart .wc-proceed-to-checkout a.button:hover{
     background-color: '.$clr['accent1'].';
}

.woocommerce.widget_shopping_cart .quantity, .woocommerce .widget_shopping_cart .quantity, .woocommerce-page.widget_shopping_cart .quantity, .woocommerce-page .widget_shopping_cart .quantity {
    color: '.$clr['accent1'].';
}
.woocommerce .widget_shopping_cart .buttons a:first-child, .woocommerce-page .widget_shopping_cart .buttons a:first-child {
     color: '.$clr['accent2'].';
     border-color: '.$clr['accent2'].';
}

.woocommerce .widget_shopping_cart .buttons a:first-child:hover, .woocommerce-page .widget_shopping_cart .buttons a:first-child:hover {
     color: '.$clr['accent1_hover'].';
     border-color: '.$clr['accent1_hover'].';
}
.woocommerce .widget_shopping_cart .buttons a:last-child, .woocommerce-page .widget_shopping_cart .buttons a:last-child{
 background-color: '.$clr['accent2'].';
}


/* 13.3 Tribe Events
------------------------------------------------------- */
.tribe-events-calendar thead th {
	background-color: '.$clr['accent1'].';
}

/* Buttons */
a.tribe-events-read-more,
.tribe-events-button,
.tribe-events-nav-previous a,
.tribe-events-nav-next a,
.tribe-events-widget-link a,
.tribe-events-viewmore a {
	background-color: '.$clr['accent1'].';
}
a.tribe-events-read-more:hover,
.tribe-events-button:hover,
.tribe-events-nav-previous a:hover,
.tribe-events-nav-next a:hover,
.tribe-events-widget-link a:hover,
.tribe-events-viewmore a:hover {
	background-color: '.$clr['accent1_hover'].';
}




/* 13.4 BB Press and Buddy Press
------------------------------------------------------- */

/* Buttons */
#bbpress-forums div.bbp-topic-content a,
#buddypress button, #buddypress a.button, #buddypress input[type="submit"], #buddypress input[type="button"], #buddypress input[type="reset"], #buddypress ul.button-nav li a, #buddypress div.generic-button a, #buddypress .comment-reply-link, a.bp-title-button, #buddypress div.item-list-tabs ul li.selected a {
	background: '.$clr['accent1'].';
}
#bbpress-forums div.bbp-topic-content a:hover,
#buddypress button:hover, #buddypress a.button:hover, #buddypress input[type="submit"]:hover, #buddypress input[type="button"]:hover, #buddypress input[type="reset"]:hover, #buddypress ul.button-nav li a:hover, #buddypress div.generic-button a:hover, #buddypress .comment-reply-link:hover, a.bp-title-button:hover, #buddypress div.item-list-tabs ul li.selected a:hover {
	background: '.$clr['accent1_hover'].';
}

#buddypress #reply-title small a span, #buddypress a.bp-primary-action span {
	color: '.$clr['accent1'].';
}



/* 13.6 Booking Calendar
------------------------------------------------------- */
.booking_font_custom,
.booking_day_container,
.booking_calendar_container_all {
	font-family: '.$fnt['p_ff'].';
}
.booking_weekdays_custom {
	font-family: '.$fnt['h1_ff'].';
}
.booking_month_navigation_button_custom:hover {
	background-color: '.$clr['accent1_hover'].' !important;
}



/* 13.6 LearnDash LMS
------------------------------------------------------- */
#learndash_next_prev_link > a {
	background-color: '.$clr['accent1'].';
}
#learndash_next_prev_link > a:hover {
	background-color: '.$clr['accent1_hover'].';
}
.widget_area dd.course_progress div.course_progress_blue {
	background-color: '.$clr['accent1_hover'].';
}


/* 15. Shortcodes
-------------------------------------------------------------- */

/* Accordion */
.sc_accordion .sc_accordion_item .sc_accordion_title:before {
    color: '.$clr['accent2'].';
}
.sc_accordion .sc_accordion_item .sc_accordion_title.ui-state-active {
	color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}

.sc_accordion .sc_accordion_item .sc_accordion_title:hover {
	color: '.$clr['accent1_hover'].';
	border-color: '.$clr['accent1_hover'].';
}


/* Audio */
.sc_audio .sc_audio_author_name,
.sc_audio .sc_audio_title {
	color: '.$clr['accent1'].';
}
.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current, .mejs-controls .mejs-time-rail .mejs-time-current {
	background: '.$clr['accent1'].' !important;
}
.mejs-controls .mejs-play button:before, .mejs-controls .mejs-pause button:before, .mejs-controls .mejs-mute button:before, .mejs-controls .mejs-unmute button:before {
background: '.$clr['accent2'].';
}
.mejs-controls .mejs-time-rail .mejs-time-current, .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current, .mejs-controls .mejs-time-rail .mejs-time-current {
background: '.$clr['accent2'].' !important;
}


/* Button */
input[type="submit"],
input[type="reset"],
input[type="button"],
button,
.sc_button {
	'.plumbing_parts_get_custom_font_css('button').';
}
input[type="submit"],
input[type="reset"],
input[type="button"],
button, .sc_button.sc_button_style_filled, .woocommerce a.button {
	background-color: '.$clr['accent1'].';
}

input[type="submit"]:hover,
input[type="reset"]:hover,
input[type="button"]:hover{
background-color: '.$clr['accent1_hover'].';
}

.sc_button:before, .woocommerce a.button:before, .woocommerce button:before {
  background-color: rgba('.$clr['accent1_hover_rgb']['r'].','.$clr['accent1_hover_rgb']['g'].','.$clr['accent1_hover_rgb']['b'].', 0.5);
}

.sc_button:after, .woocommerce a.button:after, .woocommerce button:after {
    background: '.$clr['accent1_hover'].';
}




.sc_button.sc_button_style_border {
	border-color: '.$clr['accent1'].';
	color: '.$clr['accent1'].';
}
.sc_button.sc_button_style_border:hover {
	border-color: '.$clr['accent1_hover'].' !important;
}

.sc_button.sc_button_style_filled2, .sc_call_to_action_style_3 .sc_call_to_action_buttons.sc_item_buttons .sc_button, .sc_button.sc_button_style_filled2:hover, .sc_call_to_action_style_3 .sc_call_to_action_buttons.sc_item_buttons .sc_button:hover, .sc_title_description_style_2 .sc_title_description_buttons .sc_title_description_button.sc_item_button .sc_button, .sc_title_description_style_2 .sc_title_description_buttons .sc_title_description_button.sc_item_button .sc_button:hover {
 background: '.$clr['accent2'].';
}

.safaribliat .sc_title_description_style_2 .sc_title_description_buttons .sc_title_description_button.sc_item_button .sc_button:hover {
 background: '.$clr['accent2_hover'].';
}

.sc_button.sc_button_style_filled2:before, .sc_call_to_action_style_3 .sc_call_to_action_buttons.sc_item_buttons .sc_button:before {
  background-color: rgba('.$clr['accent2_hover_rgb']['r'].','.$clr['accent2_hover_rgb']['g'].','.$clr['accent2_hover_rgb']['b'].', 0.5);
}
.sc_button.sc_button_style_filled2:after, .sc_call_to_action_style_3 .sc_call_to_action_buttons.sc_item_buttons .sc_button:after {
  background: '.$clr['accent2_hover'].';
}



/* Blogger */
.sc_blogger.layout_date .sc_blogger_item .sc_blogger_date { 
	background-color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.sc_blogger.layout_polaroid .photostack nav span.current {
	background-color: '.$clr['accent1'].';
}
.sc_blogger.layout_polaroid .photostack nav span.current.flip {
	background-color: '.$clr['accent1_hover'].';
}


/* Call to Action */
.sc_call_to_action_accented {
	background-color: '.$clr['accent1'].';
}
.sc_call_to_action_accented .sc_item_button > a {
	color: '.$clr['accent1'].';
}
.sc_call_to_action_accented .sc_item_button > a:before {
	background-color: '.$clr['accent1'].';
}
.vc_row.inverse_colors .sc_call_to_action_style_1 .sc_call_to_action_buttons a {
color: '.$clr['accent1'].'!important;
}
.inverse_colors .sc_call_to_action_style_1 .sc_call_to_action_buttons a:hover, .inverse_colors .sc_call_to_action_style_1 .sc_call_to_action_buttons .sc_call_to_action_button + .sc_call_to_action_button a:hover  {
    border-color: '.$clr['accent1_hover'].'!important;
}


/* Chat */
.sc_chat_inner a {
  color: '.$clr['accent1'].';
}
.sc_chat_inner a:hover {
  color: '.$clr['accent1_hover'].';
}

/* Clients */
.sc_clients_style_clients-2 .sc_client_title a:hover {
	color: '.$clr['accent1'].';
}
.sc_clients_style_clients-2 .sc_client_description:before,
.sc_clients_style_clients-2 .sc_client_position {
	color: '.$clr['accent1'].';
}

/* Contact form */
.sc_form .sc_form_item.sc_form_button button { 
	background-color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.sc_form .sc_form_item.sc_form_button button:hover { 
	background-color: '.$clr['accent1_hover'].';
	border-color: '.$clr['accent1_hover'].';
}

/* picker */
.sc_form table.picker__table th {
	background-color: '.$clr['accent1'].';
}
.sc_form .picker__day--today:before,
.sc_form .picker__button--today:before,
.sc_form .picker__button--clear:before,
.sc_form button:focus {
	border-color: '.$clr['accent1'].';
}
.sc_form .picker__button--close:before {
	color: '.$clr['accent1'].';
}
.sc_form .picker--time .picker__button--clear:hover,
.sc_form .picker--time .picker__button--clear:focus {
	background-color: '.$clr['accent1_hover'].';
}


/* Countdown Style 1 */
.sc_countdown.sc_countdown_style_1 .sc_countdown_digits,
.sc_countdown.sc_countdown_style_1 .sc_countdown_separator {
	color: '.$clr['accent1'].';
}
.sc_countdown.sc_countdown_style_1 .sc_countdown_label {
	color: '.$clr['accent1'].';
}

/* Countdown Style 2 */
.sc_countdown.sc_countdown_style_2 .sc_countdown_separator {
	color: '.$clr['accent1'].';
}
.sc_countdown.sc_countdown_style_2 .sc_countdown_digits span {
	background-color: '.$clr['accent1'].';
}
.sc_countdown.sc_countdown_style_2 .sc_countdown_label {
	color: '.$clr['accent1'].';
}

/* Dropcaps */
.sc_dropcaps.sc_dropcaps_style_1 .sc_dropcaps_item {
	color: '.$clr['accent2'].';
}
.sc_dropcaps.sc_dropcaps_style_2 .sc_dropcaps_item {
	background-color: '.$clr['accent2'].';
}
.sc_dropcaps.sc_dropcaps_style_3 .sc_dropcaps_item {
	color: '.$clr['accent2_hover'].';
}
.sc_dropcaps.sc_dropcaps_style_4 .sc_dropcaps_item {
	color: '.$clr['accent2'].';
}


/* Quote */
.sc_quote p {
   color: '.$clr['accent1'].';
}
.sc_quote_style_2 {
    border-color: '.$clr['accent2'].';
}

/* Emailer */
.sc_emailer.sc_emailer_opened .sc_emailer_button {
    background-color: '.$clr['accent2'].';
}
.sc_emailer.sc_emailer_opened .sc_emailer_button:hover {
    background-color: '.$clr['accent2_hover'].';
}


/* Events */
.sc_events_style_events-2 .sc_events_item_date {
	background-color: '.$clr['accent1'].';
}


/* Highlight */
.sc_highlight_style_1 {
	background-color: '.$clr['accent1'].';
}
.sc_highlight_style_2 {
	background-color: '.$clr['accent1_hover'].';
}


/* Icon */
.sc_icon_hover:hover,
a:hover .sc_icon_hover {
	background-color: '.$clr['accent1'].' !important; 
}

.sc_icon_shape_round.sc_icon,
.sc_icon_shape_square.sc_icon {	
	background-color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}

.sc_icon_shape_round.sc_icon:hover,
.sc_icon_shape_square.sc_icon:hover,
a:hover .sc_icon_shape_round.sc_icon,
a:hover .sc_icon_shape_square.sc_icon {
	color: '.$clr['accent1'].';
}


/* Image */
figure figcaption,
.sc_image figcaption {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6);
}


/* Infobox */
.sc_infobox.sc_infobox_style_regular span, .sc_infobox.sc_infobox_style_regular:before {color: '.$clr['accent2'].';}


/* List */
.sc_list_style_iconed li:before,
.sc_list_style_iconed .sc_list_icon {
	color: '.$clr['accent1'].';
}
.sc_list_style_iconed li a:hover .sc_list_title {
	color: '.$clr['accent1_hover'].';
}


/* Popup */
.sc_popup:before {
	background-color: '.$clr['accent1'].';
}


/* Price block */
.sc_price_block.sc_price_block_style_1 {
	background-color: '.$clr['accent1'].';
}
.sc_price_block.sc_price_block_style_1 .sc_price_block_link .sc_button {
	background-color: '.$clr['accent1_hover'].';
}
.sc_price_block.sc_price_block_style_2 .sc_price_block_title {
    background-color: '.$clr['accent2'].';
}

.sc_price_block.sc_price_block_style_2 .sc_price_block_money .sc_price_money {
    color: '.$clr['accent1'].';
}
.sc_price_block.sc_price_block_style_2 .sc_price_block_description a {
    color: '.$clr['accent2'].';
    border-color: '.$clr['accent2'].';
}
.sc_price_block.sc_price_block_style_2 .sc_price_block_description a:hover {
    background-color: '.$clr['accent2_hover'].';
     border-color: '.$clr['accent2_hover'].';
}


/* Plumbing Parts - Recent News */
.sc_recent_news_header_category_item_more {
	color: '.$clr['accent1'].';
}
.sc_recent_news_header_more_categories > a {
	color:'.$clr['accent1'].';
}
.sc_recent_news_header_more_categories > a:hover {
	color:'.$clr['accent1_hover'].';
}

/*Promo block*/

.sc_promo .sc_promo_title {
 color: '.$clr['accent2'].';
}

/* Grid */
.minimal-dark .esg-navigationbutton:hover * {
    color: '.$clr['accent2'].'!important;
}
.flat-light .esg-filterbutton, .flat-light .esg-navigationbutton, .flat-light .esg-sortbutton, .flat-light .esg-cartbutton {
 color: '.$clr['accent2'].'!important;
}

/* Section */
.sc_section_reverse_yes .sc_call_to_action_style_1 .sc_call_to_action_buttons a {
    color: '.$clr['accent1'].';
}
.sc_section_reverse_yes .sc_call_to_action_style_1 .sc_call_to_action_buttons a:hover, .sc_section_reverse_yes .sc_call_to_action_style_1 .sc_call_to_action_buttons .sc_call_to_action_button + .sc_call_to_action_button a:hover {
    border-color: '.$clr['accent1_hover'].';
}



/* Services */
.sc_services_item .sc_services_item_readmore span {
  color: '.$clr['accent1'].';
}
.sc_services_item .sc_services_item_readmore:hover,
.sc_services_item .sc_services_item_readmore:hover span {
  color: '.$clr['accent1_hover'].';
}
.sc_services_style_services-1 .sc_icon,
.sc_services_style_services-2 .sc_icon {
	color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.sc_services_style_services-1 .sc_services_item a {
    color: '.$clr['accent2'].';
}
.sc_services_style_services-1 .sc_services_item a:hover {
    color: '.$clr['accent1'].';
}
.sc_services_style_services-1.sc_services_type_images .sc_services_item .sc_services_item_content .sc_services_item_title a{
   color: '.$clr['accent1'].';
}
.sc_services_style_services-1.sc_services_type_images .sc_services_item .sc_services_item_content .sc_services_item_title a:hover {
color: '.$clr['accent2'].';
}
.sc_services_style_services-1 .sc_services_item a.sc_services_item_readmore {
    background-color: '.$clr['accent1'].';
}
.sc_services_style_services-1 .sc_icon:hover,
.sc_services_style_services-1 a:hover .sc_icon,
.sc_services_style_services-2 .sc_icon:hover,
.sc_services_style_services-2 a:hover .sc_icon {
	background-color: '.$clr['accent1'].';
}
.sc_services_style_services-3 a:hover .sc_icon,
.sc_services_style_services-3 .sc_icon:hover {
	color: '.$clr['accent1'].';
}
.sc_services_style_services-3 a:hover .sc_services_item_title {
	color: '.$clr['accent1'].';
}
.sc_services_style_services-4 .sc_icon {
	background-color: '.$clr['accent1'].';
}
.sc_services_style_services-4 a:hover .sc_icon,
.sc_services_style_services-4 .sc_icon:hover {
	background-color: '.$clr['accent1_hover'].';
}
.sc_services_style_services-4 a:hover .sc_services_item_title {
	color: '.$clr['accent1'].';
}
.sc_services_style_services-5 .sc_icon {
	border-color: '.$clr['accent1'].';
}
.sc_services_style_services-5 .sc_icon {
	color: '.$clr['accent1'].';
}
.sc_services_style_services-5 .sc_icon:hover,
.sc_services_style_services-5 a:hover .sc_icon {
	background-color: '.$clr['accent1'].';
}
.sc_services_style_services-1 .sc_icon:hover,
.sc_services_style_services-1 a:hover .sc_icon,
.sc_services_style_services-2 .sc_icon:hover,
.sc_services_style_services-2 a:hover .sc_icon {
	color: '.$clr['accent1'].';
}

/* Single-services */

.single.single-services .sidebar.widget_area .widget_area_inner aside {
    background-color: '.$clr['accent2'].';
}


/* Scroll controls */
.sc_scroll_controls_wrap a {
	background-color: '.$clr['accent1'].';
}
.sc_scroll_controls_type_side .sc_scroll_controls_wrap a {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.8);
}
.sc_scroll_controls_wrap a:hover {
	background-color: '.$clr['accent1_hover'].';
}
.sc_scroll_bar .swiper-scrollbar-drag:before {
	background-color: '.$clr['accent1'].';
}

/* Skills */
.sc_skills_counter .sc_skills_item .sc_skills_icon {
	color: '.$clr['accent1'].';
}
.sc_skills_counter .sc_skills_item:hover .sc_skills_icon {
	color: '.$clr['accent1_hover'].';
}
.sc_skills_bar .sc_skills_item .sc_skills_count {
	border-color: '.$clr['accent2'].';
}

.sc_skills_bar .sc_skills_item .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_3 .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_info {
	background-color: '.$clr['accent2'].';
}

.sc_skills_bar.sc_skills_horizontal .sc_skills_total {
    color: '.$clr['accent1'].';
}

/* Slider */
.sc_slider_controls_wrap a:hover {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}

.sc_slider_swiper .sc_slider_info {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.8) !important;
}
.sc_slider_pagination_over .sc_slider_pagination_wrap span:hover,
.sc_slider_pagination_over .sc_slider_pagination_wrap .swiper-pagination-bullet-active {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}

/* rev slider*/

.rev_slider_wrapper .rev_slider .revbutton1:hover {
}


/* Socials */
.sc_socials.sc_socials_type_icons a:hover,
.scheme_dark .sc_socials.sc_socials_type_icons a:hover,
.scheme_light .sc_socials.sc_socials_type_icons a:hover {
	color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}


/* Tabs */
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.ui-state-active a,
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.sc_tabs_active a,
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li a:hover {
	background-color: '.$clr['accent2'].';
}
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.ui-state-active a:after,
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.sc_tabs_active a:after {
	background-color: '.$clr['accent1'].';
}
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a:hover,
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.ui-state-active a,
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.sc_tabs_active a {
	color: '.$clr['accent1'].';
}



/* Team */
.sc_team_item .sc_team_item_info .sc_team_item_title a:hover {
	color: '.$clr['accent1_hover'].';
}
.sc_team_item .sc_team_item_info .sc_team_item_position {
	color: '.$clr['accent1'].';
}
.sc_team.sc_team_style_team-1 .sc_team_item_info .sc_team_item_position {
    color: '.$clr['accent2'].';
}
.sc_team_style_team-1 .sc_team_item_info,
.sc_team_style_team-3 .sc_team_item_info {
	border-color: '.$clr['accent1'].';
}
.sc_team.sc_team_style_team-3 .sc_team_item_avatar .sc_team_item_hover {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.8);
}
.sc_team.sc_team_style_team-4 .sc_socials_item a:hover {
	color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.sc_team_style_team-4 .sc_team_item_info .sc_team_item_title a:hover {
	color: '.$clr['accent1'].';
}


/* Testimonials */
.sc_testimonials_style_testimonials-3 .sc_testimonial_content p:first-child:before,
.sc_testimonials_style_testimonials-3 .sc_testimonial_author_position {
	color: '.$clr['accent1'].';
}

.sc_testimonials_style_testimonials-4 .sc_testimonial_author_position {
	color: '.$clr['accent1'].';
}
.vc_row.inverse_colors .sc_testimonials_style_testimonials-4 .sc_testimonial_author_name {
    color: '.$clr['accent2'].'!important;
}

/* Title */
.sc_title_icon {
	color: '.$clr['accent1'].';
}
.sc_title_underline::after {
	border-color: '.$clr['accent1'].';
}

/* Toggles */
.sc_toggles .sc_toggles_item .sc_toggles_title.ui-state-active {
	color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.sc_toggles .sc_toggles_item .sc_toggles_title.ui-state-active .sc_toggles_icon_opened {
	background-color: '.$clr['accent1'].';
}
.sc_toggles .sc_toggles_item .sc_toggles_title:hover {
	color: '.$clr['accent1_hover'].';
	border-color: '.$clr['accent1_hover'].';
}
.sc_toggles .sc_toggles_item .sc_toggles_title:hover .sc_toggles_icon_opened {
	background-color: '.$clr['accent1_hover'].';
}


/* Tooltip */
.sc_tooltip_parent .sc_tooltip,
.sc_tooltip_parent .sc_tooltip:before {
	background-color: '.$clr['accent1'].';
}

/* Common styles (title, subtitle and description for some shortcodes) */
.sc_item_subtitle {
	color: '.$clr['accent1'].';
}
.sc_item_title:after {
	background-color: '.$clr['accent1'].';
}
.sc_item_button > a:before {
	color: '.$clr['accent1'].';
}
.sc_item_button > a:hover:before {
	color: '.$clr['accent1_hover'].';
}
';		
		return $custom_style.$css;	
	}
}

// Add skin responsive styles
if (!function_exists('plumbing_parts_action_skin_add_responsive')) {
	//add_action('plumbing_parts_action_add_responsive', 'plumbing_parts_action_skin_add_responsive');
	function plumbing_parts_action_skin_add_responsive() {
		$suffix = plumbing_parts_param_is_off(plumbing_parts_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
		if (file_exists(plumbing_parts_get_file_dir('skin.responsive'.($suffix).'.css'))) 
			plumbing_parts_enqueue_style( 'theme-skin-responsive-style', plumbing_parts_get_file_url('skin.responsive'.($suffix).'.css'), array(), null );
	}
}

// Add skin responsive inline styles
if (!function_exists('plumbing_parts_filter_skin_add_responsive_inline')) {
	//add_filter('plumbing_parts_filter_add_responsive_inline', 'plumbing_parts_filter_skin_add_responsive_inline');
	function plumbing_parts_filter_skin_add_responsive_inline($custom_style) {
		return $custom_style;	
	}
}

// Remove list files for compilation
if (!function_exists('plumbing_parts_filter_skin_compile_less')) {
	//add_filter('plumbing_parts_filter_compile_less', 'plumbing_parts_filter_skin_compile_less');
	function plumbing_parts_filter_skin_compile_less($files) {
		return array();	
	}
}



//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
if (!function_exists('plumbing_parts_action_skin_add_scripts')) {
	//add_action('plumbing_parts_action_add_scripts', 'plumbing_parts_action_skin_add_scripts');
	function plumbing_parts_action_skin_add_scripts() {
		if (file_exists(plumbing_parts_get_file_dir('skin.js')))
			plumbing_parts_enqueue_script( 'theme-skin-script', plumbing_parts_get_file_url('skin.js'), array(), null );
		if (plumbing_parts_get_theme_option('show_theme_customizer') == 'yes' && file_exists(plumbing_parts_get_file_dir('skin.customizer.js')))
			plumbing_parts_enqueue_script( 'theme-skin-customizer-script', plumbing_parts_get_file_url('skin.customizer.js'), array(), null );
	}
}

// Add skin scripts inline
if (!function_exists('plumbing_parts_action_skin_add_scripts_inline')) {
	//add_action('plumbing_parts_action_add_scripts_inline', 'plumbing_parts_action_skin_add_scripts_inline');
	function plumbing_parts_action_skin_add_scripts_inline() {
		// Todo: add skin specific scripts
		// Example:
		// echo '<script type="text/javascript">'
		//	. 'jQuery(document).ready(function() {'
		//	. "if (PLUMBING_PARTS_STORAGE['theme_font']=='') PLUMBING_PARTS_STORAGE['theme_font'] = '" . plumbing_parts_get_custom_font_settings('p', 'font-family') . "';"
		//	. "PLUMBING_PARTS_STORAGE['theme_skin_color'] = '" . plumbing_parts_get_scheme_color('accent1') . "';"
		//	. "});"
		//	. "< /script>";
	}
}
?>