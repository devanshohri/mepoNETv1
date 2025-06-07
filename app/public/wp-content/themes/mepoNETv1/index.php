<?php

get_header(); ?>


<div class="content-layout-grid">

 <div class="content-sidebar">

        <?php
        $current_user = wp_get_current_user();
        if (is_user_logged_in()) { ?>

            <div class="sidebar">

                <a href="<?php echo get_author_posts_url($current_user->ID); ?>"><div class="sidebar-profile">

                    <div class="profile-picture">
                        <?php echo get_avatar(get_current_user_id(), 60); ?>
                    </div>

                    <div class="profile-info">
                        <div class="profile-name">
                            <h3>
                                <?php echo $current_user->display_name ?>
                            </h3>
                        </div>
                        <div class="profile-username">
                            <p>
                            <?php echo'@'.$current_user->user_login ?>
                            </p>
                        </div>
                    </div>

                </div></a>

                <div <?php if (is_home()) echo 'class="current-sidebar-button"'; else echo 'class="sidebar-button"'; ?>>
                    <a href="<?php echo site_url() ?>"><span class="material-icons">explore</span>Feed</a>
                </div>

                <div <?php if (is_page('my-network')) echo 'class="current-sidebar-button"'; else echo 'class="sidebar-button"'; ?>>
                    <a href="<?php echo site_url('/my-network') ?>"><span class="material-icons">group</span>My Network</a>
                </div>

                <div <?php if (is_page('event')) echo 'class="current-sidebar-button"'; else echo 'class="sidebar-button"'; ?>>
                    <a href="<?php echo get_post_type_archive_link('event') ?>"><span class="material-icons">calendar_today</span>Events</a>
                </div>

                <div <?php if (is_page('project')) echo 'class="current-sidebar-button"'; else echo 'class="sidebar-button"'; ?>>
                    <a href="<?php echo get_post_type_archive_link('project') ?>"><span class="material-icons">extension</span>Projects</a>
                </div>

                <div class="sidebar-button logout-button">
                    <a href="<?php echo wp_logout_url() ?>"><span class="material-icons">logout</span>Log Out</a>
                </div>

            </div>
        <?php } ?>
    </div>

    
    <div class="content-aside">

        <div class="activity-feed">
            <h4>New Activity</h4>
            <ul> <!-- Move the <ul> element outside the loop -->
                <?php
                $actargs = array(
                    'post_type' => array('post', 'event', 'project', 'follow', 'like'),
                    'posts_per_page' => 5, // Adjust the number of posts as needed
                    'orderby' => 'date', // Order by post date
                    'order' => 'DESC', // Descending order (most recent first)
                );

                $act_query = new WP_Query($actargs);

                while ($act_query->have_posts()) {
                    $act_query->the_post();
                    ?>
                    <li><p>
                        <?php
                        $current_post_type = get_post_type();

                        if ($current_post_type == 'post') {
                            echo '📝 ' . get_author_name() . ' added a new <a href="' . get_permalink() . '">Post</a>';
                        } elseif ($current_post_type == 'event') {
                            echo '📅 ' . get_author_name() . ' added a new <a href="' . get_permalink() . '">Event</a>';
                        } elseif ($current_post_type == 'project') {
                            echo '📂 ' . get_author_name() . ' added a new <a href="' . get_permalink() . '">Project</a>';
                        } elseif ($current_post_type == 'follow') {
                            echo '➕ ' . get_the_title();
                        } elseif ($current_post_type == 'like') {
                            $liked_post_id = get_post_meta(get_the_ID(), 'liked_post_id', true);
                            $liked_post_type = get_post_type($liked_post_id);
                            echo '❤️ ' . get_the_title() . "'s <a href='" . get_permalink($liked_post_id) . "'>" . ucfirst($liked_post_type) . "</a>";
                        }
                        ?>
                    </p></li>
                    <?php
                }
                wp_reset_postdata();
                ?>
            </ul>
        </div>

        <div class="search-study-path">
            <h4>Explore Study path</h4>
            <a href="<?php echo site_url('/studypaths/interactive-media/'); ?>"><div class="search-study-path-button search-intmed-button">Interactive Media</div></a>
            <a href="<?php echo site_url('/studypaths/music-production/'); ?>"><div class="search-study-path-button search-musicprod-button">Music Production</div></a>
            <a href="<?php echo site_url('/studypaths/fine-arts/'); ?>"><div class="search-study-path-button search-farts-button">Fine Arts</div></a>
            <a href="<?php echo site_url('/studypaths/medianomi/'); ?>"><div class="search-study-path-button search-medi-button">Medianomi</div></a>
        </div>
    </div>
</div>


<? get_footer();

?>