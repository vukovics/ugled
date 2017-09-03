<?php
// Footer Testimonials stream
if (plumbing_parts_get_custom_option('show_testimonials_in_footer')=='yes') {
    $count = max(1, plumbing_parts_get_custom_option('testimonials_count'));
    $data = plumbing_parts_sc_testimonials(array('count'=>$count));
    if ($data) {
        ?>
        <footer class="testimonials_wrap sc_section scheme_<?php echo esc_attr(plumbing_parts_get_custom_option('testimonials_scheme')); ?>">
            <div class="testimonials_wrap_inner sc_section_inner sc_section_overlay">
                <div class="content_wrap"><?php plumbing_parts_show_layout($data); ?></div>
            </div>
        </footer>
    <?php
    }
}
?>