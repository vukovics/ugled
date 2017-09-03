<?php
/**
 * Plumbing Parts Framework: shortcodes manipulations
 *
 * @package	plumbing_parts
 * @since	plumbing_parts 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('plumbing_parts_sc_theme_setup')) {
	add_action( 'plumbing_parts_action_init_theme', 'plumbing_parts_sc_theme_setup', 1 );
	function plumbing_parts_sc_theme_setup() {
		// Add sc stylesheets
		add_action('plumbing_parts_action_add_styles', 'plumbing_parts_sc_add_styles', 1);
	}
}

if (!function_exists('plumbing_parts_sc_theme_setup2')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_sc_theme_setup2' );
	function plumbing_parts_sc_theme_setup2() {

		if ( !is_admin() || isset($_POST['action']) ) {
			// Enable/disable shortcodes in excerpt
			add_filter('the_excerpt', 					'plumbing_parts_sc_excerpt_shortcodes');
	
			// Prepare shortcodes in the content
			if (function_exists('plumbing_parts_sc_prepare_content')) plumbing_parts_sc_prepare_content();
		}

		// Add init script into shortcodes output in VC frontend editor
		add_filter('plumbing_parts_shortcode_output', 'plumbing_parts_sc_add_scripts', 10, 4);

		// AJAX: Send contact form data
		add_action('wp_ajax_send_form',			'plumbing_parts_sc_form_send');
		add_action('wp_ajax_nopriv_send_form',	'plumbing_parts_sc_form_send');

		// Show shortcodes list in admin editor
		add_action('media_buttons',				'plumbing_parts_sc_selector_add_in_toolbar', 11);

	}
}


// Register shortcodes styles
if ( !function_exists( 'plumbing_parts_sc_add_styles' ) ) {
	//add_action('plumbing_parts_action_add_styles', 'plumbing_parts_sc_add_styles', 1);
	function plumbing_parts_sc_add_styles() {
		// Shortcodes
		plumbing_parts_enqueue_style( 'plumbing_parts-shortcodes-style',	plumbing_parts_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
	}
}


// Register shortcodes init scripts
if ( !function_exists( 'plumbing_parts_sc_add_scripts' ) ) {
	//add_filter('plumbing_parts_shortcode_output', 'plumbing_parts_sc_add_scripts', 10, 4);
	function plumbing_parts_sc_add_scripts($output, $tag='', $atts=array(), $content='') {

		if (plumbing_parts_storage_empty('shortcodes_scripts_added')) {
			plumbing_parts_storage_set('shortcodes_scripts_added', true);
			//plumbing_parts_enqueue_style( 'plumbing_parts-shortcodes-style', plumbing_parts_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
			plumbing_parts_enqueue_script( 'plumbing_parts-shortcodes-script', plumbing_parts_get_file_url('shortcodes/theme.shortcodes.js'), array('jquery'), null, true );	
		}
		
		return $output;
	}
}


/* Prepare text for shortcodes
-------------------------------------------------------------------------------- */

// Prepare shortcodes in content
if (!function_exists('plumbing_parts_sc_prepare_content')) {
	function plumbing_parts_sc_prepare_content() {
		if (function_exists('plumbing_parts_sc_clear_around')) {
			$filters = array(
				array('plumbing_parts', 'sc', 'clear', 'around'),
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
			if (function_exists('plumbing_parts_exists_woocommerce') && plumbing_parts_exists_woocommerce()) {
				$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
				$filters[] = array('woocommerce', 'short', 'description');
			}
			if (is_array($filters) && count($filters) > 0) {
				foreach ($filters as $flt)
					add_filter(join('_', $flt), 'plumbing_parts_sc_clear_around', 1);	// Priority 1 to clear spaces before do_shortcodes()
			}
		}
	}
}

// Enable/Disable shortcodes in the excerpt
if (!function_exists('plumbing_parts_sc_excerpt_shortcodes')) {
	function plumbing_parts_sc_excerpt_shortcodes($content) {
		if (!empty($content)) {
			$content = do_shortcode($content);
			//$content = strip_shortcodes($content);
		}
		return $content;
	}
}



/*
// Remove spaces and line breaks between close and open shortcode brackets ][:
[trx_columns]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
[/trx_columns]

convert to

[trx_columns][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][/trx_columns]
*/
if (!function_exists('plumbing_parts_sc_clear_around')) {
	function plumbing_parts_sc_clear_around($content) {
		if (!empty($content)) $content = preg_replace("/\](\s|\n|\r)*\[/", "][", $content);
		return $content;
	}
}






/* Shortcodes support utils
---------------------------------------------------------------------- */

// Plumbing Parts shortcodes load scripts
if (!function_exists('plumbing_parts_sc_load_scripts')) {
	function plumbing_parts_sc_load_scripts() {
		plumbing_parts_enqueue_script( 'plumbing_parts-shortcodes_admin-script', plumbing_parts_get_file_url('core/core.shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
		plumbing_parts_enqueue_script( 'plumbing_parts-selection-script',  plumbing_parts_get_file_url('js/jquery.selection.js'), array('jquery'), null, true );
		wp_localize_script( 'plumbing_parts-shortcodes_admin-script', 'PLUMBING_PARTS_SHORTCODES_DATA', plumbing_parts_storage_get('shortcodes') );
	}
}

// Plumbing Parts shortcodes prepare scripts
if (!function_exists('plumbing_parts_sc_prepare_scripts')) {
	function plumbing_parts_sc_prepare_scripts() {
		if (!plumbing_parts_storage_isset('shortcodes_prepared')) {
			plumbing_parts_storage_set('shortcodes_prepared', true);
			?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					PLUMBING_PARTS_STORAGE['shortcodes_cp'] = '<?php echo is_admin() ? (!plumbing_parts_storage_empty('to_colorpicker') ? plumbing_parts_storage_get('to_colorpicker') : 'wp') : 'custom'; ?>';	// wp | tiny | custom
				});
			</script>
			<?php
		}
	}
}

// Show shortcodes list in admin editor
if (!function_exists('plumbing_parts_sc_selector_add_in_toolbar')) {
	//add_action('media_buttons','plumbing_parts_sc_selector_add_in_toolbar', 11);
	function plumbing_parts_sc_selector_add_in_toolbar(){

		if ( !plumbing_parts_options_is_used() ) return;

		plumbing_parts_sc_load_scripts();
		plumbing_parts_sc_prepare_scripts();

		$shortcodes = plumbing_parts_storage_get('shortcodes');
		$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'.esc_html__('- Select Shortcode -', 'plumbing-parts').'&nbsp;</option>';

		if (is_array($shortcodes) && count($shortcodes) > 0) {
			foreach ($shortcodes as $idx => $sc) {
				$shortcodes_list .= '<option value="'.esc_attr($idx).'" title="'.esc_attr($sc['desc']).'">'.esc_html($sc['title']).'</option>';
			}
		}

		$shortcodes_list .= '</select>';

		plumbing_parts_show_layout($shortcodes_list);
	}
}

// Plumbing Parts shortcodes builder settings
require_once trailingslashit( get_template_directory() ) . PLUMBING_PARTS_FW_DIR . '/core/core.shortcodes/shortcodes_settings.php';



// VC shortcodes settings
if ( class_exists('WPBakeryShortCode') ) {
    require_once trailingslashit( get_template_directory() ) . PLUMBING_PARTS_FW_DIR . '/core/core.shortcodes/shortcodes_vc.php';
}

// Plumbing Parts shortcodes implementation
plumbing_parts_autoload_folder( 'shortcodes/trx_basic' );
plumbing_parts_autoload_folder( 'shortcodes/trx_optional' );
?>