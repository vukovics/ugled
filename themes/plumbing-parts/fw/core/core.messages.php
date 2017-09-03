<?php
/**
 * Plumbing Parts Framework: messages subsystem
 *
 * @package	plumbing_parts
 * @since	plumbing_parts 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('plumbing_parts_messages_theme_setup')) {
	add_action( 'plumbing_parts_action_before_init_theme', 'plumbing_parts_messages_theme_setup' );
	function plumbing_parts_messages_theme_setup() {
		// Core messages strings
		add_action('plumbing_parts_action_add_scripts_inline', 'plumbing_parts_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('plumbing_parts_get_error_msg')) {
	function plumbing_parts_get_error_msg() {
		return plumbing_parts_storage_get('error_msg');
	}
}

if (!function_exists('plumbing_parts_set_error_msg')) {
	function plumbing_parts_set_error_msg($msg) {
		$msg2 = plumbing_parts_get_error_msg();
		plumbing_parts_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('plumbing_parts_get_success_msg')) {
	function plumbing_parts_get_success_msg() {
		return plumbing_parts_storage_get('success_msg');
	}
}

if (!function_exists('plumbing_parts_set_success_msg')) {
	function plumbing_parts_set_success_msg($msg) {
		$msg2 = plumbing_parts_get_success_msg();
		plumbing_parts_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('plumbing_parts_get_notice_msg')) {
	function plumbing_parts_get_notice_msg() {
		return plumbing_parts_storage_get('notice_msg');
	}
}

if (!function_exists('plumbing_parts_set_notice_msg')) {
	function plumbing_parts_set_notice_msg($msg) {
		$msg2 = plumbing_parts_get_notice_msg();
		plumbing_parts_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('plumbing_parts_set_system_message')) {
	function plumbing_parts_set_system_message($msg, $status='info', $hdr='') {
		update_option('plumbing_parts_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('plumbing_parts_get_system_message')) {
	function plumbing_parts_get_system_message($del=false) {
		$msg = get_option('plumbing_parts_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			plumbing_parts_del_system_message();
		return $msg;
	}
}

if (!function_exists('plumbing_parts_del_system_message')) {
	function plumbing_parts_del_system_message() {
		delete_option('plumbing_parts_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('plumbing_parts_messages_add_scripts_inline')) {
	function plumbing_parts_messages_add_scripts_inline() {
		echo '<script type="text/javascript">'
			
			. "if (typeof PLUMBING_PARTS_STORAGE == 'undefined') var PLUMBING_PARTS_STORAGE = {};"
			
			// Strings for translation
			. 'PLUMBING_PARTS_STORAGE["strings"] = {'
				. 'ajax_error: 			"' . addslashes(esc_html__('Invalid server answer', 'plumbing-parts')) . '",'
				. 'bookmark_add: 		"' . addslashes(esc_html__('Add the bookmark', 'plumbing-parts')) . '",'
				. 'bookmark_added:		"' . addslashes(esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'plumbing-parts')) . '",'
				. 'bookmark_del: 		"' . addslashes(esc_html__('Delete this bookmark', 'plumbing-parts')) . '",'
				. 'bookmark_title:		"' . addslashes(esc_html__('Enter bookmark title', 'plumbing-parts')) . '",'
				. 'bookmark_exists:		"' . addslashes(esc_html__('Current page already exists in the bookmarks list', 'plumbing-parts')) . '",'
				. 'search_error:		"' . addslashes(esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'plumbing-parts')) . '",'
				. 'email_confirm:		"' . addslashes(esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'plumbing-parts')) . '",'
				. 'reviews_vote:		"' . addslashes(esc_html__('Thanks for your vote! New average rating is:', 'plumbing-parts')) . '",'
				. 'reviews_error:		"' . addslashes(esc_html__('Error saving your vote! Please, try again later.', 'plumbing-parts')) . '",'
				. 'error_like:			"' . addslashes(esc_html__('Error saving your like! Please, try again later.', 'plumbing-parts')) . '",'
				. 'error_global:		"' . addslashes(esc_html__('Global error text', 'plumbing-parts')) . '",'
				. 'name_empty:			"' . addslashes(esc_html__('The name can\'t be empty', 'plumbing-parts')) . '",'
				. 'name_long:			"' . addslashes(esc_html__('Too long name', 'plumbing-parts')) . '",'
				. 'email_empty:			"' . addslashes(esc_html__('Too short (or empty) email address', 'plumbing-parts')) . '",'
				. 'email_long:			"' . addslashes(esc_html__('Too long email address', 'plumbing-parts')) . '",'
				. 'email_not_valid:		"' . addslashes(esc_html__('Invalid email address', 'plumbing-parts')) . '",'
				. 'subject_empty:		"' . addslashes(esc_html__('The subject can\'t be empty', 'plumbing-parts')) . '",'
				. 'subject_long:		"' . addslashes(esc_html__('Too long subject', 'plumbing-parts')) . '",'
				. 'text_empty:			"' . addslashes(esc_html__('The message text can\'t be empty', 'plumbing-parts')) . '",'
				. 'text_long:			"' . addslashes(esc_html__('Too long message text', 'plumbing-parts')) . '",'
				. 'send_complete:		"' . addslashes(esc_html__("Send message complete!", 'plumbing-parts')) . '",'
				. 'send_error:			"' . addslashes(esc_html__('Transmit failed!', 'plumbing-parts')) . '",'
				. 'login_empty:			"' . addslashes(esc_html__('The Login field can\'t be empty', 'plumbing-parts')) . '",'
				. 'login_long:			"' . addslashes(esc_html__('Too long login field', 'plumbing-parts')) . '",'
				. 'login_success:		"' . addslashes(esc_html__('Login success! The page will be reloaded in 3 sec.', 'plumbing-parts')) . '",'
				. 'login_failed:		"' . addslashes(esc_html__('Login failed!', 'plumbing-parts')) . '",'
				. 'password_empty:		"' . addslashes(esc_html__('The password can\'t be empty and shorter then 4 characters', 'plumbing-parts')) . '",'
				. 'password_long:		"' . addslashes(esc_html__('Too long password', 'plumbing-parts')) . '",'
				. 'password_not_equal:	"' . addslashes(esc_html__('The passwords in both fields are not equal', 'plumbing-parts')) . '",'
				. 'registration_success:"' . addslashes(esc_html__('Registration success! Please log in!', 'plumbing-parts')) . '",'
				. 'registration_failed:	"' . addslashes(esc_html__('Registration failed!', 'plumbing-parts')) . '",'
				. 'geocode_error:		"' . addslashes(esc_html__('Geocode was not successful for the following reason:', 'plumbing-parts')) . '",'
				. 'googlemap_not_avail:	"' . addslashes(esc_html__('Google map API not available!', 'plumbing-parts')) . '",'
				. 'editor_save_success:	"' . addslashes(esc_html__("Post content saved!", 'plumbing-parts')) . '",'
				. 'editor_save_error:	"' . addslashes(esc_html__("Error saving post data!", 'plumbing-parts')) . '",'
				. 'editor_delete_post:	"' . addslashes(esc_html__("You really want to delete the current post?", 'plumbing-parts')) . '",'
				. 'editor_delete_post_header:"' . addslashes(esc_html__("Delete post", 'plumbing-parts')) . '",'
				. 'editor_delete_success:	"' . addslashes(esc_html__("Post deleted!", 'plumbing-parts')) . '",'
				. 'editor_delete_error:		"' . addslashes(esc_html__("Error deleting post!", 'plumbing-parts')) . '",'
				. 'editor_caption_cancel:	"' . addslashes(esc_html__('Cancel', 'plumbing-parts')) . '",'
				. 'editor_caption_close:	"' . addslashes(esc_html__('Close', 'plumbing-parts')) . '"'
				. '};'
			
			. '</script>';
	}
}
?>