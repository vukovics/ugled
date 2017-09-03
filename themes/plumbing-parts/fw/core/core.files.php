<?php
/**
 * Plumbing Parts Framework: file system manipulations, styles and scripts usage, etc.
 *
 * @package	plumbing_parts
 * @since	plumbing_parts 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* File system utils
------------------------------------------------------------------------------------- */

// Return list folders inside specified folder in the child theme dir (if exists) or main theme dir
if (!function_exists('plumbing_parts_get_list_folders')) {	
	function plumbing_parts_get_list_folders($folder, $only_names=true) {
		$dir = plumbing_parts_get_folder_dir($folder);
		$url = plumbing_parts_get_folder_url($folder);
		$list = array();
		if ( is_dir($dir) ) {
			$hdir = @opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					if ( substr($file, 0, 1) == '.' || !is_dir( ($dir) . '/' . ($file) ) )
						continue;
					$key = $file;
					$list[$key] = $only_names ? plumbing_parts_strtoproper($key) : ($url) . '/' . ($file);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}

// Return list files in folder
if (!function_exists('plumbing_parts_get_list_files')) {	
	function plumbing_parts_get_list_files($folder, $ext='', $only_names=false) {
		$dir = plumbing_parts_get_folder_dir($folder);
		$url = plumbing_parts_get_folder_url($folder);
		$list = array();
		if ( is_dir($dir) ) {
			$hdir = @opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( ($dir) . '/' . ($file) );
					if ( substr($file, 0, 1) == '.' || is_dir( ($dir) . '/' . ($file) ) || (!empty($ext) && $pi['extension'] != $ext) )
						continue;
					$key = plumbing_parts_substr($file, 0, plumbing_parts_strrpos($file, '.'));
					if (plumbing_parts_substr($key, -4)=='.min') $key = plumbing_parts_substr($file, 0, plumbing_parts_strrpos($key, '.'));
					$list[$key] = $only_names ? plumbing_parts_strtoproper(str_replace('_', ' ', $key)) : ($url) . '/' . ($file);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}

// Return list files in subfolders
if (!function_exists('plumbing_parts_collect_files')) {	
	function plumbing_parts_collect_files($dir, $ext=array()) {
		if (!is_array($ext)) $ext = array($ext);
		if (plumbing_parts_substr($dir, -1)=='/') $dir = plumbing_parts_substr($dir, 0, plumbing_parts_strlen($dir)-1);
		$list = array();
		if ( is_dir($dir) ) {
			$hdir = @opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( $dir . '/' . $file );
					if ( substr($file, 0, 1) == '.' )
						continue;
					if ( is_dir( $dir . '/' . $file ))
						$list = array_merge($list, plumbing_parts_collect_files($dir . '/' . $file, $ext));
					else if (empty($ext) || in_array($pi['extension'], $ext))
						$list[] = $dir . '/' . $file;
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}

// Return path to directory with uploaded images
if (!function_exists('plumbing_parts_get_uploads_dir_from_url')) {	
	function plumbing_parts_get_uploads_dir_from_url($url) {
		$upload_info = wp_upload_dir();
		$upload_dir = $upload_info['basedir'];
		$upload_url = $upload_info['baseurl'];
		
		$http_prefix = "http://";
		$https_prefix = "https://";
		
		if (!strncmp($url, $https_prefix, plumbing_parts_strlen($https_prefix)))			//if url begins with https:// make $upload_url begin with https:// as well
			$upload_url = str_replace($http_prefix, $https_prefix, $upload_url);
		else if (!strncmp($url, $http_prefix, plumbing_parts_strlen($http_prefix)))		//if url begins with http:// make $upload_url begin with http:// as well
			$upload_url = str_replace($https_prefix, $http_prefix, $upload_url);		
	
		// Check if $img_url is local.
		if ( false === plumbing_parts_strpos( $url, $upload_url ) ) return false;
	
		// Define path of image.
		$rel_path = str_replace( $upload_url, '', $url );
		$img_path = ($upload_dir) . ($rel_path);
		
		return $img_path;
	}
}

// Replace uploads url to current site uploads url
if (!function_exists('plumbing_parts_replace_uploads_url')) {	
	function plumbing_parts_replace_uploads_url($str, $uploads_folder='uploads') {
		static $uploads_url = '', $uploads_len = 0;
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = plumbing_parts_replace_uploads_url($v, $uploads_folder);
			}
		} else if (is_string($str)) {
			if (empty($uploads_url)) {
				$uploads_info = wp_upload_dir();
				$uploads_url = $uploads_info['baseurl'];
				$uploads_len = plumbing_parts_strlen($uploads_url);
			}
			$break = '\'" ';
			$pos = 0;
			while (($pos = plumbing_parts_strpos($str, "/{$uploads_folder}/", $pos))!==false) {
				$pos0 = $pos;
				$chg = true;
				while ($pos0) {
					if (plumbing_parts_strpos($break, plumbing_parts_substr($str, $pos0, 1))!==false) {
						$chg = false;
						break;
					}
					if (plumbing_parts_substr($str, $pos0, 5)=='http:' || plumbing_parts_substr($str, $pos0, 6)=='https:')
						break;
					$pos0--;
				}
				if ($chg) {
					$str = ($pos0 > 0 ? plumbing_parts_substr($str, 0, $pos0) : '') . ($uploads_url) . plumbing_parts_substr($str, $pos+plumbing_parts_strlen($uploads_folder)+1);
					$pos = $pos0 + $uploads_len;
				} else 
					$pos++;
			}
		}
		return $str;
	}
}

// Return file extension from full name/path
if (!function_exists('plumbing_parts_get_file_ext')) {
	function plumbing_parts_get_file_ext($file) {
		$parts = pathinfo($file);
		return $parts['extension'];
	}
}

// Replace site url to current site url
if (!function_exists('plumbing_parts_replace_site_url')) {	
	function plumbing_parts_replace_site_url($str, $old_url) {
		static $site_url = '', $site_len = 0;
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = plumbing_parts_replace_site_url($v, $old_url);
			}
		} else if (is_string($str)) {
			if (empty($site_url)) {
				$site_url = get_site_url();
				$site_len = plumbing_parts_strlen($site_url);
				if (plumbing_parts_substr($site_url, -1)=='/') {
					$site_len--;
					$site_url = plumbing_parts_substr($site_url, 0, $site_len);
				}
			}
			if (plumbing_parts_substr($old_url, -1)=='/') $old_url = plumbing_parts_substr($old_url, 0, plumbing_parts_strlen($old_url)-1);
			$break = '\'" ';
			$pos = 0;
			while (($pos = plumbing_parts_strpos($str, $old_url, $pos))!==false) {
				$str = plumbing_parts_unserialize($str);
				if (is_array($str) && count($str) > 0) {
					foreach ($str as $k=>$v) {
						$str[$k] = plumbing_parts_replace_site_url($v, $old_url);
					}
					$str = serialize($str);
					break;
				} else {
					$pos0 = $pos;
					$chg = true;
					while ($pos0 >= 0) {
						if (plumbing_parts_strpos($break, plumbing_parts_substr($str, $pos0, 1))!==false) {
							$chg = false;
							break;
						}
						if (plumbing_parts_substr($str, $pos0, 5)=='http:' || plumbing_parts_substr($str, $pos0, 6)=='https:')
							break;
						$pos0--;
					}
					if ($chg && $pos0>=0) {
						$str = ($pos0 > 0 ? plumbing_parts_substr($str, 0, $pos0) : '') . ($site_url) . plumbing_parts_substr($str, $pos+plumbing_parts_strlen($old_url));
						$pos = $pos0 + $site_len;
					} else 
						$pos++;
				}
			}
		}
		return $str;
	}
}


// Autoload templates, widgets, etc.
// Scan subfolders and require file with same name in each folder
if (!function_exists('plumbing_parts_autoload_folder')) {	
	function plumbing_parts_autoload_folder($folder, $from_subfolders=true, $from_skin=true) {
		static $skin_dir = '';
		if ($folder[0]=='/') $folder = plumbing_parts_substr($file, 1);
		if ($from_skin && empty($skin_dir) && function_exists('plumbing_parts_get_custom_option')) {
			$skin_dir = plumbing_parts_esc(plumbing_parts_get_custom_option('theme_skin'));
			if ($skin_dir) $skin_dir  = 'skins/'.($skin_dir);
		} else
			$skin_dir = '-no-skins-';
		$theme_dir = get_template_directory();
		$child_dir = get_stylesheet_directory();
		$dirs = array(
			($child_dir).'/'.($skin_dir).'/'.($folder),
			($theme_dir).'/'.($skin_dir).'/'.($folder),
			($child_dir).'/'.($folder),
			($theme_dir).'/'.($folder),
			($child_dir).'/'.(PLUMBING_PARTS_FW_DIR).'/'.($folder),
			($theme_dir).'/'.(PLUMBING_PARTS_FW_DIR).'/'.($folder)
		);
		$loaded = array();
		foreach ($dirs as $dir) {
			if ( is_dir($dir) ) {
				$hdir = @opendir( $dir );
				if ( $hdir ) {
					$files = array();
					$folders = array();
					while ( ($file = readdir($hdir)) !== false ) {
						if (substr($file, 0, 1) == '.' || in_array($file, $loaded))
							continue;
						if ( is_dir( ($dir) . '/' . ($file) ) ) {
							if ($from_subfolders && file_exists( ($dir) . '/' . ($file) . '/' . ($file) . '.php' ) ) {
								$folders[] = $file;
							}
						} else {
							$files[] = $file;
						}
					}
					@closedir( $hdir );
					// Load sorted files
					if ( count($files) > 0) {
						sort($files);
						foreach ($files as $file) {
							$loaded[] = $file;
							require_once ($dir) . '/' . ($file);
						}
					}
					// Load sorted subfolders
					if ( count($folders) > 0) {
						sort($folders);
						foreach ($folders as $file) {
							$loaded[] = $file;
							require_once ($dir) . '/' . ($file) . '/' . ($file) . '.php';
						}
					}
				}
			}
		}
	}
}



/* File system utils
------------------------------------------------------------------------------------- */

// Put text into specified file
if (!function_exists('plumbing_parts_fpc')) {	
	function plumbing_parts_fpc($file, $data, $flag=0) {
		global $wp_filesystem;
		if (!empty($file)) {
			if (isset($wp_filesystem) && is_object($wp_filesystem)) {
				$file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
				// Attention! WP_Filesystem can't append the content to the file!
				// That's why we have to read the contents of the file into a string,
				// add new content to this string and re-write it to the file if parameter $flag == FILE_APPEND!
				return $wp_filesystem->put_contents($file, ($flag==FILE_APPEND ? $wp_filesystem->get_contents($file) : '') . $data, false);
			} else {
				if (plumbing_parts_param_is_on(plumbing_parts_get_theme_option('debug_mode')))
					throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Put contents to the file "%s" failed', 'plumbing-parts'), $file));
			}
		}
		return false;
	}
}

// Get text from specified file
if (!function_exists('plumbing_parts_fgc')) {	
	function plumbing_parts_fgc($file) {
        static $allow_url_fopen = -1;
        if ($allow_url_fopen==-1) $allow_url_fopen = (int) ini_get('allow_url_fopen');
		global $wp_filesystem;
		if (!empty($file)) {
			if (isset($wp_filesystem) && is_object($wp_filesystem)) {
				$file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
                return !$allow_url_fopen && strpos($file, '//')!==false 
                                ? plumbing_parts_remote_get($file) 
                                : $wp_filesystem->get_contents($file);
			} else {
				if (plumbing_parts_param_is_on(plumbing_parts_get_theme_option('debug_mode')))
					throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Get contents from the file "%s" failed', 'plumbing-parts'), $file));
			}
		}
			return '';
	}
}

// Get array with rows from specified file
if (!function_exists('plumbing_parts_fga')) {	
	function plumbing_parts_fga($file) {
		global $wp_filesystem;
		if (!empty($file)) {
			if (isset($wp_filesystem) && is_object($wp_filesystem)) {
				$file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
				return $wp_filesystem->get_contents_array($file);
			} else {
				if (plumbing_parts_param_is_on(plumbing_parts_get_theme_option('debug_mode')))
					throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Get rows from the file "%s" failed', 'plumbing-parts'), $file));
			}
		}
		return array();
	}
}

// Get text from specified file (local or remote)
if (!function_exists('plumbing_parts_get_local_or_remote_file')) {	
	function plumbing_parts_get_local_or_remote_file($file) {
		$rez = '';
		if (substr($file, 0, 5)=='http:' || substr($file, 0, 6)=='https:') {
			$tm = round( 0.9 * max(30, ini_get('max_execution_time')));
			$response = wp_remote_get($file, array(
									'timeout'     => $tm,
									'redirection' => $tm
									)
								);
			if (is_array($response) && isset($response['response']['code']) && $response['response']['code']==200)
				$rez = $response['body'];
		} else {
			if (($file = plumbing_parts_get_file_dir($file)) != '')
				$rez = plumbing_parts_fgc($file);
		}
		return $rez;
	}
}

// Remove unsafe characters from file/folder path
if (!function_exists('plumbing_parts_esc')) {	
	function plumbing_parts_esc($file) {
		return str_replace(array('\\'), array('/'), $file);
	}
}

// Create folder
if (!function_exists('plumbing_parts_mkdir')) {	
	function plumbing_parts_mkdir($folder, $addindex = true) {
		if (is_dir($folder) && $addindex == false) return true;
		$created = wp_mkdir_p(trailingslashit($folder));
		@chmod($folder, 0777);
		if ($addindex == false) return $created;
		$index_file = trailingslashit($folder) . 'index.php';
		if (file_exists($index_file)) return $created;
		plumbing_parts_fpc($index_file, "<?php\n// Silence is golden.\n");
		return $created;
	}
}


/* Enqueue scripts and styles from child or main theme directory and use .min version
------------------------------------------------------------------------------------- */

// Enqueue .min.css (if exists and filetime .min.css > filetime .css) instead .css
if (!function_exists('plumbing_parts_enqueue_style')) {	
	function plumbing_parts_enqueue_style($handle, $src=false, $depts=array(), $ver=null, $media='all') {
		$load = true;
		if (!is_array($src) && $src !== false && $src !== '') {
			$debug_mode = plumbing_parts_get_theme_option('debug_mode');
			$theme_dir = get_template_directory();
			$theme_url = get_template_directory_uri();
			$child_dir = get_stylesheet_directory();
			$child_url = get_stylesheet_directory_uri();
			$dir = $url = '';
			if (plumbing_parts_strpos($src, $child_url)===0) {
				$dir = $child_dir;
				$url = $child_url;
			} else if (plumbing_parts_strpos($src, $theme_url)===0) {
				$dir = $theme_dir;
				$url = $theme_url;
			}
			if ($dir != '') {
				if ($debug_mode == 'no') {
					if (plumbing_parts_substr($src, -4)=='.css') {
						if (plumbing_parts_substr($src, -8)!='.min.css') {
							$src_min = plumbing_parts_substr($src, 0, plumbing_parts_strlen($src)-4).'.min.css';
							$file_src = $dir . plumbing_parts_substr($src, plumbing_parts_strlen($url));
							$file_min = $dir . plumbing_parts_substr($src_min, plumbing_parts_strlen($url));
							if (file_exists($file_min) && filemtime($file_src) <= filemtime($file_min)) $src = $src_min;
						}
					}
				}
				$file_src = $dir . plumbing_parts_substr($src, plumbing_parts_strlen($url));
				$load = file_exists($file_src) && filesize($file_src) > 0;
			}
		}
		if ($load) {
			if (is_array($src))
				wp_enqueue_style( $handle, $depts, $ver, $media );
			else
				wp_enqueue_style( $handle, $src, $depts, $ver, $media );
		}
	}
}

// Enqueue .min.js (if exists and filetime .min.js > filetime .js) instead .js
if (!function_exists('plumbing_parts_enqueue_script')) {	
	function plumbing_parts_enqueue_script($handle, $src=false, $depts=array(), $ver=null, $in_footer=false) {
		$load = true;
		if (!is_array($src) && $src !== false && $src !== '') {
			$debug_mode = plumbing_parts_get_theme_option('debug_mode');
			$theme_dir = get_template_directory();
			$theme_url = get_template_directory_uri();
			$child_dir = get_stylesheet_directory();
			$child_url = get_stylesheet_directory_uri();
			$dir = $url = '';
			if (plumbing_parts_strpos($src, $child_url)===0) {
				$dir = $child_dir;
				$url = $child_url;
			} else if (plumbing_parts_strpos($src, $theme_url)===0) {
				$dir = $theme_dir;
				$url = $theme_url;
			}
			if ($dir != '') {
				if ($debug_mode == 'no') {
					if (plumbing_parts_substr($src, -3)=='.js') {
						if (plumbing_parts_substr($src, -7)!='.min.js') {
							$src_min  = plumbing_parts_substr($src, 0, plumbing_parts_strlen($src)-3).'.min.js';
							$file_src = $dir . plumbing_parts_substr($src, plumbing_parts_strlen($url));
							$file_min = $dir . plumbing_parts_substr($src_min, plumbing_parts_strlen($url));
							if (file_exists($file_min) && filemtime($file_src) <= filemtime($file_min)) $src = $src_min;
						}
					}
				}
				$file_src = $dir . plumbing_parts_substr($src, plumbing_parts_strlen($url));
				$load = file_exists($file_src) && filesize($file_src) > 0;
			}
		}
		if ($load) {
			if (is_array($src))
				wp_enqueue_script( $handle, $depts, $ver, $in_footer );
			else
				wp_enqueue_script( $handle, $src, $depts, $ver, $in_footer );
		}
	}
}


/* Check if file/folder present in the child theme and return path (url) to it. 
   Else - path (url) to file in the main theme dir
------------------------------------------------------------------------------------- */

// Detect file location with next algorithm:
// 1) check in the skin folder in the child theme folder (optional, if $from_skin==true)
// 2) check in the child theme folder
// 3) check in the framework folder in the child theme folder
// 4) check in the skin folder in the main theme folder (optional, if $from_skin==true)
// 5) check in the main theme folder
// 6) check in the framework folder in the main theme folder
if (!function_exists('plumbing_parts_get_file_dir')) {	
	function plumbing_parts_get_file_dir($file, $return_url=false, $from_skin=true) {
		static $skin_dir = '';
		if ($file[0]=='/') $file = plumbing_parts_substr($file, 1);
		if ($from_skin && empty($skin_dir) && function_exists('plumbing_parts_get_custom_option')) {
			$skin_dir = plumbing_parts_esc(plumbing_parts_get_custom_option('theme_skin'));
			if ($skin_dir) $skin_dir  = 'skins/' . ($skin_dir);
		}
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		$dir = '';
		if ($from_skin && !empty($skin_dir) && file_exists(($child_dir).'/'.($skin_dir).'/'.($file)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($skin_dir).'/'.($file);
		else if (file_exists(($child_dir).'/'.($file)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($file);
		else if (file_exists(($child_dir).'/'.(PLUMBING_PARTS_FW_DIR).'/'.($file)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.(PLUMBING_PARTS_FW_DIR).'/'.($file);
		else if ($from_skin && !empty($skin_dir) && file_exists(($theme_dir).'/'.($skin_dir).'/'.($file)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($skin_dir).'/'.($file);
		else if (file_exists(($theme_dir).'/'.($file)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($file);
		else if (file_exists(($theme_dir).'/'.(PLUMBING_PARTS_FW_DIR).'/'.($file)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.(PLUMBING_PARTS_FW_DIR).'/'.($file);
		return $dir;
	}
}

// Detect file location with next algorithm:
// 1) check in the skin folder in the main theme folder (optional, if $from_skin==true)
// 2) check in the main theme folder
// 3) check in the framework folder in the main theme folder
// and return file slug (relative path to the file without extension)
// to use it in the get_template_part()
if (!function_exists('plumbing_parts_get_file_slug')) {	
	function plumbing_parts_get_file_slug($file, $from_skin=true) {
		static $skin_dir = '';
		if ($file[0]=='/') $file = plumbing_parts_substr($file, 1);
		if ($from_skin && empty($skin_dir) && function_exists('plumbing_parts_get_custom_option')) {
			$skin_dir = plumbing_parts_esc(plumbing_parts_get_custom_option('theme_skin'));
			if ($skin_dir) $skin_dir  = 'skins/' . ($skin_dir);
		}
		$theme_dir = get_template_directory();
		$dir = '';
		if ($from_skin && !empty($skin_dir) && file_exists(($theme_dir).'/'.($skin_dir).'/'.($file)))
			$dir = ($skin_dir).'/'.($file);
		else if (file_exists(($theme_dir).'/'.($file)))
			$dir = $file;
		else if (file_exists(($theme_dir).'/'.PLUMBING_PARTS_FW_DIR.'/'.($file)))
			$dir = PLUMBING_PARTS_FW_DIR.'/'.($file);
		if (plumbing_parts_substr($dir, -4)=='.php') $dir = plumbing_parts_substr($dir, 0, plumbing_parts_strlen($dir)-4);
		return $dir;
	}
}

if (!function_exists('plumbing_parts_get_file_url')) {	
	function plumbing_parts_get_file_url($file) {
		return plumbing_parts_get_file_dir($file, true);
	}
}

// Detect file location in the skin/theme/framework folders
if (!function_exists('plumbing_parts_get_skin_file_dir')) {	
	function plumbing_parts_get_skin_file_dir($file) {
		return plumbing_parts_get_file_dir($file, false, true);
	}
}

// Detect file location in the skin/theme/framework folders
if (!function_exists('plumbing_parts_get_skin_file_slug')) {	
	function plumbing_parts_get_skin_file_slug($file) {
		return plumbing_parts_get_file_slug($file, true);
	}
}

if (!function_exists('plumbing_parts_get_skin_file_url')) {	
	function plumbing_parts_get_skin_file_url($file) {
		return plumbing_parts_get_skin_file_dir($file, true, true);
	}
}

// Detect folder location with same algorithm as file (see above)
if (!function_exists('plumbing_parts_get_folder_dir')) {	
	function plumbing_parts_get_folder_dir($folder, $return_url=false, $from_skin=false) {
		static $skin_dir = '';
		if ($folder[0]=='/') $folder = plumbing_parts_substr($folder, 1);
		if ($from_skin && empty($skin_dir) && function_exists('plumbing_parts_get_custom_option')) {
			$skin_dir = plumbing_parts_esc(plumbing_parts_get_custom_option('theme_skin'));
			if ($skin_dir) $skin_dir  = 'skins/'.($skin_dir);
		}
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		$dir = '';
		if (!empty($skin_dir) && file_exists(($child_dir).'/'.($skin_dir).'/'.($folder)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($skin_dir).'/'.($folder);
		else if (is_dir(($child_dir).'/'.($folder)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($folder);
		else if (is_dir(($child_dir).'/'.(PLUMBING_PARTS_FW_DIR).'/'.($folder)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.(PLUMBING_PARTS_FW_DIR).'/'.($folder);
		else if (!empty($skin_dir) && file_exists(($theme_dir).'/'.($skin_dir).'/'.($folder)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($skin_dir).'/'.($folder);
		else if (file_exists(($theme_dir).'/'.($folder)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($folder);
		else if (file_exists(($theme_dir).'/'.(PLUMBING_PARTS_FW_DIR).'/'.($folder)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.(PLUMBING_PARTS_FW_DIR).'/'.($folder);
		return $dir;
	}
}

if (!function_exists('plumbing_parts_get_folder_url')) {	
	function plumbing_parts_get_folder_url($folder) {
		return plumbing_parts_get_folder_dir($folder, true);
	}
}

// Detect skin version of the social icon (if exists), else return it from template images directory
if (!function_exists('plumbing_parts_get_socials_dir')) {	
	function plumbing_parts_get_socials_dir($soc, $return_url=false) {
		return plumbing_parts_get_file_dir('images/socials/' . plumbing_parts_esc($soc) . (plumbing_parts_strpos($soc, '.')===false ? '.png' : ''), $return_url, true);
	}
}

if (!function_exists('plumbing_parts_get_socials_url')) {	
	function plumbing_parts_get_socials_url($soc) {
		return plumbing_parts_get_socials_dir($soc, true);
	}
}

// Detect theme version of the template (if exists), else return it from fw templates directory
if (!function_exists('plumbing_parts_get_template_dir')) {	
	function plumbing_parts_get_template_dir($tpl) {
		return plumbing_parts_get_file_dir('templates/' . plumbing_parts_esc($tpl) . (plumbing_parts_strpos($tpl, '.php')===false ? '.php' : ''));
	}
}
?>