<?php
/**
 * Plugin Name: Relative Post Time
 * Description: Displays how long ago a post was published (e.g., "5 minutes ago").
 * Version: 1.0
 * Author: Your Name
 */

function rpt_get_relative_post_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $post_time = get_the_time('U', $post_id); 
    $current_time = current_time('timestamp'); 
    $time_diff = $current_time - $post_time;

    if ($time_diff < MINUTE_IN_SECONDS) {
        return 'Just now';
    } elseif ($time_diff < HOUR_IN_SECONDS) {
        $mins = floor($time_diff / MINUTE_IN_SECONDS);
        return $mins . ' minute' . ($mins !== 1 ? 's' : '') . ' ago';
    } elseif ($time_diff < DAY_IN_SECONDS) {
        $hours = floor($time_diff / HOUR_IN_SECONDS);
        return $hours . ' hour' . ($hours !== 1 ? 's' : '') . ' ago';
    } elseif ($time_diff < WEEK_IN_SECONDS) {
        $days = floor($time_diff / DAY_IN_SECONDS);
        return $days . ' day' . ($days !== 1 ? 's' : '') . ' ago';
    } else {
        return get_the_date('', $post_id);
    }
}

// Optional: Add a shortcode to use in post content like [relative_post_time]
function rpt_relative_time_shortcode() {
    return rpt_get_relative_post_time();
}
add_shortcode('relative_post_time', 'rpt_relative_time_shortcode');
