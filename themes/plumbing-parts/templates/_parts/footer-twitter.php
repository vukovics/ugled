<?php
if (plumbing_parts_get_custom_option('show_twitter_in_footer')=='yes') {
    $count = max(1, plumbing_parts_get_custom_option('twitter_count'));
    $data = plumbing_parts_sc_twitter(array('count'=>$count));
    if ($data) {
        ?>
        <footer class="twitter_wrap sc_section scheme_<?php echo esc_attr(plumbing_parts_get_custom_option('twitter_scheme')); ?>">
            <div class="twitter_wrap_inner sc_section_inner sc_section_overlay">
                <div class="content_wrap"><?php plumbing_parts_show_layout($data); ?></div>
            </div>
        </footer>
    <?php
    }
}

?>