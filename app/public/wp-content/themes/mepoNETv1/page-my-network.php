<?php
get_header();
?>
<div class="archive-page-container">
<div class="content-archive-layout-grid">
    <div class="content-archive-sidebar">
        <?php get_template_part('template-parts/sidebar-component'); ?>
    </div>
    <div class="content-archive-main">
        <div class="network-follow">
            <h3> People you follow</h3>

            <div class="card-layout-grid">
            <?php
            if (is_user_logged_in()) {
                $current_user_id = get_current_user_id();

                // Query the follow posts associated with the current user
                $followQuery = new WP_Query(array(
                    'author' => $current_user_id,
                    'post_type' => 'follow',
                    'orderby' => 'rand',
                    'posts_per_page' => 9,
                ));

                if ($followQuery->have_posts()) {
                    while ($followQuery->have_posts()) {
                        $followQuery->the_post();
                        $followed_user_id = get_post_meta(get_the_ID(), 'followed_user_id', true);
                        $followed_user = get_user_by('ID', $followed_user_id);

                        if ($followed_user) {
                            $profile_picture_id = get_user_meta($followed_user->ID, 'profile_picture', true);
                            $user_roles = $followed_user->roles;
                            $relatedStudyPaths = get_user_meta($followed_user->ID, 'related_study_paths', true);

                            echo '<a href="' . esc_url(get_author_posts_url($followed_user->ID)) . '"><div class="network-follow-cards">';

                            if (!empty($profile_picture_id)) {
                                $profile_picture_url = wp_get_attachment_image_url($profile_picture_id, array(60, 60));
                                echo '<img src="' . esc_url($profile_picture_url) . '" alt="' . esc_attr($followed_user->display_name) . '">';
                            } else {
                                echo get_avatar($followed_user->ID, 60);
                            }

                            echo '<div class="follow-card-text">
                            <h4>' . esc_html($followed_user->display_name) . '</h4>';

                            if (!empty($user_roles) && is_array($user_roles)) {
                                foreach ($user_roles as $role) {
                                    $role_display = ucfirst($role);
                                    echo '<p>' . esc_html($role_display) . '</p>';
                                }
                            }

                            if (!empty($relatedStudyPaths) && is_array($relatedStudyPaths)) {
                                foreach ($relatedStudyPaths as $studyPathId) {
                                    $studyPathName = get_the_title($studyPathId);
                                    echo '<p>' . esc_html($studyPathName) . '</p>';
                                }
                            }

                            echo '</div></div></a>';
                        }
                    }
                    wp_reset_postdata();
                } else {
                    echo '<p class="greyed-text">' . esc_html__('You are not following anyone yet.', 'your-text-domain') . '</p>';
                }
            } else {
                echo '<p class="greyed-text">' . esc_html__('You need to be logged in to see the people you follow.', 'your-text-domain') . '</p>';
            }
            ?>
            </div>
        </div>

        <div class="network-recommend">
            <h3>People you may know</h3>
            <div class="card-layout-grid">
            <?php
            if (is_user_logged_in()) {
                $current_user_id = get_current_user_id();

                // Get the list of users the current user follows
                $current_user_following = new WP_Query(array(
                    'author' => $current_user_id,
                    'post_type' => 'follow',
                    'posts_per_page' => -1,
                ));

                $current_user_following_ids = array();

                if ($current_user_following->have_posts()) {
                    while ($current_user_following->have_posts()) {
                        $current_user_following->the_post();
                        $followed_user_id = get_post_meta(get_the_ID(), 'followed_user_id', true);
                        if ($followed_user_id) {
                            $current_user_following_ids[] = intval($followed_user_id);
                        }
                    }
                    wp_reset_postdata();
                }

                // Get the list of users followed by the users the current user follows
                $potential_recommendations = array();

                if (!empty($current_user_following_ids)) {
                    foreach ($current_user_following_ids as $followed_user_id) {
                        $followed_user_following = new WP_Query(array(
                            'author' => $followed_user_id,
                            'post_type' => 'follow',
                            'posts_per_page' => -1,
                        ));

                        if ($followed_user_following->have_posts()) {
                            while ($followed_user_following->have_posts()) {
                                $followed_user_following->the_post();
                                $potential_follow_id = get_post_meta(get_the_ID(), 'followed_user_id', true);

                                if ($potential_follow_id && $potential_follow_id != $current_user_id && !in_array($potential_follow_id, $current_user_following_ids)) {
                                    $potential_follow_id = intval($potential_follow_id);
                                    if (!isset($potential_recommendations[$potential_follow_id])) {
                                        $potential_recommendations[$potential_follow_id] = 0;
                                    }
                                    $potential_recommendations[$potential_follow_id]++;
                                }
                            }
                            wp_reset_postdata();
                        }
                    }
                }

                // Sort potential recommendations by the number of mutual followers
                arsort($potential_recommendations);

                if (!empty($potential_recommendations)) {
                    foreach ($potential_recommendations as $recommended_user_id => $count) {
                        $recommended_user = get_user_by('ID', $recommended_user_id);

                        if ($recommended_user) {
                            $profile_picture_id = get_user_meta($recommended_user->ID, 'profile_picture', true);
                            $user_roles = $recommended_user->roles;
                            $relatedStudyPaths = get_user_meta($recommended_user->ID, 'related_study_paths', true);

                            echo '<div class="network-follow-cards">';

                            if (!empty($profile_picture_id)) {
                                $profile_picture_url = wp_get_attachment_image_url($profile_picture_id, array(60, 60));
                                echo '<img src="' . esc_url($profile_picture_url) . '" alt="' . esc_attr($recommended_user->display_name) . '">';
                            } else {
                                echo get_avatar($recommended_user->ID, 60);
                            }

                            echo '<div class="follow-card-text">
                            <h4><a href="' . esc_url(get_author_posts_url($recommended_user->ID)) . '">' . esc_html($recommended_user->display_name) . '</a></h4>';

                            if (!empty($user_roles) && is_array($user_roles)) {
                                foreach ($user_roles as $role) {
                                    echo '<p>' . esc_html(ucfirst($role)) . '</p>';
                                }
                            }

                            if (!empty($relatedStudyPaths) && is_array($relatedStudyPaths)) {
                                foreach ($relatedStudyPaths as $studyPathId) {
                                    echo '<p>' . esc_html(get_the_title($studyPathId)) . '</p>';
                                }
                            }

                            // Determine if current user already follows this recommended user
                            $followStatus = 'no';
                            $followPostID = '';

                            $existQuery = new WP_Query(array(
                                'author' => $current_user_id,
                                'post_type' => 'follow',
                                'meta_query' => array(
                                    array(
                                        'key' => 'followed_user_id',
                                        'compare' => '=',
                                        'value' => $recommended_user->ID
                                    )
                                )
                            ));

                            if ($existQuery->found_posts) {
                                $followStatus = 'yes';
                                $followPostID = $existQuery->posts[0]->ID;
                            }
                            wp_reset_postdata();

                            // Render follow/unfollow button
                            echo '</div><div class="author-follow-button ' . ($followStatus === 'yes' ? 'white-bttn' : 'black-bttn') . '" 
                                data-follow="' . esc_attr($followPostID) . '" 
                                data-user="' . esc_attr($recommended_user->ID) . '" 
                                data-exists="' . esc_attr($followStatus) . '">'
                                . ($followStatus === 'yes' ? 'Unfollow' : 'Follow') .
                            '</div>';

                            echo '</div>';
                        }
                    }
                } else {
                    echo '<p class="greyed-text">' . esc_html__('No recommendations available at the moment.', 'your-text-domain') . '</p>';
                }
            } else {
                echo '<p class="greyed-text">' . esc_html__('You need to be logged in to see people you might know.', 'your-text-domain') . '</p>';
            }
            ?>
            </div>
        </div>
    </div>
</div>
</div>

<?php
get_footer();
?>
