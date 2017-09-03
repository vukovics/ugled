<?php
/**
 * Plumbing Parts Framework: ini-files manipulations
 *
 * @package	plumbing_parts
 * @since	plumbing_parts 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


//  Get value by name from .ini-file
if (!function_exists('plumbing_parts_ini_get_value')) {
	function plumbing_parts_ini_get_value($file, $name, $defa='') {
		if (!is_array($file)) {
			if (file_exists($file)) {
				$file = plumbing_parts_fga($file);
			} else
				return $defa;
		}
		$name = plumbing_parts_strtolower($name);
		$rez = $defa;
		for ($i=0; $i<count($file); $i++) {
			$file[$i] = trim($file[$i]);
			if (($pos = plumbing_parts_strpos($file[$i], ';'))!==false)
				$file[$i] = trim(plumbing_parts_substr($file[$i], 0, $pos));
			$parts = explode('=', $file[$i]);
			if (count($parts)!=2) continue;
			if (plumbing_parts_strtolower(trim(chop($parts[0])))==$name) {
				$rez = trim(chop($parts[1]));
				if (plumbing_parts_substr($rez, 0, 1)=='"')
					$rez = plumbing_parts_substr($rez, 1, plumbing_parts_strlen($rez)-2);
				else
					$rez *= 1;
				break;
			}
		}
		return $rez;
	}
}

//  Retrieve all values from .ini-file as assoc array
if (!function_exists('plumbing_parts_ini_get_values')) {
	function plumbing_parts_ini_get_values($file) {
		$rez = array();
		if (!is_array($file)) {
			if (file_exists($file)) {
				$file = plumbing_parts_fga($file);
			} else
				return $rez;
		}
		for ($i=0; $i<count($file); $i++) {
			$file[$i] = trim(chop($file[$i]));
			if (($pos = plumbing_parts_strpos($file[$i], ';'))!==false)
				$file[$i] = trim(plumbing_parts_substr($file[$i], 0, $pos));
			$parts = explode('=', $file[$i]);
			if (count($parts)!=2) continue;
			$key = trim(chop($parts[0]));
			$rez[$key] = trim($parts[1]);
			if (plumbing_parts_substr($rez[$key], 0, 1)=='"')
				$rez[$key] = plumbing_parts_substr($rez[$key], 1, plumbing_parts_strlen($rez[$key])-2);
			else
				$rez[$key] *= 1;
		}
		return $rez;
	}
}
?>