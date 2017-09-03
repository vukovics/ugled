<?php
/**
 * Plumbing Parts Framework: http queries and data manipulations
 *
 * @package	plumbing_parts
 * @since	plumbing_parts 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Get GET, POST value
if (!function_exists('plumbing_parts_get_value_gp')) {
	function plumbing_parts_get_value_gp($name, $defa='') {
		$rez = $defa;
		if (isset($_GET[$name])) {
			$rez = stripslashes(trim($_GET[$name]));
		} else if (isset($_POST[$name])) {
			$rez = stripslashes(trim($_POST[$name]));
		}
		return $rez;
	}
}


// Get GET, POST, SESSION value and save it (if need)
if (!function_exists('plumbing_parts_get_value_gps')) {
	function plumbing_parts_get_value_gps($name, $defa='', $page='') {
		$putToSession = $page!='';
		$rez = $defa;
		if (isset($_GET[$name])) {
			$rez = stripslashes(trim($_GET[$name]));
		} else if (isset($_POST[$name])) {
			$rez = stripslashes(trim($_POST[$name]));
		} else if (isset($_SESSION[$name.($page!='' ? '_'.($page) : '')])) {
			$rez = stripslashes(trim($_SESSION[$name.($page!='' ? '_'.($page) : '')]));
			$putToSession = false;
		}
		if ($putToSession)
			plumbing_parts_set_session_value($name, $rez, $page);
		return $rez;
	}
}

// Get GET, POST, COOKIE value and save it (if need)
if (!function_exists('plumbing_parts_get_value_gpc')) {
	function plumbing_parts_get_value_gpc($name, $defa='', $page='', $exp=0) {
		$putToCookie = $page!='';
		$rez = $defa;
		if (isset($_GET[$name])) {
			$rez = stripslashes(trim($_GET[$name]));
		} else if (isset($_POST[$name])) {
			$rez = stripslashes(trim($_POST[$name]));
		} else if (isset($_COOKIE[$name.($page!='' ? '_'.($page) : '')])) {
			$rez = stripslashes(trim($_COOKIE[$name.($page!='' ? '_'.($page) : '')]));
			$putToCookie = false;
		}
		if ($putToCookie)
			setcookie($name.($page!='' ? '_'.($page) : ''), $rez, $exp, '/');
		return $rez;
	}
}

// Save value into session
if (!function_exists('plumbing_parts_set_session_value')) {
	function plumbing_parts_set_session_value($name, $value, $page='') {
		if (!session_id()) session_start();
		$_SESSION[$name.($page!='' ? '_'.($page) : '')] = $value;
	}
}

// Save value into session
if (!function_exists('plumbing_parts_del_session_value')) {
	function plumbing_parts_del_session_value($name, $page='') {
		if (!session_id()) session_start();
		unset($_SESSION[$name.($page!='' ? '_'.($page) : '')]);
	}
}


/* Other functions
-------------------------------------------------------------------------------- */

// Return current site protocol
if (!function_exists('plumbing_parts_get_protocol')) {
	function plumbing_parts_get_protocol() {
		return is_ssl() ? 'https' : 'http';
	}
}
	
//  Cache disable
if (!function_exists('plumbing_parts_http_cache_disable')) {
	function plumbing_parts_http_cache_disable() {
		Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		Header("Cache-Control: no-cache, must-revalidate");
		Header("Pragma: no-cache");
		Header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	}
}
?>