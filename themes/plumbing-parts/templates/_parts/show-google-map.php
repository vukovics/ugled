<?php
if ( plumbing_parts_get_custom_option('show_googlemap')=='yes' ) {
    $map_address = plumbing_parts_get_custom_option('googlemap_address');
    $map_latlng  = plumbing_parts_get_custom_option('googlemap_latlng');
    $map_zoom    = plumbing_parts_get_custom_option('googlemap_zoom');
    $map_style   = plumbing_parts_get_custom_option('googlemap_style');
    $map_height  = plumbing_parts_get_custom_option('googlemap_height');
    if (!empty($map_address) || !empty($map_latlng)) {
        $args = array();
        if (!empty($map_style))		$args['style'] = esc_attr($map_style);
        if (!empty($map_zoom))		$args['zoom'] = esc_attr($map_zoom);
        if (!empty($map_height))	$args['height'] = esc_attr($map_height);
        plumbing_parts_show_layout(plumbing_parts_sc_googlemap($args));
    }
}

?>