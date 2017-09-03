<?php
$copyright_style = plumbing_parts_get_custom_option('show_copyright_in_footer');
if (!plumbing_parts_param_is_off($copyright_style)) {
    ?>
    <div class="copyright_wrap copyright_style_<?php echo esc_attr($copyright_style); ?>  scheme_<?php echo esc_attr(plumbing_parts_get_custom_option('copyright_scheme')); ?>">
        <div class="copyright_wrap_inner">
            <div class="content_wrap">
                <?php
                if ($copyright_style == 'menu') {
                    if (($menu = plumbing_parts_get_nav_menu('menu_footer'))!='') {
                        plumbing_parts_show_layout($menu);
                    }
                } else if ($copyright_style == 'socials') {
                    plumbing_parts_show_layout(plumbing_parts_sc_socials(array('size'=>"tiny")));
                }
                ?>
                <div class="copyright_text"><?php echo force_balance_tags(plumbing_parts_get_custom_option('footer_copyright')); ?></div>
            </div>
        </div>
    </div>
<?php
}

?>