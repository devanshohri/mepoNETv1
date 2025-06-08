<?php
get_header();

$study_path_id = get_the_ID();
$study_path_title = get_the_title();

$today = date('Ymd');
?>

<div class="studypath-title">
    <h1><?php echo esc_html($study_path_title); ?></h1>
</div>
<div class="page-container">
    <div class="content-layout-grid">

        <!-- Sidebar Events -->
        <div class="content-sidebar">
            <div class="sidebar-event">
                <?php
                $studyPathEvents = new WP_Query([
                    'post_type' => 'event',
                    'posts_per_page' => 5, // Limit number for performance
                    'meta_key' => 'event_date',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC',
                    'meta_query' => [
                        [
                            'key' => 'event_date',
                            'compare' => '>=',
                            'value' => $today,
                            'type' => 'NUMERIC',
                        ],
                        [
                            'key' => 'related_study_paths',
                            'compare' => 'LIKE',
                            'value' => '"' . $study_path_id . '"',
                        ],
                    ],
                ]);

                echo '<h4>Upcoming ' . esc_html($study_path_title) . ' Events</h4><br>';

                if ($studyPathEvents->have_posts()) {
                    while ($studyPathEvents->have_posts()) {
                        $studyPathEvents->the_post();
                        $eventDate = new DateTime(get_field('event_date'));
                        ?>
                        <div class="sidebar-event-container">
                            <div class="sidebar-event-img">
                                <?php the_post_thumbnail('thumbnail'); ?>
                            </div>
                            <div class="sidebar-event-content">
                                <div class="sidebar-event-date">
                                    <p>
                                        <?php echo esc_html($eventDate->format('M d')); ?>
                                    </p>
                                </div>
                                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            </div>
                        </div>
                    <?php
                    }
                    wp_reset_postdata();
                } else {
                    echo '<p class="greyed-text">No Upcoming Events</p>';
                }
                ?>
            </div>
        </div>

        <!-- Main Content Posts -->
        <div class="content-main">
            <div class="main-content-feed">
                <?php
                $studyPathPosts = new WP_Query([
                    'post_type' => 'post',
                    'posts_per_page' => 10, // Pagination can be added
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'meta_query' => [
                        [
                            'key' => 'related_study_paths',
                            'compare' => 'LIKE',
                            'value' => '"' . $study_path_id . '"',
                        ],
                    ],
                ]);

                if ($studyPathPosts->have_posts()) {
                    while ($studyPathPosts->have_posts()) {
                        $studyPathPosts->the_post();

                        $author_id = get_the_author_meta('ID');
                        $author_avatar = get_avatar_url($author_id, ['size' => 450]);
                        $author_user = get_userdata($author_id);
                        $user_roles = $author_user ? $author_user->roles : [];
                        $relatedStudyPaths = get_user_meta($author_id, 'related_study_paths', true);
                        ?>
                        <div class="feed-post">
                            <div class="post-top-bar">
                                <div class="post-profile">
                                    <div class="post-profile-img">
                                        <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
                                    </div>
                                    <div class="post-profile-info">
                                        <h4><?php the_author_posts_link(); ?></h4>
                                        <p class="greyed-text">
                                            <?php
                                            foreach ($user_roles as $role) {
                                                echo esc_html(ucfirst($role)) . ' ';
                                            }
                                            ?>
                                        </p>
                                        <p class="greyed-text">
                                            <?php
                                            if (is_array($relatedStudyPaths)) {
                                                foreach ($relatedStudyPaths as $path_id) {
                                                    echo esc_html(get_the_title($path_id)) . ' ';
                                                }
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="post-meta">
                                    <p><?php if (function_exists('rpt_get_relative_post_time')) echo esc_html(rpt_get_relative_post_time()); ?></p>
                                </div>
                            </div>
                            <div class="post-main-text">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p><?php the_excerpt(); ?></p>
                                <p class="readmore"><a href="<?php the_permalink(); ?>">Read More</a></p>
                            </div>
                            <div class="post-main-media">
                                <?php the_post_thumbnail('single-post-thumbnail'); ?>
                            </div>

                            <?php get_template_part('template-parts/post', 'bottom-bar'); ?>
                        </div>
                    <?php
                    }
                    wp_reset_postdata();
                } else {
                    echo '<p class="greyed-text">No ' . esc_html($study_path_title) . ' Posts</p>';
                }
                ?>
            </div>
        </div>

        <!-- Sidebar Users -->
        <div class="content-aside">
            <div class="sidebar-people">
                <div class="people-follow">
                    <div class="sidebar-users">
                        <?php
                        $userQuery = new WP_User_Query([
                            'meta_query' => [
                                [
                                    'key' => 'related_study_paths',
                                    'compare' => 'LIKE',
                                    'value' => '"' . $study_path_id . '"',
                                ],
                            ],
                            'number' => 10,
                            'orderby' => 'display_name',
                            'order' => 'ASC',
                        ]);

                        if (!empty($userQuery->results)) {
                            echo '<h3>' . esc_html($study_path_title) . ' People</h3>';
                            foreach ($userQuery->results as $user) {
                                $profile_picture_id = get_user_meta($user->ID, 'profile_picture', true);
                                $user_description = get_user_meta($user->ID, 'description', true);
                                ?>
                                <div class="sidebar-people-cards">
                                    <a href="<?php echo esc_url(get_author_posts_url($user->ID)); ?>">
                                        <?php
                                        if (!empty($profile_picture_id)) {
                                            $profile_picture_url = wp_get_attachment_image_url($profile_picture_id, [450, 450]);
                                            echo '<img src="' . esc_url($profile_picture_url) . '" alt="' . esc_attr($user->display_name) . '">';
                                        } else {
                                            echo get_avatar($user->ID, 450);
                                        }
                                        ?>
                                        <div class="main-people-text">
                                            <h4><?php echo esc_html($user->display_name); ?></h4>
                                            <p><?php echo esc_html($user_description); ?></p>
                                        </div>
                                    </a>
                                </div>
                            <?php
                            }
                        } else {
                            echo '<p class="greyed-text">No Users with Related Study Path</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php get_footer(); ?>
