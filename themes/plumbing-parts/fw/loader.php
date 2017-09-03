<?php
/**
 * Plumbing Parts Framework
 *
 * @package plumbing_parts
 * @since plumbing_parts 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'PLUMBING_PARTS_FW_DIR' ) )			define( 'PLUMBING_PARTS_FW_DIR', 'fw' );
if ( ! defined( 'PLUMBING_PARTS_THEME_PATH' ) )	define( 'PLUMBING_PARTS_THEME_PATH',	trailingslashit( get_template_directory() ) );
if ( ! defined( 'PLUMBING_PARTS_FW_PATH' ) )		define( 'PLUMBING_PARTS_FW_PATH',		PLUMBING_PARTS_THEME_PATH . PLUMBING_PARTS_FW_DIR . '/' );

// Include theme variables storage
require_once trailingslashit( get_template_directory() ) . PLUMBING_PARTS_FW_DIR . '/core/core.storage.php';

// Theme variables storage
//$theme_slug = str_replace(' ', '_', trim(strtolower(get_stylesheet())));
//plumbing_parts_storage_set('options_prefix', 'plumbing_parts'.'_'.trim($theme_slug));	// Used as prefix to store theme's options in the post meta and wp options
plumbing_parts_storage_set('options_prefix', 'plumbing_parts');	// Used as prefix to store theme's options in the post meta and wp options
plumbing_parts_storage_set('page_template', '');			// Storage for current page template name (used in the inheritance system)
plumbing_parts_storage_set('widgets_args', array(			// Arguments to register widgets
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5 class="widget_title">',
		'after_title'   => '</h5>',
	)
);

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'plumbing_parts_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'plumbing_parts_loader_theme_setup', 20 );
	function plumbing_parts_loader_theme_setup() {

		plumbing_parts_profiler_add_point(esc_html__('After load theme required files', 'plumbing-parts'));

		// Before init theme
		do_action('plumbing_parts_action_before_init_theme');

		// Load current values for main theme options
		plumbing_parts_load_main_options();

		// Theme core init - only for admin side. In frontend it called from header.php
		if ( is_admin() ) {
			plumbing_parts_core_init_theme();
		}
	}
}


/* Include core parts
------------------------------------------------------------------------ */
// Manual load important libraries before load all rest files
// core.strings must be first - we use plumbing_parts_str...() in the plumbing_parts_get_file_dir()
require_once trailingslashit( get_template_directory() ) . PLUMBING_PARTS_FW_DIR . '/core/core.strings.php';
// core.files must be first - we use plumbing_parts_get_file_dir() to include all rest parts
require_once trailingslashit( get_template_directory() ) . PLUMBING_PARTS_FW_DIR . '/core/core.files.php';

// Include debug and profiler
require_once trailingslashit( get_template_directory() ) . PLUMBING_PARTS_FW_DIR . '/core/core.debug.php';

require_once PLUMBING_PARTS_FW_PATH . 'core/core.options/core.options.php';
require_once PLUMBING_PARTS_FW_PATH . 'core/core.importer/core.importer.php';

// Include custom theme files
plumbing_parts_autoload_folder( 'includes' );

// Include core files
plumbing_parts_autoload_folder( 'core' );

// Include theme-specific plugins and post types
plumbing_parts_autoload_folder( 'plugins' );

// Include theme templates
plumbing_parts_autoload_folder( 'templates' );

// Include theme widgets
plumbing_parts_autoload_folder( 'widgets' );
?>