<?php
if ($video_bg_show) {
    $youtube = plumbing_parts_get_custom_option('video_bg_youtube_code');
    $video   = plumbing_parts_get_custom_option('video_bg_url');
    $overlay = plumbing_parts_get_custom_option('video_bg_overlay')=='yes';
    if (!empty($youtube)) {
        ?>
        <div class="video_bg<?php echo !empty($overlay) ? ' video_bg_overlay' : ''; ?>" data-youtube-code="<?php echo esc_attr($youtube); ?>"></div>
    <?php
    } else if (!empty($video)) {
        $info = pathinfo($video);
        $ext = !empty($info['extension']) ? $info['extension'] : 'src';
        ?>
        <div class="video_bg<?php echo !empty($overlay) ? ' video_bg_overlay' : ''; ?>"><video class="video_bg_tag" width="1280" height="720" data-width="1280" data-height="720" data-ratio="16:9" preload="metadata" autoplay loop src="<?php echo esc_url($video); ?>"><source src="<?php echo esc_url($video); ?>" type="video/<?php echo esc_attr($ext); ?>"></source></video></div>
    <?php
    }
}
?>