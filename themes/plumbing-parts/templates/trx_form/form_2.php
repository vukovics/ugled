<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'plumbing_parts_template_form_2_theme_setup' ) ) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_template_form_2_theme_setup', 1 );
	function plumbing_parts_template_form_2_theme_setup() {
		plumbing_parts_add_template(array(
			'layout' => 'form_2',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 2', 'plumbing-parts')
			));
	}
}

// Template output
if ( !function_exists( 'plumbing_parts_template_form_2_output' ) ) {
	function plumbing_parts_template_form_2_output($post_options, $post_data) {
		$address_1 = plumbing_parts_get_theme_option('contact_address_1');
		$address_2 = plumbing_parts_get_theme_option('contact_address_2');
		$phone = plumbing_parts_get_theme_option('contact_phone');
		$fax = plumbing_parts_get_theme_option('contact_fax');
		$email = plumbing_parts_get_theme_option('contact_email');
		$open_hours = plumbing_parts_get_theme_option('contact_open_hours');
		?>
		<div class="sc_columns columns_wrap">
            <div class="sc_form_fields column-1_2">
                <div class="contact_form_filds">
                    <h3><?php esc_html_e('Say Hello', 'plumbing-parts'); ?></h3>
                    <form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'_form"' : ''; ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
                        <?php plumbing_parts_sc_form_show_fields($post_options['fields']); ?>
                        <div class="sc_form_info">
                            <div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_username"><?php esc_html_e('Name', 'plumbing-parts'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Name *', 'plumbing-parts'); ?>"></div>
                            <div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_email"><?php esc_html_e('E-mail', 'plumbing-parts'); ?></label><input id="sc_form_email" type="text" name="email" placeholder="<?php esc_attr_e('E-mail *', 'plumbing-parts'); ?>"></div>
                        </div>
                        <div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php esc_html_e('Message', 'plumbing-parts'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Message', 'plumbing-parts'); ?>"></textarea></div>
                        <div class="sc_form_item sc_form_button"><button><?php esc_html_e('Send Message', 'plumbing-parts'); ?></button></div>
                        <div class="result sc_infobox"></div>
                    </form>
                </div>
            </div>
			<div class="sc_form_address column-1_2">
                <div class="contact_info">
                    <h3><?php esc_html_e('Contacts', 'plumbing-parts'); ?></h3>
                    <div class="sc_form_address_field">
                        <span class="sc_form_address_label"><?php esc_html_e('Address', 'plumbing-parts'); ?></span>
					<span class="sc_form_address_data"><?php plumbing_parts_show_layout($address_1 . (!empty($address_1) && !empty($address_2) ? ', ' : '') . $address_2); ?></span>
                    </div>
                    <div class="sc_form_address_field">
                        <span class="sc_form_address_label"><?php esc_html_e('Phone number:', 'plumbing-parts'); ?></span>
                        <span class="sc_form_address_data"><?php plumbing_parts_show_layout($phone) . (!empty($phone) && !empty($fax) ? ', ' : '') . $fax; ?></span>
                    </div>
                    <div class="sc_form_address_field">
                        <span class="sc_form_address_label"><?php esc_html_e('Mail:', 'plumbing-parts'); ?></span>
                        <span class="sc_form_address_data"><?php plumbing_parts_show_layout($email); ?></span>
                    </div>
                    <div class="sc_form_address_field">
                        <span class="sc_form_address_label"><?php esc_html_e('We are open', 'plumbing-parts'); ?></span>
                        <span class="sc_form_address_data"><?php plumbing_parts_show_layout($open_hours); ?></span>
                    </div>
                </div>
			</div>

		</div>
		<?php
	}
}
?>