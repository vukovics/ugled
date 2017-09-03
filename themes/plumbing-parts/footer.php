<?php
/**
 * The template for displaying the footer.
 */

				plumbing_parts_close_wrapper();	// <!-- </.content> -->

				plumbing_parts_profiler_add_point(esc_html__('After Page content', 'plumbing-parts'));
	
				// Show main sidebar
				get_sidebar();

				if (plumbing_parts_get_custom_option('body_style')!='fullscreen') plumbing_parts_close_wrapper();	// <!-- </.content_wrap> -->
				?>
			
			</div>		<!-- </.page_content_wrap> -->
			
			<?php
			// Footer Testimonials stream
            require_once plumbing_parts_get_file_dir('templates/_parts/footer-testimonials.php');
			
			// Footer sidebar
            require_once plumbing_parts_get_file_dir('templates/_parts/footer-sidebar.php');

			// Footer Twitter stream
            require_once plumbing_parts_get_file_dir('templates/_parts/footer-twitter.php');

			// Google map
            require_once plumbing_parts_get_file_dir('templates/_parts/show-google-map.php');

			// Footer contacts
            require_once plumbing_parts_get_file_dir('templates/_parts/footer-contacts.php');

			// Copyright area
            require_once plumbing_parts_get_file_dir('templates/_parts/footer-copyright-area.php');





			plumbing_parts_profiler_add_point(esc_html__('After Footer', 'plumbing-parts'));
			?>
			
		</div>	<!-- /.page_wrap -->

	</div>		<!-- /.body_wrap -->
	
	<?php if ( !plumbing_parts_param_is_off(plumbing_parts_get_custom_option('show_sidebar_outer')) ) { ?>
	</div>	<!-- /.outer_wrap -->
	<?php } ?>

<?php
// Post/Page views counter
get_template_part(plumbing_parts_get_file_slug('templates/_parts/views-counter.php'));

// Login/Register
if (plumbing_parts_get_theme_option('show_login')=='yes') {
	plumbing_parts_enqueue_popup();
	// Anyone can register ?
	if ( (int) get_option('users_can_register') > 0) {
		get_template_part(plumbing_parts_get_file_slug('templates/_parts/popup-register.php'));
	}
	get_template_part(plumbing_parts_get_file_slug('templates/_parts/popup-login.php'));
}

// Front customizer
if (plumbing_parts_get_custom_option('show_theme_customizer')=='yes') {
	get_template_part(plumbing_parts_get_file_slug('core/core.customizer/front.customizer.php'));
}
?>

<a href="#" class="scroll_to_top icon-up" title="<?php esc_attr_e('Scroll to top', 'plumbing-parts'); ?>"></a>

<div class="custom_html_section">
<?php echo force_balance_tags(plumbing_parts_get_custom_option('custom_code')); ?>
</div>

<?php
echo force_balance_tags(plumbing_parts_get_custom_option('gtm_code2'));

plumbing_parts_profiler_add_point(esc_html__('After Theme HTML output', 'plumbing-parts'));

wp_footer(); 
?>

</body>
</html>