<?php
/**
 * Reusable Sidebar Component
 */
$current_user = wp_get_current_user();
if (!is_user_logged_in())
    return;
?>

<div class="sidebar">
    <a href="<?php echo get_author_posts_url($current_user->ID); ?>">
        <div class="sidebar-profile">
            <div class="profile-picture">
                <?php echo get_avatar(get_current_user_id(), 60); ?>
            </div>
            <div class="profile-info">
                <div class="profile-name">
                    <h3><?php echo $current_user->display_name ?></h3>
                </div>
                <div class="profile-username">
                    <p><?php echo '@' . $current_user->user_login ?></p>
                </div>
            </div>
        </div>
    </a>

    <?php
    // Sidebar menu items array
    $menu_items = array(
        array(
            'url' => site_url(),
            'icon' => 'explore',
            'text' => 'Feed',
            'is_current' => is_front_page() || is_home() || is_page('followed-feed')
        ),
        array(
            'url' => site_url('/my-network'),
            'icon' => 'group',
            'text' => 'My Network',
            'is_current' => is_page('my-network')
        ),
        array(
            'url' => get_post_type_archive_link('event'),
            'icon' => 'calendar_today',
            'text' => 'Events',
            'is_current' => is_post_type_archive('event')
        ),
        array(
            'url' => get_post_type_archive_link('project'),
            'icon' => 'extension',
            'text' => 'Projects',
            'is_current' => is_post_type_archive('project')
        ),
    );

    // Output menu items
    foreach ($menu_items as $item) {
        $class = $item['is_current'] ? 'current-sidebar-button' : 'sidebar-button';
        echo '<div class="' . $class . '">';
        echo '<a href="' . esc_url($item['url']) . '">';
        echo '<span class="material-icons">' . $item['icon'] . '</span>';
        echo esc_html($item['text']);
        echo '</a>';
        echo '</div>';
    }
    ?>

    <!-- Study Paths Dropdown -->
    <div class="sidebar-button dropdown-sidebar-button" tabindex="0" role="button" aria-expanded="false"
        aria-controls="study-paths-menu">
        <span class="material-icons">menu_book</span>
        <span class="sidebar-button-text">Study Paths</span>
        <span class="material-icons dropdown-arrow">expand_more</span>
    </div>
    <div class="dropdown-menu" id="study-paths-menu" hidden>
        <a class="interactive-media-color" href="<?php echo site_url('/studypaths/interactive-media/'); ?>">Interactive Media</a>
        <a class="music-production-color" href="<?php echo site_url('/studypaths/music-production/'); ?>">Music Production</a>
        <a class="fine-arts-color" href="<?php echo site_url('/studypaths/fine-arts/'); ?>">Fine Arts</a>
        <a class="medianomi-color" href="<?php echo site_url('/studypaths/medianomi/'); ?>">Medianomi</a>
    </div>


    <div class="sidebar-button logout-button">
        <a href="<?php echo wp_logout_url() ?>">
            <span class="material-icons">logout</span>Log Out
        </a>
    </div>
</div>