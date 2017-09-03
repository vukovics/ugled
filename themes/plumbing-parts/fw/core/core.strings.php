<?php
/**
 * Plumbing Parts Framework: strings manipulations
 *
 * @package	plumbing_parts
 * @since	plumbing_parts 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'PLUMBING_PARTS_MULTIBYTE' ) ) define( 'PLUMBING_PARTS_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('plumbing_parts_strlen')) {
	function plumbing_parts_strlen($text) {
		return PLUMBING_PARTS_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('plumbing_parts_strpos')) {
	function plumbing_parts_strpos($text, $char, $from=0) {
		return PLUMBING_PARTS_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('plumbing_parts_strrpos')) {
	function plumbing_parts_strrpos($text, $char, $from=0) {
		return PLUMBING_PARTS_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('plumbing_parts_substr')) {
	function plumbing_parts_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = plumbing_parts_strlen($text)-$from;
		}
		return PLUMBING_PARTS_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('plumbing_parts_strtolower')) {
	function plumbing_parts_strtolower($text) {
		return PLUMBING_PARTS_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('plumbing_parts_strtoupper')) {
	function plumbing_parts_strtoupper($text) {
		return PLUMBING_PARTS_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('plumbing_parts_strtoproper')) {
	function plumbing_parts_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<plumbing_parts_strlen($text); $i++) {
			$ch = plumbing_parts_substr($text, $i, 1);
			$rez .= plumbing_parts_strpos(' .,:;?!()[]{}+=', $last)!==false ? plumbing_parts_strtoupper($ch) : plumbing_parts_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('plumbing_parts_strrepeat')) {
	function plumbing_parts_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('plumbing_parts_strshort')) {
	function plumbing_parts_strshort($str, $maxlength, $add='...') {
	//	if ($add && plumbing_parts_substr($add, 0, 1) != ' ')
	//		$add .= ' ';
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= plumbing_parts_strlen($str)) 
			return strip_tags($str);
		$str = plumbing_parts_substr(strip_tags($str), 0, $maxlength - plumbing_parts_strlen($add));
		$ch = plumbing_parts_substr($str, $maxlength - plumbing_parts_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = plumbing_parts_strlen($str) - 1; $i > 0; $i--)
				if (plumbing_parts_substr($str, $i, 1) == ' ') break;
			$str = trim(plumbing_parts_substr($str, 0, $i));
		}
		if (!empty($str) && plumbing_parts_strpos(',.:;-', plumbing_parts_substr($str, -1))!==false) $str = plumbing_parts_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('plumbing_parts_strclear')) {
	function plumbing_parts_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (plumbing_parts_substr($text, 0, plumbing_parts_strlen($open))==$open) {
					$pos = plumbing_parts_strpos($text, '>');
					if ($pos!==false) $text = plumbing_parts_substr($text, $pos+1);
				}
				if (plumbing_parts_substr($text, -plumbing_parts_strlen($close))==$close) $text = plumbing_parts_substr($text, 0, plumbing_parts_strlen($text) - plumbing_parts_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('plumbing_parts_get_slug')) {
	function plumbing_parts_get_slug($title) {
		return plumbing_parts_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('plumbing_parts_strmacros')) {
	function plumbing_parts_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('plumbing_parts_unserialize')) {
	function plumbing_parts_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = @unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			//if ($data===false) $data = @unserialize(str_replace(array("\n", "\r"), array('\\n','\\r'), $str));
			return $data;
		} else
			return $str;
	}
}
?>