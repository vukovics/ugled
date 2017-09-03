<?php
// Add TOC items 'Home' and "To top"
if (plumbing_parts_get_custom_option('menu_toc_home')=='yes')
    plumbing_parts_show_layout(plumbing_parts_sc_anchor(array(
            'id' => "toc_home",
            'title' => esc_html__('Home', 'plumbing-parts'),
            'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'plumbing-parts'),
            'icon' => "icon-home",
            'separator' => "yes",
            'url' => esc_url(home_url('/'))
        )
    ));
if (plumbing_parts_get_custom_option('menu_toc_top')=='yes')
    plumbing_parts_show_layout(plumbing_parts_sc_anchor(array(
            'id' => "toc_top",
            'title' => esc_html__('To Top', 'plumbing-parts'),
            'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'plumbing-parts'),
            'icon' => "icon-double-up",
            'separator' => "yes")
    ));
?>