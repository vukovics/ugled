<?php
/**
 * The Header for our theme.
 */

// Theme init - don't remove next row! Load custom options
plumbing_parts_core_init_theme();

plumbing_parts_profiler_add_point(esc_html__('Before Theme HTML output', 'plumbing-parts'));

$theme_skin = plumbing_parts_esc(plumbing_parts_get_custom_option('theme_skin'));
$body_scheme = plumbing_parts_get_custom_option('body_scheme');
if (empty($body_scheme)  || plumbing_parts_is_inherit_option($body_scheme)) $body_scheme = 'original';
$body_style  = plumbing_parts_get_custom_option('body_style');
$top_panel_style = plumbing_parts_get_custom_option('top_panel_style');
$top_panel_position = plumbing_parts_get_custom_option('top_panel_position');
$top_panel_scheme = plumbing_parts_get_custom_option('top_panel_scheme');
$video_bg_show  = plumbing_parts_get_custom_option('show_video_bg')=='yes' && (plumbing_parts_get_custom_option('video_bg_youtube_code')!='' || plumbing_parts_get_custom_option('video_bg_url')!='');

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo 'scheme_' . esc_attr($body_scheme); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1<?php if (plumbing_parts_get_theme_option('responsive_layouts')=='yes') echo ', maximum-scale=1'; ?>">
	<meta name="format-detection" content="telephone=no">

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php
	if (($preloader=plumbing_parts_get_theme_option('page_preloader'))!='') {
		$clr = plumbing_parts_get_scheme_color('bg_color');
		?>
	   	<style type="text/css">
   		<!--

	   	-->
   		</style>
   		<?php
   	}

    if ( !function_exists('has_site_icon') || !has_site_icon() ) {
        $favicon = plumbing_parts_get_custom_option('favicon');
        if (!$favicon) {
            if ( file_exists(plumbing_parts_get_file_dir('skins/'.($theme_skin).'/images/favicon.ico')) )
                $favicon = plumbing_parts_get_file_url('skins/'.($theme_skin).'/images/favicon.ico');
            if ( !$favicon && file_exists(plumbing_parts_get_file_dir('favicon.ico')) )
                $favicon = plumbing_parts_get_file_url('favicon.ico');
        }
        if ($favicon) {
            ?><link rel="icon" type="image/x-icon" href="<?php echo esc_url($favicon); ?>" /><?php
        }
    }


	wp_head();
	?>
</head>

<body <?php body_class();?>>

	<?php 
	plumbing_parts_profiler_add_point(esc_html__('BODY start', 'plumbing-parts'));
	
	echo force_balance_tags(plumbing_parts_get_custom_option('gtm_code'));

	// Page preloader
	if ($preloader!='') {
		?><div id="page_preloader"></div><?php
	}

	do_action( 'before' );

	// Add TOC items 'Home' and "To top"
    require_once plumbing_parts_get_file_dir('templates/_parts/menu-toc.php');
	?>

	<?php if ( !plumbing_parts_param_is_off(plumbing_parts_get_custom_option('show_sidebar_outer')) ) { ?>
	<div class="outer_wrap">
	<?php } ?>

	<?php get_template_part(plumbing_parts_get_file_slug('sidebar_outer.php')); ?>

	<?php
		$class = $style = '';
		if (plumbing_parts_get_custom_option('bg_custom')=='yes' && ($body_style=='boxed' || plumbing_parts_get_custom_option('bg_image_load')=='always')) {
			if (($img = plumbing_parts_get_custom_option('bg_image_custom')) != '')
				$style = 'background: url('.esc_url($img).') ' . str_replace('_', ' ', plumbing_parts_get_custom_option('bg_image_custom_position')) . ' no-repeat fixed;';
			else if (($img = plumbing_parts_get_custom_option('bg_pattern_custom')) != '')
				$style = 'background: url('.esc_url($img).') 0 0 repeat fixed;';
			else if (($img = plumbing_parts_get_custom_option('bg_image')) > 0)
				$class = 'bg_image_'.($img);
			else if (($img = plumbing_parts_get_custom_option('bg_pattern')) > 0)
				$class = 'bg_pattern_'.($img);
			if (($img = plumbing_parts_get_custom_option('bg_color')) != '')
				$style .= 'background-color: '.($img).';';
		}
	?>

	<div class="body_wrap<?php echo !empty($class) ? ' '.esc_attr($class) : ''; ?>"<?php echo !empty($style) ? ' style="'.esc_attr($style).'"' : ''; ?>>

		<?php
        // Video bg
        require_once plumbing_parts_get_file_dir('templates/headers/_parts/video-bg.php');
		?>

		<div class="page_wrap">

			<?php
			plumbing_parts_profiler_add_point(esc_html__('Before Page Header', 'plumbing-parts'));
			// Top panel 'Above' or 'Over'
			if (in_array($top_panel_position, array('above', 'over'))) {
				plumbing_parts_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
				// Mobile Menu
				get_template_part(plumbing_parts_get_file_slug('templates/headers/_parts/header-mobile.php'));

				plumbing_parts_profiler_add_point(esc_html__('After show menu', 'plumbing-parts'));
			}

			// Slider
			get_template_part(plumbing_parts_get_file_slug('templates/headers/_parts/slider.php'));
			
			// Top panel 'Below'
			if ($top_panel_position == 'below') {
				plumbing_parts_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
				// Mobile Menu
				get_template_part(plumbing_parts_get_file_slug('templates/headers/_parts/header-mobile.php'));

				plumbing_parts_profiler_add_point(esc_html__('After show menu', 'plumbing-parts'));
			}



			// Top of page section: page title and breadcrumbs
            require_once plumbing_parts_get_file_dir('templates/headers/_parts/breadcrumbs.php');
			?>




			<div class="page_content_wrap page_paddings_<?php echo esc_attr(plumbing_parts_get_custom_option('body_paddings')); ?>">

				<?php
				plumbing_parts_profiler_add_point(esc_html__('Before Page content', 'plumbing-parts'));
				// Content and sidebar wrapper
				if ($body_style!='fullscreen') plumbing_parts_open_wrapper('<div class="content_wrap">');

                ?>
                <?php if (plumbing_parts_get_custom_option('switch_user_header')=='yes') { ?>
                    <?php
                    $show_user_header = plumbing_parts_get_custom_option('show_user_header');
                    if (!empty($show_user_header) && $show_user_header != 'none') {
                        ?>
                        <div class="userHeaderSection">
                            <?php
                            echo plumbing_parts_do_shortcode($show_user_header);
                            ?>
                        </div>
                    <?php
                    }
                }

            ?>
<?php
				// Main content wrapper
				plumbing_parts_open_wrapper('<div class="content">');

				?>