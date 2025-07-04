<?php
/* Template Name: Followed Users Posts */

get_header();

// Get the current user ID
$current_user_id = get_current_user_id();

// Fetch the IDs of users the current user follows
function get_followed_user_ids($current_user_id) {
    $follow_query = new WP_Query(array(
        'author' => $current_user_id,
        'post_type' => 'follow',
        'fields' => 'ids',
        'posts_per_page' => -1
    ));

    $followed_user_ids = array();
    if ($follow_query->have_posts()) {
        while ($follow_query->have_posts()) {
            $follow_query->the_post();
            $followed_user_id = get_post_meta(get_the_ID(), 'followed_user_id', true);
            if ($followed_user_id) {
                $followed_user_ids[] = $followed_user_id;
            }
        }
    }
    wp_reset_postdata(); // Reset post data after query

    return $followed_user_ids;
}

$followed_user_ids = get_followed_user_ids($current_user_id);

// Set up a new query to fetch posts from followed users (only if we're following someone)
$args = array(
    'post_type' => array('post', 'event', 'project'),
    'posts_per_page' => 10, // Adjust as needed
);

// Only add author__in if we're actually following someone
if (!empty($followed_user_ids)) {
    $args['author__in'] = $followed_user_ids;
}
$followed_posts_query = new WP_Query($args);
?>

<div class="page-container">
    <div class="content-layout-grid">

        <div class="content-sidebar">
            <?php get_template_part('template-parts/sidebar-component'); ?>
        </div>

        <div class="content-main">

            <div class="feed-toggle-buttons">
                <a href="<?php echo site_url('/'); ?>" class="button explore-button">Explore</a>
                <a href="<?php echo site_url('/followed-feed/'); ?>"
                    class="button following-button">Following</a>
            </div>

            <div class="main-content-feed">
                <?php if (empty($followed_user_ids)) : ?>
                    <div class="no-following-message">
                        <p>You're not following anyone yet.</p>
                        <p>Follow some users to see their posts here.</p>
                        <a href="<?php echo site_url('/community/'); ?>" class="button">Browse Community</a>
                    </div>
                <?php elseif ($followed_posts_query->have_posts()) : ?>
                    <?php while ($followed_posts_query->have_posts()) : $followed_posts_query->the_post(); ?>

                        <!-- POST HTML -->
                        <?php if ('post' == get_post_type()) { ?>

                            <div class="feed-post">
                                <div class="post-top-bar">
                                    <div class="post-profile">
                                        <div class="post-profile-img">
                                            <?php
                                            $get_author_id = get_the_author_meta('ID');
                                            echo get_avatar($get_author_id);
                                            ?>
                                        </div>
                                        <div class="post-profile-info">
                                            <h4>
                                                <?php the_author_posts_link(); ?>
                                            </h4>
                                            <p class="greyed-text">
                                                <?php
                                                $relatedStudyPaths = get_user_meta($get_author_id, 'related_study_paths', true);
                                                $user = get_userdata($get_author_id);
                                                $user_roles = !empty($user) && !empty($user->roles) ? $user->roles : [];

                                                if (!empty($user_roles) && is_array($user_roles)) {
                                                    foreach ($user_roles as $role) {
                                                        echo esc_html(ucfirst($role)) . ' ';
                                                    }
                                                }
                                                ?>
                                            </p>
                                            <p class="greyed-text">
                                                <?php
                                                if (!empty($relatedStudyPaths) && is_array($relatedStudyPaths)) {
                                                    foreach ($relatedStudyPaths as $studyPathId) {
                                                        $studyPathName = get_the_title($studyPathId);
                                                        echo esc_html($studyPathName) . ' ';
                                                    }
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="post-meta">
                                        <p>
                                            <?php
                                            if (function_exists('rpt_get_relative_post_time')) {
                                                echo esc_html(rpt_get_relative_post_time());
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="post-main-text">
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <br>
                                    <p><?php the_excerpt(); ?></p>
                                    <p class='readmore'><a href="<?php the_permalink(); ?>">Read More</a></p>
                                </div>
                                <div class="post-main-media">
                                    <?php the_post_thumbnail('single-post-thumbnail'); ?>
                                </div>

                                <?php get_template_part('template-parts/post', 'bottom-bar'); ?>

                            </div>

                        <?php } ?>

                        <!-- EVENT HTML -->
                        <?php if ('event' == get_post_type()) { ?>
                            <div class="feed-post">
                                <div class="post-top-bar">
                                    <div class="post-profile">
                                        <div class="post-profile-img">
                                            <?php
                                            $get_author_id = get_the_author_meta('ID');
                                            echo get_avatar($get_author_id);
                                            ?>
                                        </div>
                                        <div class="post-profile-info">
                                            <h4>
                                                <?php the_author_posts_link(); ?>
                                            </h4>
                                            <p class="greyed-text">
                                                <?php
                                                $relatedStudyPaths = get_user_meta($get_author_id, 'related_study_paths', true);
                                                $user = get_userdata($get_author_id);
                                                $user_roles = !empty($user) && !empty($user->roles) ? $user->roles : [];

                                                if (!empty($user_roles) && is_array($user_roles)) {
                                                    foreach ($user_roles as $role) {
                                                        echo esc_html(ucfirst($role)) . ' ';
                                                    }
                                                }
                                                ?>
                                            </p>
                                            <p class="greyed-text">
                                                <?php
                                                if (!empty($relatedStudyPaths) && is_array($relatedStudyPaths)) {
                                                    foreach ($relatedStudyPaths as $studyPathId) {
                                                        $studyPathName = get_the_title($studyPathId);
                                                        echo esc_html($studyPathName) . ' ';
                                                    }
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="post-meta">
                                        <p>
                                            <?php
                                            if (function_exists('rpt_get_relative_post_time')) {
                                                echo esc_html(rpt_get_relative_post_time());
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="post-event-main">
                                    <div class="post-event-media">
                                        <?php the_post_thumbnail('single-post-thumbnail'); ?>
                                    </div>
                                    <div class="post-event-info">
                                        <div class="post-event-date">
                                            <?php
                                            $eventDateRaw = get_field('event_date');
                                            if ($eventDateRaw) {
                                                try {
                                                    $eventDate = new DateTime($eventDateRaw);
                                                    ?>
                                                    <h3><?php echo esc_html($eventDate->format('M')); ?></h3>
                                                    <p><?php echo esc_html($eventDate->format('j')); ?></p>
                                                    <h3><?php echo esc_html($eventDate->format('Y')); ?></h3>
                                                    <?php
                                                } catch (Exception $e) {
                                                    // invalid date format, show nothing or fallback
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="post-event-text">
                                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                            <p>
                                                <strong>Time: </strong>
                                                <?php
                                                $eventTimeStart = get_field('event_time_start');
                                                if ($eventTimeStart) {
                                                    echo esc_html($eventTimeStart);
                                                }
                                                $eventTimeEnd = get_field('event_time_end');
                                                if (!empty($eventTimeEnd)) {
                                                    echo ' - ' . esc_html($eventTimeEnd);
                                                }
                                                ?>
                                            </p>
                                            <p>
                                                <strong>Location: </strong>
                                                <?php
                                                $eventLocation = get_field('event_location');
                                                if ($eventLocation) {
                                                    echo esc_html(wp_trim_words($eventLocation, 6, '...'));
                                                }
                                                ?>
                                            </p>
                                        </div>
                                        <div class="post-view-event-bttn black-bttn">
                                            <a href="<?php the_permalink(); ?>">View Event</a>
                                        </div>
                                    </div>

                                </div>

                                <?php get_template_part('template-parts/post', 'bottom-bar'); ?>

                            </div>
                        <?php } ?>

                        <!-- PROJECT HTML -->
                        <?php if ('project' == get_post_type()) { ?>

                            <div class="feed-post">

                                <!-- Post Top Bar -->
                                <div class="post-top-bar">
                                    <div class="post-profile">
                                        <div class="post-profile-img">
                                            <?php
                                            $get_author_id = get_the_author_meta('ID');
                                            $get_author_gravatar = get_avatar_url($get_author_id, ['size' => 450]);
                                            echo '<img src="' . esc_url($get_author_gravatar) . '" alt="' . esc_attr(get_the_title()) . '" />';
                                            ?>
                                        </div>

                                        <div class="post-profile-info">
                                            <h4><?php the_author_posts_link(); ?></h4>

                                            <p class="greyed-text">
                                                <?php
                                                $user = get_userdata($get_author_id);
                                                if (!empty($user->roles) && is_array($user->roles)) {
                                                    foreach ($user->roles as $role) {
                                                        echo esc_html(ucfirst($role)) . ' ';
                                                    }
                                                }
                                                ?>
                                            </p>

                                            <p class="greyed-text">
                                                <?php
                                                $relatedStudyPaths = get_user_meta($get_author_id, 'related_study_paths', true);
                                                if (!empty($relatedStudyPaths) && is_array($relatedStudyPaths)) {
                                                    foreach ($relatedStudyPaths as $studyPathId) {
                                                        echo esc_html(get_the_title($studyPathId)) . ' ';
                                                    }
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="post-meta">
                                        <p><?php echo function_exists('rpt_get_relative_post_time') ? esc_html(rpt_get_relative_post_time()) : ''; ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Post Main Text -->
                                <div class="post-project-main">
                                    <div class="post-main-text">
                                        <h3>PROJECT: <br><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <p><?php the_excerpt(); ?></p>
                                        <p class="readmore"><a href="<?php the_permalink(); ?>">Read More</a></p>
                                    </div>

                                    <!-- Post Media -->
                                    <div class="post-main-media">
                                        <?php the_post_thumbnail('single-post-thumbnail'); ?>
                                    </div>

                                    <!-- Project Info -->
                                    <div class="post-project-info">
                                        <div class="post-project-text">
                                            <p><strong>Project Manager:</strong>
                                                <?php echo esc_html(get_field('project_manager')); ?></p>

                                            <p>
                                                <strong>Timeline:</strong>
                                                <?php
                                                $start = get_field('project_start_date');
                                                $end = get_field('project_end_date');
                                                if ($start) {
                                                    echo esc_html($start);
                                                }
                                                if (!empty($end)) {
                                                    echo ' – ' . esc_html($end);
                                                }
                                                ?>
                                            </p>

                                            <p><strong>Team Size:</strong> <?php echo esc_html(get_field('project_team_size')); ?>
                                            </p>
                                        </div>

                                        <div class="post-view-project-bttn black-bttn">
                                            <a href="<?php the_permalink(); ?>">View Project</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Post Bottom Bar -->
                                <?php get_template_part('template-parts/post', 'bottom-bar'); ?>
                                <!-- end post-bottom-bar -->
                            </div> <!-- end feed-post -->
                        <?php } ?>
                        <!-- PROJECT HTML -->
                    <?php endwhile; ?>
                <?php elseif (!empty($followed_user_ids)): ?>
                    <p>No posts found from users you follow.</p>
                <?php endif; ?>

                <?php wp_reset_postdata(); ?>
            </div>

            <?php if (!empty($followed_user_ids) && $followed_posts_query->max_num_pages > 1) : ?>
                <div class="pagination">
                    <?php
                    // Use paginate_links() for custom query pagination
                    echo paginate_links(array(
                        'total' => $followed_posts_query->max_num_pages,
                        'prev_text' => '&laquo; Previous',
                        'next_text' => 'Next &raquo;'
                    ));
                    ?>
                </div>
            <?php endif; ?>

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
                        <li>
                            <p>
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
                            </p>
                        </li>
                        <?php
                    }
                    wp_reset_postdata();
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>