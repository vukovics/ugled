<?php
// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Theme init
if (!function_exists('plumbing_parts_importer_theme_setup')) {
	add_action( 'plumbing_parts_action_after_init_theme', 'plumbing_parts_importer_theme_setup' );		// Fire this action after load theme options
	function plumbing_parts_importer_theme_setup() {
		if (is_admin() && current_user_can('import') && plumbing_parts_get_theme_option('admin_dummy_data')=='yes') {
			new plumbing_parts_dummy_data_importer();
		}
	}
}

class plumbing_parts_dummy_data_importer {

	// Theme specific settings
	var $options = array(
		'debug'			=> false,									// Enable debug output
		'demo_style'	=> 2,										// 1 | 2 - Progress bar style when import demo data
		'demo_timeout'	=> 1200,									// Timeframe for PHP scripts when import demo data
		'demo_type'		=> 'default',								// Default demo data type
		'demo_set'		=> 'part',									// full | part - Default demo data set
		'demo_parts'	=> '',										// Comma separated list of the checked items to be imported
		'demo_pages'	=> array(),									// List of the checked pages to be imported
		'demo_url'		=> '',										// URL or local path to the folder with demo data
		'files'			=> array(									// Dummy data files: path to the local file with demo content or URL from external (cloud) server)
			'default' 	=> array(
				'title'				=> '',							// Installation title ('Light version', 'Portfolio style', etc.
				'file_with_'		=> 'name.ext',					// Placeholder of the file with data to create entries of the third-party plugins
				'file_with_posts'	=> 'posts.txt',					// File with posts content
				'file_with_users'	=> 'users.txt',					// File with users
				'file_with_mods'	=> 'theme_mods.txt',			// File with theme mods
				'file_with_options'	=> 'theme_options.txt',			// File with theme options
				'file_with_templates'=>'templates_options.txt',		// File with templates options
				'file_with_widgets' => 'widgets.txt',				// File with widgets data
				'file_with_uploads' => 'uploads.txt',				// File with attachments data: list of the archive's parts or files
				'domain_dev'		=> '',							// Domain on the developer's server 								MUST BE SET IN THE THEME!
				'domain_demo'		=> ''							// Domain on the demo-server										MUST BE SET IN THE THEME!
			)
		),
		'ignore_post_types'		=> array(						// Ignore specified post types when export posts and postmeta
			'revision'
		),
		'plugins_initial_state'	=> 0,							// The initial state of the plugin's checkboxes: 1 - checked, 0 - unchecked		MUST BE SET OR CHANGED IN THEME!
		'taxonomies'			=> array(),						// List of the required taxonomies: 'post_type' => 'taxonomy', ...				MUST BE SET OR CHANGED IN THEME!
		'additional_options'	=> array(						// Additional options slugs (for export plugins settings).						MUST BE SET OR CHANGED IN THEME!
			// WP
			'blogname',
			'blogdescription',
			'posts_per_page',
			'show_on_front',
			'page_on_front',
			'page_for_posts'
		)
	);

	var $error    = '';				// Error message
	var $result   = 0;				// Import posts percent (if break inside)
	
	var $action 	= '';			// Current AJAX action

	var $uploads_url = '';
	var $uploads_dir = '';

	var $start_time = 0;
	var $max_time = 0;
	
	var $part_replace = array();	// List of ID to be replaced after particular import
	var $part_image = array();		// Uploaded no-image.jpg to replace all images on the pages (if 'demo_set' == 'part')

	var	$response = array(
			'action' => '',
			'error' => '',
			'start_from_id' => 0,
			'result' => 100
		);

	//-----------------------------------------------------------------------------------
	// Constuctor
	//-----------------------------------------------------------------------------------
	function __construct() {
		// Add menu item
		add_action('admin_menu', 					array($this, 'admin_menu_item'));
		// Add menu item
		add_action('admin_enqueue_scripts', 		array($this, 'admin_scripts'));
		// AJAX handler
		add_action('wp_ajax_plumbing_parts_importer_start_import',		array($this, 'importer'));
		add_action('wp_ajax_nopriv_plumbing_parts_importer_start_import',	array($this, 'importer'));
		// AJAX handler of the get_list_pages actions
		add_action('wp_ajax_plumbing_parts_importer_get_list_pages',		array($this, 'get_list_pages_callback'));
		add_action('wp_ajax_nopriv_plumbing_parts_importer_get_list_pages',array($this, 'get_list_pages_callback'));
		// Check if row will be imported in the set='part'
		add_filter('plumbing_parts_filter_importer_import_row',			array($this, 'import_check_row'), 9, 4);
	}
	
	function prepare_vars() {
		// Detect current uploads folder and url
		$uploads_info = wp_upload_dir();
		$this->uploads_dir = $uploads_info['basedir'];
		$this->uploads_url = $uploads_info['baseurl'];
		// Filter importer options
		$this->options['debug'] = plumbing_parts_param_is_on(plumbing_parts_get_theme_option('debug_mode'));
	    $this->options = apply_filters('plumbing_parts_filter_importer_options', $this->options);
		// Get allowed execution time
		$this->start_time = time();
		$this->max_time = round( 0.9 * max(30, ini_get('max_execution_time')));
		// Get current percent
		$this->result = isset($_POST['result']) ? $_POST['result'] : 0;
		// Type of demo data
		if (isset($_POST['demo_type']))
			$this->options['demo_type'] = $_POST['demo_type'];
		// Set of the demo data
		if (isset($_POST['demo_set']))
			$this->options['demo_set'] = $_POST['demo_set'];
		// Parts to be imported
		if (isset($_POST['demo_parts']))
			$this->options['demo_parts'] = $_POST['demo_parts'];
		// Pages to be imported
		if (isset($_POST['demo_pages']))
			$this->options['demo_pages'] = explode(',', $_POST['demo_pages']);
	}

	//-----------------------------------------------------------------------------------
	// Admin Interface
	//-----------------------------------------------------------------------------------
	
	// Add menu item
	function admin_menu_item() {
		if ( current_user_can( 'manage_options' ) ) {
			// Add in admin menu 'Theme Options'
			plumbing_parts_admin_add_menu_item('theme', array(
				'page_title' => esc_html__('Install Dummy Data', 'plumbing-parts'),
				'menu_title' => esc_html__('Install Dummy Data', 'plumbing-parts'),
				'capability' => 'manage_options',
				'menu_slug'  => 'trx_importer',
				'callback'   => array($this, 'build_page'),
				'icon'		 => ''
				)
			);
		}
	}
	
	// Add script
	function admin_scripts() {
		wp_enqueue_style(  'plumbing_parts-importer-style',  plumbing_parts_get_file_url('core/core.importer/core.importer.css'), array(), null );
		wp_enqueue_script( 'plumbing_parts-importer-script', plumbing_parts_get_file_url('core/core.importer/core.importer.js'), array('jquery'), null, true );
	}
	
	
	//-----------------------------------------------------------------------------------
	// Build the Main Page
	//-----------------------------------------------------------------------------------
	function build_page() {
		$this->prepare_vars();
		
		// Export data
		if ( isset($_POST['exporter_action']) ) {
			if ( !wp_verify_nonce( plumbing_parts_get_value_gp('nonce'), admin_url() ) )
				$this->error = esc_html__('Incorrect WP-nonce data! Operation canceled!', 'plumbing-parts');
						
			else
				$this->exporter();
		}
		?>

		<div class="trx_importer">

			<div class="trx_importer_section">

				<h2 class="trx_title"><?php esc_html_e('ThemeREX Importer', 'plumbing-parts'); ?></h2>

				<form id="trx_importer_form">

					<?php if (count($this->options['files']) > 1) { ?>
						<p><b><?php esc_html_e('Select the demo-data set to be imported:', 'plumbing-parts'); ?></b></p>
						<div class="trx_importer_demo_type">
							<?php
							foreach ($this->options['files'] as $k=>$v) {
								?><label><input type="radio"<?php if ($this->options['demo_type']==$k) echo ' checked="checked"'; ?> value="<?php echo esc_attr($k); ?>" name="demo_type" /><?php echo esc_html($v['title']); ?></label><br><?php
							}
							?>
						</div>
					<?php } ?>

					<p><b><?php esc_html_e('Select the demo-data set to be imported:', 'plumbing-parts'); ?></b></p>
					<div class="trx_importer_demo_set">
						<label><input type="radio"<?php if ($this->options['demo_set']=='part') echo ' checked="checked"'; ?> value="part" name="demo_set" /><?php esc_html_e('Only pages, forms and sliders', 'plumbing-parts'); ?></label>
						<div class="trx_importer_description trx_importer_description_part<?php if ($this->options['demo_set']!='part') echo ' trx_importer_hidden'; ?>">
							<ol>
								<li><?php echo wp_kses_data(__('<b>Only pages, forms layouts and sliders will be imported in this case.</b>', 'plumbing-parts')); ?></li>
								<li><?php echo wp_kses_data(__('The imported data will be added to the existing content.', 'plumbing-parts')); ?></li>
								<li><?php echo wp_kses_data(__('All the images are replaced with placeholders.', 'plumbing-parts')); ?></li>
								<li><?php echo wp_kses_data(__('The new pages provide sample markup and shall not be included in the menu! You have to do it yourself.', 'plumbing-parts')); ?></li>
								<li><?php echo wp_kses_data(__('Import some components (Revolution sliders, Essential Grids, etc.) take a long time - <b>please wait until the end of the procedure, do not navigate away from this page</b>.', 'plumbing-parts')); ?></li>
							</ol>
						</div>
						<label><input type="radio"<?php if ($this->options['demo_set']=='full') echo ' checked="checked"'; ?> value="full" name="demo_set" /><?php esc_html_e('Whole demo-site content', 'plumbing-parts'); ?></label>
						<div class="trx_importer_description trx_importer_description_full<?php if ($this->options['demo_set']!='full') echo ' trx_importer_hidden'; ?>">
							<ol>
								<li><?php echo wp_kses_data(__('<b>It is recommended for new installations of WordPress</b> (without posts, pages and any other data)!', 'plumbing-parts')); ?></li>
								<li><?php echo wp_kses_data(__('As a result, you will get a new set of posts, pages, menu items - <b>a complete copy of our demo site</b>.', 'plumbing-parts')); ?></li>
								<li><?php echo wp_kses_data(__('Import some components (Revolution sliders, Essential Grids, etc.) take a long time - <b>please wait until the end of the procedure, do not navigate away from this page</b>.', 'plumbing-parts')); ?></li>
							</ol>
						</div>
					</div>

					<p><b><?php esc_html_e('Select the elements to be imported:', 'plumbing-parts'); ?></b></p>

					<?php
					$this->show_importer_params(array(
						'slug' => 'posts',
						'title' => esc_html__('Import posts, pages, taxonomies, etc.', 'plumbing-parts'),
						'part' => 1,
						'checked' => true,
						'class' => 'trx_importer_separator'
					));
					?>
					<div class="trx_importer_part_pages<?php if ($this->options['demo_set']=='full') echo ' trx_importer_hidden"'; ?>">
						<?php
							$pages = $this->get_list_pages_from_demo($this->options['demo_type']);
							if (is_array($pages)) {
								foreach ($pages as $id=>$title) {
									?>
									<label>
										<input class="trx_importer_pages" type="checkbox" value="<?php echo esc_attr($id); ?>" name="import_pages_<?php echo esc_attr($id); ?>" id="import_pages_<?php echo esc_attr($id); ?>" />
										<?php echo esc_html($title); ?>
									</label>
									<?php
								}
							}
						?>
					</div>
					<?php
					$this->show_importer_params(array(
						'slug' => 'uploads',
						'title' => esc_html__('Import media', 'plumbing-parts'),
						'part' => 0,
						'checked' => true,
						'class' => 'trx_importer_separator'
					));
					$this->show_importer_params(array(
						'slug' => 'tm',
						'title' => esc_html__('Import Theme mods', 'plumbing-parts'),
						'part' => 1,
						'checked' => true
					));
					$this->show_importer_params(array(
						'slug' => 'to',
						'title' => esc_html__('Import Theme Options', 'plumbing-parts'),
						'part' => 1,
						'checked' => true
					));
					$this->show_importer_params(array(
						'slug' => 'tpl',
						'title' => esc_html__('Import Templates Options', 'plumbing-parts'),
						'part' => 1,
						'checked' => true
					));
					$this->show_importer_params(array(
						'slug' => 'widgets',
						'title' => esc_html__('Import Widgets', 'plumbing-parts'),
						'part' => 1,
						'checked' => true,
						'class' => 'trx_importer_separator'
					));
					do_action('plumbing_parts_action_importer_params', $this);
					?>
	
					<div class="trx_buttons">
						<input type="button" value="<?php esc_attr_e('Start import', 'plumbing-parts'); ?>">
					</div>
				</form>
				
				<div id="trx_importer_progress" class="notice notice-info style_<?php echo esc_attr($this->options['demo_style']); ?>">
					<h4 class="trx_importer_progress_title"><?php esc_html_e('Import demo data', 'plumbing-parts'); ?></h4>
					<table border="0" cellpadding="4">
					<?php
					$fields = array(
						'posts'		=> esc_html__('Posts', 'plumbing-parts'),
						'uploads'	=> esc_html__('Media', 'plumbing-parts'),
						'tm'		=> esc_html__('Theme Mods', 'plumbing-parts'),
						'to'		=> esc_html__('Theme Options', 'plumbing-parts'),
						'tpl'		=> esc_html__('Template Options', 'plumbing-parts'),
						'widgets'	=> esc_html__('Widgets', 'plumbing-parts'),
					);
					foreach ($fields as $slug=>$title) {
						$this->show_importer_fields(array('slug' => $slug, 'title' => $title));
					}
					do_action('plumbing_parts_action_importer_import_fields', $this);
					?>
					</table>
					<h4 class="trx_importer_progress_complete"><?php esc_html_e('Congratulations! Data import complete!', 'plumbing-parts'); ?> <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('View site', 'plumbing-parts'); ?></a></h4>
				</div>
				
			</div>

			<div class="trx_exporter_section">

				<h2 class="trx_title"><?php esc_html_e('ThemeREX Exporter', 'plumbing-parts'); ?></h2>

				<?php 
				if ($this->error) {
					?><div class="trx_exporter_error notice notice-error"><?php plumbing_parts_show_layout($this->error); ?></div><?php
				}
				?>

				<form id="trx_exporter_form" action="#" method="post">

					<input type="hidden" value="<?php echo esc_attr(wp_create_nonce(admin_url())); ?>" name="nonce" />
					<input type="hidden" value="all" name="exporter_action" />

					<?php 
					if ( isset($_POST['exporter_action']) ) { 
						?><table border="0" cellpadding="6"><?php
						$fields = array(
							'users'			=> esc_html__('Users', 'plumbing-parts'),
							'posts'			=> esc_html__('Posts', 'plumbing-parts'),
							'theme_mods'	=> esc_html__('Theme Mods', 'plumbing-parts'),
							'theme_options'	=> esc_html__('Theme Options', 'plumbing-parts'),
							'templates_options'	=> esc_html__('Templates Options', 'plumbing-parts'),
							'widgets'		=> esc_html__('Widgets', 'plumbing-parts'),
						);
						foreach ($fields as $slug=>$title) {
							$this->show_exporter_fields(array('slug' => $slug, 'title' => $title));
						}
						do_action('plumbing_parts_action_importer_export_fields', $this);
						?></table><?php

					} else {

						if (false && count($this->options['files']) > 1) {

							?><p><b><?php esc_html_e('Select the demo type to be exported', 'plumbing-parts'); ?></b></p><?php

							foreach ($this->options['files'] as $k=>$v) {
								if (!empty($v['file_with_posts'])) {
									?>
									<label><input type="radio"<?php if ($this->options['demo_type']==$k) echo ' checked="checked"'; ?> value="<?php echo esc_attr($k); ?>" name="demo_type" /><?php echo esc_html($v['title']); ?></label>
									<?php
								}
							}
						}
							
						?>
						<div class="trx_buttons">
							<input type="submit" value="<?php esc_attr_e('Export Demo Data', 'plumbing-parts'); ?>">
						</div>
						<?php
					}
					?>
				</form>
			</div>
		</div>
		<?php
	}
	
	// Display importer param's checkbox
	function show_importer_params($args=array()) {
		$args = array_merge(array(
				'slug' => '',
				'title' => '',
				'full' => '1',
				'part' => '0',
				'class' => ''
				), $args);
		?>
		<label<?php if (!empty($args['class'])) echo ' class="'.esc_attr($args['class']).'"'; ?>>
			<input type="checkbox"
					class="trx_importer_item trx_importer_item_<?php echo esc_attr($args['slug']); ?>"
					data-set-full="<?php echo esc_attr($args['full']); ?>"
					data-set-part="<?php echo esc_attr($args['part']); ?>"<?php
					echo (isset($args['checked']) && $args['checked']) || (in_array($args['slug'], plumbing_parts_storage_get('required_plugins')) && $this->options['plugins_initial_state'])
								? ' checked="checked"' 
								: '';
					?>
					value="1"
					name="import_<?php echo esc_attr($args['slug']); ?>"
					id="import_<?php echo esc_attr($args['slug']); ?>" />
			<?php plumbing_parts_show_layout($args['title']); ?>
		</label>
		<?php
	}
	
	// Display importer field's layout
	function show_importer_fields($args=array()) {
		$args = array_merge(array(
				'slug' => '',
				'title' => ''
				), $args);
		?>
		<tr class="import_<?php echo esc_attr($args['slug']); ?>">
			<td class="import_progress_item"><?php plumbing_parts_show_layout($args['title']); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
	
	// Display exporter field's layout
	function show_exporter_fields($args=array()) {
		$args = array_merge(array(
				'slug' => '',
				'title' => ''
				), $args);
		?>
		<tr>
			<th align="left"><?php plumbing_parts_show_layout($args['title']); ?></th>
			<td><a download="<?php echo esc_attr($args['slug']); ?>.txt" href="<?php echo esc_url(plumbing_parts_get_file_url('core/core.importer/export/'.trim($args['slug']).'.txt')); ?>"><?php esc_html_e('Download', 'plumbing-parts'); ?></a></td>
		</tr>
		<?php
	}

	// Check for required plugings
	function check_required_plugins($list='') {
		$not_installed = '';
		if (in_array('trx_utils', plumbing_parts_storage_get('required_plugins')) && !defined('TRX_UTILS_VERSION') )
			$not_installed .= 'Plumbing Parts Utilities';
		$not_installed = apply_filters('plumbing_parts_filter_importer_required_plugins', $not_installed, $list);
		if ($not_installed) {
			$this->error = '<b>'.esc_html__('Attention! For correct installation of the selected demo data, you must install and activate the following plugins: ', 'plumbing-parts').'</b><br>'.($not_installed);
			return false;
		}
		return true;
	}
	
	
	//-----------------------------------------------------------------------------------
	// Export dummy data
	//-----------------------------------------------------------------------------------
	function exporter() {
		global $wpdb;
		$suppress = $wpdb->suppress_errors();

		// Export theme mods
		plumbing_parts_fpc(plumbing_parts_get_file_dir('core/core.importer/export/theme_mods.txt'), serialize($this->prepare_data(get_theme_mods())));

		// Export theme, templates and categories options and VC templates
		$rows = $wpdb->get_results( $wpdb->prepare( 
												"SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s",
												 plumbing_parts_storage_get('options_prefix') . '_options%'
												 )
									);
		$options = array();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = plumbing_parts_unserialize($row->option_value);
			}
		}
		// Export additional options
		if (is_array($this->options['additional_options']) && count($this->options['additional_options']) > 0) {
			foreach ($this->options['additional_options'] as $opt) {
				$rows = $wpdb->get_results( $wpdb->prepare(
														"SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s",
														$opt
														)
											);
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$options[$row->option_name] = plumbing_parts_unserialize($row->option_value);
					}
				}
			}
		}
		plumbing_parts_fpc(plumbing_parts_get_file_dir('core/core.importer/export/theme_options.txt'), serialize($this->prepare_data($options)));

		// Export templates options
		$rows = $wpdb->get_results( $wpdb->prepare(
												"SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s",
												plumbing_parts_storage_get('options_prefix').'_options_template_%'
												)
									);
		$options = array();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = plumbing_parts_unserialize($row->option_value);
			}
		}
		plumbing_parts_fpc(plumbing_parts_get_file_dir('core/core.importer/export/templates_options.txt'), serialize($this->prepare_data($options)));

		// Export widgets
		// Attention! Query below not need to wpdb::prepare because it not using any external variable
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name = 'sidebars_widgets' OR option_name LIKE 'widget_%'" );
		$options = array();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = plumbing_parts_unserialize($row->option_value);
			}
		}
		plumbing_parts_fpc(plumbing_parts_get_file_dir('core/core.importer/export/widgets.txt'), serialize($this->prepare_data($options)));

		// Export posts
		plumbing_parts_fpc(plumbing_parts_get_file_dir('core/core.importer/export/posts.txt'), serialize(array(
			"posts"					=> $this->export_dump("posts"),
			"postmeta"				=> $this->export_dump("postmeta"),
			"comments"				=> $this->export_dump("comments"),
			"commentmeta"			=> $this->export_dump("commentmeta"),
			"terms"					=> $this->export_dump("terms"),
			"termmeta"				=> $this->export_dump("termmeta"),
			"term_taxonomy"			=> $this->export_dump("term_taxonomy"),
			"term_relationships"	=> $this->export_dump("term_relationships")
			)
        ));
		
		// Expost WP Users
		$users = array();
		$rows = $this->export_dump("users");
		if (is_array($rows) && count($rows)>0) {
			foreach ($rows as $k=>$v) {
				$rows[$k]['user_login']	= $rows[$k]['user_nicename'] = 'user'.$v['ID'];
				$rows[$k]['user_pass']		= '';
				$rows[$k]['display_name']	= sprintf(esc_html__('User %d', 'plumbing-parts'), $v['ID']);
				$rows[$k]['user_email']	= 'user'.$v['ID'].'@user-mail.net';
			}
		}
		$users['users'] = $rows;
		$rows = $this->export_dump("usermeta");
		if (is_array($rows) && count($rows)>0) {
			foreach ($rows as $k=>$v) {
				if      ($v['meta_key'] == 'nickname')				$rows[$k]['meta_value'] = 'user'.$v['user_id'];
				else if ($v['meta_key'] == 'first_name')			$rows[$k]['meta_value'] = sprintf(esc_html__('FName%d', 'plumbing-parts'), $v['user_id']);
				else if ($v['meta_key'] == 'last_name')				$rows[$k]['meta_value'] = sprintf(esc_html__('LName%d', 'plumbing-parts'), $v['user_id']);
				else if ($v['meta_key'] == 'billing_first_name')	$rows[$k]['meta_value'] = sprintf(esc_html__('FName%d', 'plumbing-parts'), $v['user_id']);
				else if ($v['meta_key'] == 'billing_last_name')		$rows[$k]['meta_value'] = sprintf(esc_html__('LName%d', 'plumbing-parts'), $v['user_id']);
				else if ($v['meta_key'] == 'billing_email')			$rows[$k]['meta_value'] = 'user'.$v['user_id'].'@user-mail.net';
			}
		}
		$users['usermeta'] = $rows;
		plumbing_parts_fpc(plumbing_parts_get_file_dir('core/core.importer/export/users.txt'), serialize($users));

		// Export Theme specific post types
		do_action('plumbing_parts_action_importer_export', $this);

		$wpdb->suppress_errors( $suppress );
	}
	
	
	//-----------------------------------------------------------------------------------
	// Export specified table
	//-----------------------------------------------------------------------------------
	function export_dump($table) {
		global $wpdb;
		$rows = array();
		if ( count( $wpdb->get_results( $wpdb->prepare( "SHOW TABLES LIKE %s", $wpdb->prefix . trim($table) ), ARRAY_A ) ) == 1 ) {
			$order = $table=='posts' 
						? 'ID' 
						: ($table=='postmeta' 
							? 'meta_id' 
							: ($table=='terms' 
								? 'term_id' 
								: ''));
			
			if ($table=='posts' && count($this->options['ignore_post_types'])>0) {
				$query = $wpdb->prepare(
										"SELECT t.* FROM ".esc_sql($wpdb->prefix.trim($table))." AS t WHERE t.post_type NOT IN (" . join(",", array_fill(0, count($this->options['ignore_post_types']), '%s')) . ")" . ($order ? ' ORDER BY t.' . esc_sql($order) . ' ASC' : ''),
										$this->options['ignore_post_types']
										);
				$rows = $this->prepare_data( $wpdb->get_results( $query, ARRAY_A ) );
			} else {
				$query = "SELECT t.* FROM ".esc_sql($wpdb->prefix.trim($table))." AS t".($order ? ' ORDER BY t.' . esc_sql($order) . ' ASC' : '');
				$rows = $this->prepare_data( $wpdb->get_results( $query, ARRAY_A ) );
			}
			if ($this->options['debug']) dfl(sprintf(__("Export %d rows from table '%s'. Used query: %s", 'plumbing-parts'), count($rows), $table, $query));
		}
		return $rows;
	}
	
	
	//-----------------------------------------------------------------------------------
	// Import dummy data
	//-----------------------------------------------------------------------------------
	//Handler of the add_action('wp_ajax_plumbing_parts_importer_start_import',			array($this, 'importer'));
	//Handler of the add_action('wp_ajax_nopriv_plumbing_parts_importer_start_import',	array($this, 'importer'));
	function importer() {

		if ($this->options['debug']) dfl(__('AJAX handler for importer', 'plumbing-parts'));

		if ( !isset($_POST['importer_action']) || !wp_verify_nonce( plumbing_parts_get_value_gp('ajax_nonce'), admin_url('admin-ajax.php') ) )
			die();
		
		$this->prepare_vars();

		$this->action = $this->response['action'] = $_POST['importer_action'];

		if ($this->options['debug']) dfl( sprintf(__('Dispatch action: %s', 'plumbing-parts'), $this->action) );
		
		global $wpdb;
		$suppress = $wpdb->suppress_errors();

		ob_start();

		// Change max_execution_time (if allowed by server)
		$admin_tm = max(0, min(1800, (int) plumbing_parts_get_theme_option('admin_dummy_timeout')));
		$tm = max(30, (int) ini_get('max_execution_time'));
		if ($tm < $admin_tm) {
			@set_time_limit($admin_tm);
			$this->max_time = round( 0.9 * max(30, ini_get('max_execution_time')));
		}

		// Start import - clear tables, etc.
		if ($this->action == 'import_start') {
			if (!$this->check_required_plugins($this->options['demo_parts']))
				$this->response['error'] = $this->error;
			else
				if (!empty($this->options['demo_parts'])) $this->clear_tables();

		// Import posts and users
		} else if ($this->action == 'import_posts') {
			$this->import_posts();
			if ($this->response['result'] >= 100 && $this->options['demo_set']=='full') {
				$this->import_users();
				do_action('plumbing_parts_action_importer_after_import_posts', $this);
			}

		// Import uploads
		} else if ($this->action == 'import_uploads') {
			$this->import_uploads();

		// Import Theme Mods
		} else if ($this->action == 'import_tm') {
			$this->import_theme_mods();

		// Import Theme Options
		} else if ($this->action == 'import_to') {
			$this->import_theme_options();

		// Import Templates Options
		} else if ($this->action == 'import_tpl') {
			$this->import_templates_options();

		// Import Widgets
		} else if ($this->action == 'import_widgets') {
			$this->import_widgets();

		// End import - clear cache, flush rules, etc.
		} else if ($this->action == 'import_end') {
			wp_cache_delete('category_children', 'options');
			$taxes = get_taxonomies();
			if (is_array($taxes) && count($taxes) > 0) {
				foreach ($taxes  as $tax ) {
					delete_option( "{$tax}_children" );
					_get_term_hierarchy( $tax );
				}
			}
			wp_cache_delete('alloptions', 'options');
			wp_cache_flush();
			flush_rewrite_rules();

		// Import Theme specific posts
		} else {
			do_action('plumbing_parts_action_importer_import', $this, $this->action);
		}

		ob_end_clean();

		$wpdb->suppress_errors($suppress);

		if ($this->options['debug']) dfl( sprintf(__("AJAX handler finished - send results to client: %s", 'plumbing-parts'), json_encode($this->response)) );

		echo json_encode($this->response);
		die();
	}
	
	
	// Delete all data from tables
	function clear_tables() {
		global $wpdb;
		if ($this->options['demo_set']=='full') {
			if (plumbing_parts_strpos($_POST['demo_parts'], 'posts')!==false) {
				if ($this->options['debug']) dfl( __('Clear tables', 'plumbing-parts') );
				$res = $wpdb->query("TRUNCATE TABLE {$wpdb->posts}");
				if ( is_wp_error( $res ) ) dfl( __( 'Failed truncate table "posts".', 'plumbing-parts' ) . ' ' . ($res->get_error_message()) );
				$res = $wpdb->query("TRUNCATE TABLE {$wpdb->postmeta}");
				if ( is_wp_error( $res ) ) dfl( __( 'Failed truncate table "postmeta".', 'plumbing-parts' ) . ' ' . ($res->get_error_message()) );
				$res = $wpdb->query("TRUNCATE TABLE {$wpdb->comments}");
				if ( is_wp_error( $res ) ) dfl( __( 'Failed truncate table "comments".', 'plumbing-parts' ) . ' ' . ($res->get_error_message()) );
				$res = $wpdb->query("TRUNCATE TABLE {$wpdb->commentmeta}");
				if ( is_wp_error( $res ) ) dfl( __( 'Failed truncate table "commentmeta".', 'plumbing-parts' ) . ' ' . ($res->get_error_message()) );
				$res = $wpdb->query("TRUNCATE TABLE {$wpdb->terms}");
				if ( is_wp_error( $res ) ) dfl( __( 'Failed truncate table "terms".', 'plumbing-parts' ) . ' ' . ($res->get_error_message()) );
				$res = $wpdb->query("TRUNCATE TABLE {$wpdb->term_relationships}");
				if ( is_wp_error( $res ) ) dfl( __( 'Failed truncate table "term_relationships".', 'plumbing-parts' ) . ' ' . ($res->get_error_message()) );
				$res = $wpdb->query("TRUNCATE TABLE {$wpdb->term_taxonomy}");
				if ( is_wp_error( $res ) ) dfl( __( 'Failed truncate table "term_taxonomy".', 'plumbing-parts' ) . ' ' . ($res->get_error_message()) );
			}
			do_action('plumbing_parts_action_importer_clear_tables', $this, $_POST['clear_tables']);
		}
	}


	// Import users
	function import_users() {
		if ($this->options['debug']) 
			dfl(__('Import users', 'plumbing-parts'));
		$this->response['start_from_id'] = 0;
		$this->import_dump('users', esc_html__('Users', 'plumbing-parts'));
	}

	// Import posts, terms and comments
	function import_posts() {
		if ($this->options['debug']) 
			dfl(__('Import posts, terms and comments', 'plumbing-parts'));
		$this->response['start_from_id'] = isset($_POST['start_from_id']) ? $_POST['start_from_id'] : 0;
		if ($this->options['demo_set'] == 'part' && $this->response['start_from_id'] == 0) {
			$this->import_prepare_no_image();
		}
		$this->import_dump('posts', esc_html__('Posts', 'plumbing-parts'));
	}

	// Import media (uploads folder)
	function import_uploads() {
		if ($this->options['debug']) 
			dfl(__('Import media', 'plumbing-parts'));
		if (empty($this->options['files'][$this->options['demo_type']]['file_with_uploads'])) return;
		// Get last processed arh
		$last_arh = $this->response['start_from_id'] = isset($_POST['start_from_id']) ? $_POST['start_from_id'] : '';
		// Get list of the files
		if ( ($txt = get_option('plumbing_parts_import_uploads')) == '' ) {
			if ( ($txt = $this->get_file($this->options['files'][$this->options['demo_type']]['file_with_uploads'])) === false)
				return;
			else
				update_option('plumbing_parts_import_uploads', $txt);
		}
		$files = plumbing_parts_unserialize($txt);
		if (!is_array($files)) $files = explode("\n", str_replace("\r\n", "\n", $files));
		// Remove empty lines
		foreach ($files as $k=>$file) {
			if (trim($file)=='') unset($files[$k]);
		}
		// Process files
		$counter = 0;
		$result = 0;
		foreach ($files as $file) {
			$counter++;
			$result = $counter < count($files) ? round($counter / count($files) * 100) : 100;
			if ( ($file = trim($file)) == '' )
				continue;
			if (!empty($last_arh)) {
				if ($file==$last_arh)
					$last_arh = '';
				continue;
			}
			$need_del = false;
			$need_extract = false;
			$need_exit = false;
			$zip = '';
			// Load single file into system temp folder
			if (plumbing_parts_get_file_ext($file)=='zip') {
				if ( ($zip = $this->download_file($file, round(max(0, $counter-1) / count($files) * 100))) === '')
					$need_exit = true;
				else
					$need_del = substr($zip, 0, 5)=='http:' || substr($zip, 0, 6)=='https:';

			// Append next part (*.001, *.002 ...) to archive
			} else if (substr(plumbing_parts_get_file_ext($file), 0, 1)==='0' && (int) plumbing_parts_get_file_ext($file) > 0) {
				if ( ($txt = $this->get_file($file, round(max(0, $counter-1) / count($files) * 100))) === false)
					$need_exit = true;
				else {
					$zip = $this->uploads_dir.'/import_media.tmp';
					plumbing_parts_fpc($zip, $txt, $file==$files[0] ? 0 : FILE_APPEND);
					$need_extract = $need_del = ($counter == count($files));
				}
			}
			// Unrecoverable error is appear
			if ($need_exit) break;
			// Save to log last processed file
			$this->response['start_from_id'] = $file;
			// Check time
			if ($this->options['debug']) 
				dfl(sprintf( __('File %s imported. Current import progress: %s. Time limit: %s sec. Elapsed time: %s sec.', 'plumbing-parts'), $file, $result.'%', $this->max_time, time() - $this->start_time));
			// Unzip file
			if ($need_extract) {
				if (!empty($zip) && file_exists($zip)) {
					if ($this->options['debug']) 
						dfl(sprintf(__('Extract zip-file "%s"', 'plumbing-parts'), $zip));
					if ( !unzip_file($zip, $this->uploads_dir) ) {
						$msg = sprintf(__('Error when unzip file "%s"', 'plumbing-parts'), $zip);
						$this->response['error'] = $msg;
						if ($this->options['debug']) 
							dfl($msg);
					}
					if ($need_del) unlink($zip);
				} else {
					$msg = sprintf(__('File "%s" not found', 'plumbing-parts'), $zip);
					$this->response['error'] = $msg;
					if ($this->options['debug']) 
						dfl($msg);
				}
			}
			// Break import after timeout or if uploads loading from parts - to show percent loading after each part
			//if (time() - $this->start_time >= $this->max_time)
				break;
		}
		if ($result >= 100) update_option('plumbing_parts_import_uploads', '');
		$this->response['result'] = $result;
	}

	// Import theme mods
	function import_theme_mods() {
		if ($this->options['debug']) 
			dfl(__('Import Theme mods', 'plumbing-parts'));
		if ( empty($this->options['files'][$this->options['demo_type']]['file_with_mods']) )
			return;
		if ( ($txt = $this->get_file($this->options['files'][$this->options['demo_type']]['file_with_mods'])) === false )
			return;
		$data = plumbing_parts_unserialize($txt);
		// Replace upload url in options
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				$data[$k] = $this->replace_uploads($v);
			}
			$theme = get_option( 'stylesheet' );
			update_option( "theme_mods_$theme", $data );
		} else {
			if ($this->options['debug'])
				dfl(sprintf(__('Error unserialize data from the file %s', 'plumbing-parts'), $this->options['files'][$this->options['demo_type']]['file_with_mods']));
		}
	}


	// Import theme options
	function import_theme_options() {
		if ($this->options['debug']) 
			dfl(__('Import Theme Options', 'plumbing-parts'));
		if ( empty($this->options['files'][$this->options['demo_type']]['file_with_options']) )
			return;
		if ( ($txt = $this->get_file($this->options['files'][$this->options['demo_type']]['file_with_options'])) === false )
			return;
		$data = plumbing_parts_unserialize($txt);
		// Replace upload url in options
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				$v = $this->replace_uploads($v);
				if ($k == 'mega_main_menu_options' && isset($v['last_modified']))
					$v['last_modified'] = time()+30;
				update_option( $k, apply_filters('plumbing_parts_filter_import_theme_options', $v, $k) );
			}
		} else {
			if ($this->options['debug'])
				dfl(sprintf(__('Error unserialize data from the file %s', 'plumbing-parts'), $this->options['files'][$this->options['demo_type']]['file_with_options']));
		}
	}


	// Import templates options
	function import_templates_options() {
		if ($this->options['debug']) 
			dfl(__('Import Theme Options', 'plumbing-parts'));
		if ( empty($this->options['files'][$this->options['demo_type']]['file_with_templates']) )
			return;
		if ( ($txt = $this->get_file($this->options['files'][$this->options['demo_type']]['file_with_templates'])) === false )
			return;
		$data = plumbing_parts_unserialize($txt);
		// Replace upload url in options
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				update_option( $k, $this->replace_uploads($v) );
			}
		} else {
			if ($this->options['debug'])
				dfl(sprintf(__('Error unserialize data from the file %s', 'plumbing-parts'), $this->options['files'][$this->options['demo_type']]['file_with_options']));
		}
	}


	// Import widgets
	function import_widgets() {
		if ($this->options['debug']) 
			dfl(__('Import Widgets', 'plumbing-parts'));
		if ( empty($this->options['files'][$this->options['demo_type']]['file_with_widgets']) )
			return;
		if ( ($txt = $this->get_file($this->options['files'][$this->options['demo_type']]['file_with_widgets'])) === false )
			return;
		$data = plumbing_parts_unserialize($txt);
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				update_option( $k, $this->replace_uploads($v) );
			}
		} else {
			if ($this->options['debug'])
				dfl(sprintf(__('Error unserialize data from the file %s', 'plumbing-parts'), $this->options['files'][$this->options['demo_type']]['file_with_widgets']));
		}
	}


	// Import any SQL dump
	function import_dump($slug, $title) {
		if ($this->options['debug']) 
			dfl(sprintf(__('Import dump file: "%s"', 'plumbing-parts'), $this->options['files'][$this->options['demo_type']]['file_with_' . $slug]));
		if ( empty($this->options['files'][$this->options['demo_type']]['file_with_' . $slug]) )
			return;
		if ( ($txt = $this->get_file($this->options['files'][$this->options['demo_type']]['file_with_' . $slug])) === false )
			return;
		$data = plumbing_parts_unserialize($txt);
		if (is_array($data) && count($data) > 0) {
			global $wpdb;
			foreach ($data as $table=>$rows) {
				if ($this->options['debug']) dfl(sprintf(__('Process table "%s"', 'plumbing-parts'), $table));
				// Clear table, if it is not 'users' or 'usermeta' and not any posts, terms or comments table
				if ($this->options['demo_set']=='full' && !in_array($table, array('users', 'usermeta')) && $this->action!='import_posts')
					$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix . $table));
				$values = $fields = '';
				$result = 100;
				$break = false;
				// Restore previous state (if import was split on parts)
				if ($this->options['demo_set']=='part' && $table=='posts' && $this->response['start_from_id'] > 0) {
					$this->part_replace = get_option('plumbing_parts_importer_part_replace', array());
					$this->part_image = get_option('plumbing_parts_importer_part_image', array());
				}
				if (is_array($rows) && ($posts_all=count($rows)) > 0) {
					$posts_counter = $posts_imported = 0;
					foreach ($rows as $row) {
						$posts_counter++;
						$result = $posts_counter < $posts_all ? round($posts_counter / $posts_all * 100) : 100;
						// Skip previously imported posts
						if (!empty($row['ID']) && $row['ID'] <= $this->response['start_from_id']) continue;
						// Check if this row will be imported in the set='part'
						if (!apply_filters('plumbing_parts_filter_importer_import_row', $this->options['demo_set']=='full', $table, $row, $this->options['demo_parts'])) continue;
						// Replace demo URL to current site URL
						$row = plumbing_parts_replace_site_url($row, $this->options['files'][$this->options['demo_type']]['domain_demo']);
						$f = '';
						$v = '';
						if (is_array($row) && count($row) > 0) {
							// If 'demo_set' == 'part' - prepare data
							if ($this->options['demo_set']=='part') {
								if ( $table=='posts' ) {
									// Replace images in the post's content
									$row['post_content'] = preg_replace('/(\s+image=["\']\d+["\'])/', ' image="'.esc_attr($this->part_image['id']).'"', $row['post_content']);
									$row['post_content'] = preg_replace('/(\s+url=["\']\d+["\'])/', ' url="'.esc_attr($this->part_image['id']).'"', $row['post_content']);
									$row['post_content'] = preg_replace('/(url\([^\)]+\))/', 'url('.esc_attr($this->part_image['url']).')', $row['post_content']);
									// Replace category in the shortcodes
									$row['post_content'] = preg_replace('/(\s+category=["\']\d+["\'])/', ' category="0"', $row['post_content']);
									$row['post_content'] = preg_replace('/(\s+cat=["\']\d+["\'])/', ' cat="0"', $row['post_content']);
								}
								if ( $table=='postmeta' ) {
									// Replace images in the meta values
									if ($row['meta_key']=='_wpb_shortcodes_custom_css' )
										$row['meta_value'] = preg_replace('/(url\([^\)]+\))/', 'url('.esc_attr($this->part_image['url']).')', $row['meta_value']);
									if ($row['meta_key']=='_thumbnail_id' )
										$row['meta_value'] = $this->part_image['id'];
									// Change post ID in the post meta
									$row['post_id'] = $this->part_replace[$row['post_id']];
								}
							}
							// Merge fields and values to string
							foreach ($row as $field => $value) {
								// If 'demo_set' == 'part' - skip autoincrement fields
								if ($this->options['demo_set']=='part') {
									if ($table=='posts' && $field=='ID') continue;
									if ($table=='postmeta' && $field=='meta_id') continue;
								}
								$f .= ($f ? ',' : '') . esc_sql($field);
								$v .= ($v ? ',' : '') . "'" . esc_sql($value) . "'";
							}
						}
						if ($fields == '') $fields = '(' . trim($f) . ')';
						$values .= ($values ? ',' : '') . '(' . trim($v) . ')';
						// If query length exceed 64K - run query, because MySQL not accept long query string
						// If current table 'users' or 'usermeta' - run queries row by row, because we append data
						if (strlen($values) > 64000 
							|| in_array($table, array('users', 'usermeta')) 
							|| ($this->options['demo_set']=='part' && $table=='posts')) {
							// Attention! All items in the variable $values are escaped in the loop above - esc_sql($value)
							// We can't use wpdb::prepare because we need calculate real query's length (with real values, but not with %s)
							$q = "INSERT INTO ".esc_sql($wpdb->prefix . $table)
									. ($this->options['demo_set']=='part'
										? ' ' . $fields
										: ''
										)
									. " VALUES {$values}";
							$wpdb->query($q);
							$values = $fields = '';
							if ($this->options['demo_set']=='part' && $table=='posts') {
								$this->part_replace[$row['ID']] = $wpdb->insert_id;
								$rez = $wpdb->update( $wpdb->posts, array( 'guid' => get_permalink( $this->part_replace[$row['ID']] ) ), array( 'ID' => $this->part_replace[$row['ID']] ) );
							}
						}
						
						// Save into log last ID
						$this->response['start_from_id'] = isset($row['ID']) ? max($row['ID'], $this->response['start_from_id']) : 0;
						if ($this->options['debug']) {
							dfl( sprintf( __('Record (ID=%s) is imported. Progress: %s. Time: %s sec. from %s sec.', 'plumbing-parts'),
											!empty($row['ID']) 
												? $row['ID'] . ($this->options['demo_set']=='part' 
													? '->' . $this->part_replace[$row['ID']]
													: ''
													)
												: (!empty($row['meta_id']) 
													? $row['meta_id']
													: (!empty($row['term_id']) 
														? $row['term_id']
														: (!empty($row['post_id']) 
															? $row['post_id']
															: ''
															)
														)
													),
											$result.'%',
											time() - $this->start_time,
											$this->max_time
										)
								);
						}
						// Break import after timeout or if leave one post and execution time > half of max_time
						if (time() - $this->start_time >= $this->max_time) {
							$break = true;
							break;
						}
					}
				}
				if (!empty($values)) {
					// Attention! All items in the variable $values are escaped in the loop above - esc_sql($value)
					// We can't use wpdb::prepare because we need calculate real query's length (with real values, but not with %s)
					$q = "INSERT INTO ".esc_sql($wpdb->prefix . $table)
							. ($this->options['demo_set']=='part'
								? ' ' . $fields
								: ''
								)
							. " VALUES {$values}";
					$wpdb->query($q);
				}
				if ($this->options['demo_set']=='part' && $table=='posts') {
					update_option('plumbing_parts_importer_part_replace', $result < 100 ? $this->part_replace : array());
					update_option('plumbing_parts_importer_part_image', $result < 100 ? $this->part_image : array());
				}
				if ($this->options['debug']) dfl(sprintf(__('Imported %s. Elapsed time %s sec. of %s sec.', 'plumbing-parts'), $result.'%', time() - $this->start_time, $this->max_time));
				if ($break) break;
			}
		} else {
			if ($this->options['debug']) 
				dfl(sprintf(__('Error unserialize data from the file %s', 'plumbing-parts'), $this->options['files'][$demo_type]['file_with_' . $slug]));
		}
		$this->response['result'] = $result;
	}
	
	// Check if the row will be imported
	// Handler of the add_filter('plumbing_parts_filter_importer_import_row', array($this, 'import_check_row'), 9, 4);
	function import_check_row($flag, $table, $row, $parts) {
		// If demo_set=='full' or previous handler set flag to true - return true
		if ($flag) return $flag;
		// Else check posts, pages, etc.
		if ($table == 'posts') {
			$flag = $row['post_type']=='page' && in_array($row['ID'], $this->options['demo_pages']);
		} else if ($table == 'postmeta') {
			$flag = !empty($this->part_replace[$row['post_id']]);
		}
		return $flag;
	}
	
	// Copy no-image.jpg to the uploads folder
	function import_prepare_no_image() {
		$no_image_title = esc_html__('No-Image placeholder', 'plumbing-parts');
		$no_image_post = get_page_by_title($no_image_title, OBJECT, 'attachment');
		if ( empty($no_image_post->ID) ) {
			if ( ($img = plumbing_parts_get_file_dir("images/no-image.jpg")) != '') {
				// Copy to the 'uploads' folder
				$this->part_image = wp_upload_bits( 'no-image.jpg', 0, plumbing_parts_fgc($img));
				if (empty($this->part_image['error'])) {
					// Prepare an array of post data for the attachment.
					$attachment = array(
						'guid'           => $this->part_image['url'], 
						'post_mime_type' => $this->part_image['type'],
						'post_title'     => $no_image_title,
						'post_content'   => '',
						'post_status'    => 'publish'
					);
					$this->part_image['id'] = wp_insert_attachment( $attachment, $this->part_image['file'] );
					// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					wp_update_attachment_metadata( $this->part_image['id'], wp_generate_attachment_metadata( $this->part_image['id'], $this->part_image['file'] ) );
				}
			}
		} else {
			$this->part_image = array(
				'id' => $no_image_post->ID,
				'url' => wp_get_attachment_url($no_image_post->ID),
				'file' => '',
				'type' => ''
			);
		}
	}
	
	// Return array with pages id and title from the selected demo
	function get_list_pages_from_demo($demo_type) {
		if ( ($txt = $this->get_file($this->options['files'][$demo_type]['file_with_posts'])) === false )
			return;
		$data = plumbing_parts_unserialize($txt);
		$list = array();
		if (is_array($data) && is_array($data['posts'])) {
			foreach ($data['posts'] as $row) {
				if ($row['post_type'] == 'page') {
					$list[$row['ID']] = $row['post_title'];
				}
			}
		}
		return $list;
	}
	
	// Callback of the get_list_pages action
	function get_list_pages_callback() {
		if ( !wp_verify_nonce( plumbing_parts_get_value_gp('ajax_nonce'), admin_url('admin-ajax.php') ) )
			die();

		$this->prepare_vars();

		$response = array(
			'error' => empty($_POST['demo_type']) ? esc_html__('Incorrect parameters', 'plumbing-parts') : '',
		);

		if (!empty($_POST['demo_type']))
			$response['data'] = $this->get_list_pages_from_demo($_POST['demo_type']);

		echo json_encode($response);
		die();
	}

	
	// Replace uploads dir to new url
	function replace_uploads($str) {
		return plumbing_parts_replace_uploads_url($str);
	}

	
	// Replace strings then export data
	function prepare_data($str) {
		$need_ser = false;
		if (is_string($str) && plumbing_parts_substr($str, 0, 2)=='a:') {
			$str = plumbing_parts_unserialize($str);
			$need_ser = is_array($str);
		}
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = $this->prepare_data($v);
			}
		} else if (is_string($str)) {
			// Replace developers domain to demo domain
			if ($this->options['files'][$this->options['demo_type']]['domain_dev']!=$this->options['files'][$this->options['demo_type']]['domain_demo'])
				$str = str_replace(
							plumbing_parts_get_domain_from_url($this->options['files'][$this->options['demo_type']]['domain_dev']),
							plumbing_parts_get_domain_from_url($this->options['files'][$this->options['demo_type']]['domain_demo']),
							$str);
			// Replace DOS-style line endings to UNIX-style
			$str = str_replace("\r\n", "\n", $str);
		}
		if ($need_ser) $str = serialize($str);
		return $str;
	}

	
	// Return path of the downloaded demo file or false
	function download_file($fname, $result=0) {
		$rez = '';
		$fname = trailingslashit($this->options['demo_url']) . trim($this->options['demo_type']) . '/' . trim($fname);
		// Download remote file
		if (substr($fname, 0, 5)=='http:' || substr($fname, 0, 6)=='https:') {
			$attempt = !empty($_POST['attempt']) ? (int) $_POST['attempt']+1 : 1;
			$response = download_url($fname, $this->max_time);
			if (is_string($response)) {
				$rez = $response;
				unset($this->response['attempt']);
				if ($this->options['debug']) 
					dfl(sprintf(__('Download file %s successful', 'plumbing-parts'), $fname));
			} else {
				if ($attempt < 3) {
					$this->response['attempt'] = $attempt;
					$this->response['result'] = $result;
					if ($this->options['debug']) {
						$error_log = sprintf(__("Attempt %d. Download file '%s' failed.", 'plumbing-parts'), $attempt, $fname);
						dfl($error_log);
					}
				} else {
					unset($this->response['attempt']);
					$this->response['error'] = sprintf(__("Error download file '%s'.", 'plumbing-parts'), $fname)
												. " \n" . __("Please, try again!", 'plumbing-parts');
					if ($this->options['debug']) 
						dfl($this->response['error']);
				}
			}
		} else {
			// File packed with theme
			$rez = $fname;
		}
		return $rez;
	}

	
	// Return content of the downloaded demo file or false
	function get_file($fname, $result=0) {
		$attempt = !empty($_POST['attempt']) ? (int) $_POST['attempt']+1 : 1;
		$fname = trailingslashit($this->options['demo_url']) . trim($this->options['demo_type']) . '/' . trim($fname);
		$txt = plumbing_parts_fgc($fname, true);
		if (empty($txt)) {
			if ($attempt < 3) {
				$this->response['attempt'] = $attempt;
				$this->response['result'] = $result;
				if ($this->options['debug']) {
					$error_log = sprintf(__("Attempt %d. Load data from the file '%s' failed. ", 'plumbing-parts'), $attempt, $fname);
					dfl($error_log);
				}
			} else {
				unset($this->response['attempt']);
				$this->response['error'] = sprintf(__("Error load data from the file '%s'.", 'plumbing-parts'), $fname)
											. " \n" . __("Please, try again!", 'plumbing-parts');
				if ($this->options['debug']) 
					dfl($this->response['error']);
			}
			$txt = false;
		} else {
			unset($this->response['attempt']);
			if ($this->options['debug']) 
				dfl(sprintf(__('Load data from the file %s successful', 'plumbing-parts'), $fname));
		}
		return $txt;
	}
}
?>