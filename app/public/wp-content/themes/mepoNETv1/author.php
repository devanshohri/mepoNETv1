<?php

get_header();

$get_author_id = get_the_author_meta('ID');
$relatedStudyPaths = get_user_meta($get_author_id, 'related_study_paths', true);
$user = get_userdata($get_author_id); // Get user data including roles
$user_roles = $user->roles;

// Check if the current user is logged in
$is_user_logged_in = is_user_logged_in();
$current_user_id = get_current_user_id();
?>



<div class="author-page-container">
    <div class="author-page-layout-grid">
    <div class="author-page-sidebar">
      <?php get_template_part('template-parts/sidebar-component'); ?>
    </div>
    <div class="author-page-main">
        <div class="author-page-header">
            <div class="author-header-image">
                <?php echo get_avatar(get_the_author_meta('ID'), 300); ?>
            </div>
            <div class="author-meta-info">
                <div class="author-meta-name">
                    <h2><?php echo get_the_author_meta('display_name'); ?>
                        <p class="greyed-text"><?php echo '@' . $user->user_login; ?></p>
                    </h2>
                </div>

                <div class="author-meta-followers">
                    <?php
                    $followCount = new WP_Query(array(
                        'post_type' => 'follow',
                        'meta_query' => array(
                            array(
                                'key' => 'followed_user_id',
                                'compare' => '=',
                                'value' => $get_author_id
                            )
                        )
                    ));

                    $followStatus = 'no';

                    if (is_user_logged_in()) {
                        $existQuery = new WP_Query(array(
                            'author' => $current_user_id,
                            'post_type' => 'follow',
                            'meta_query' => array(
                                array(
                                    'key' => 'followed_user_id',
                                    'compare' => '=',
                                    'value' => $get_author_id
                                )
                            )
                        ));

                        if ($existQuery->found_posts) {
                            $followStatus = 'yes';
                        }
                    }


                    ?>
                    <p>Followers</p>
                    <h3 class="follow-count"><?php echo $followCount->found_posts; ?></h3>
                </div>

                <p class="greyed-text">

                    <!-- User Role -->
                    <?php if (!empty($user_roles) && is_array($user_roles)) {
                        foreach ($user_roles as $role) {
                            $role_display = ucfirst($role); // Make first letter uppercase
                            echo $role_display . ' ';
                        }
                    } ?>
                    <!-- User Role -->

                    <!-- Related Study Path -->
                    <?php if (!empty($relatedStudyPaths) && is_array($relatedStudyPaths)) { ?>
                        &nbsp; • &nbsp; <!-- divider -->
                        <?php
                        foreach ($relatedStudyPaths as $studyPathId) {
                            $studyPathName = get_the_title($studyPathId);
                            echo $studyPathName;
                        }
                    }
                    ?>
                    <!-- Related Study Path -->

                </p>

                <div class="author-meta-bio">
                    <p><?php the_author_description(); ?></p>
                </div>

                <div class="author-buttons">
                    <?php if ($is_user_logged_in && $current_user_id === $get_author_id) { ?>
                        <!-- Logged-in user visiting their own profile: Edit Profile button -->
                        <div class="author-edit-button window-open black-bttn">
                            <span>Edit Profile</span>
                        </div>
                    <?php } elseif ($is_user_logged_in) { ?>
                        <!-- Logged-in user visiting another user's profile: Follow button -->
                        <?php if ($followStatus == 'no') { ?>
                            <div class="author-follow-button black-bttn" data-follow="<?php if (isset($existQuery->posts[0]->ID))
                                echo $existQuery->posts[0]->ID; ?>" data-user="<?php echo $get_author_id ?>"
                                data-exists="<?php echo $followStatus; ?>">
                                Follow
                            </div>
                        <?php } elseif ($followStatus == 'yes') { ?>
                            <div class="author-follow-button white-bttn" data-follow="<?php if (isset($existQuery->posts[0]->ID))
                                echo $existQuery->posts[0]->ID; ?>" data-user="<?php echo $get_author_id ?>"
                                data-exists="<?php echo $followStatus; ?>">
                                Unfollow
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <br>
        <hr>
        <div class="author-main-content">
            <div class="author-content-type-buttons">
                <?php if ($is_user_logged_in && $current_user_id === $get_author_id) { ?>
                    <!-- Logged-in user visiting their own profile: Edit Profile button -->
                    <div class="author-post-list-button">
                        <a href="#">My Posts</a>
                    </div>
                <?php } elseif ($is_user_logged_in) { ?>
                    <!-- Logged-in user visiting another user's profile: Follow button -->
                    <div class="author-post-list-button">
                        <a href="#"><?php echo get_the_author_meta('display_name'); ?>'s Posts</a>
                    </div>
                <?php } ?>
            </div>

            <div class="author-content-layout">
                <?php
                // Modify the default loop, include custom post types
                global $wp_query;

                $args = array_merge($wp_query->query, array(
                    'post_type' => array('post', 'event', 'project'),
                    'author' => $user,
                ));

                $author_posts_query = new WP_Query($args);

                if ($author_posts_query->have_posts()) {
                    while ($author_posts_query->have_posts()) {
                        $author_posts_query->the_post(); ?>

                        <!-- POST HTML -->

                        <div class="author-post" data-id="<?php the_ID(); ?>" data-post-type="<?php echo get_post_type(); ?>">
                            <div class="author-post-content">
                                <h3>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php echo substr(get_the_title(), 0, 70), '...'; ?>
                                    </a>
                                </h3>
                            

                            <p><?php echo substr(get_the_excerpt(), 0, 150), '...'; ?></p>
                            <p class='readmore'><a href="<?php the_permalink(); ?>">Read More</a></p>
                            </div>
                            <div class="author-post-media">
                                <?php the_post_thumbnail('single-post-thumbnail'); ?>
                            </div>
                            <div class="author-post-bottom-bar">
                                <div class="black-bttn">
                                    <h4><a href="<?php the_permalink(); ?>">View Project</a></h4>
                                </div>
                                <?php if ($is_user_logged_in && $current_user_id === $get_author_id) { ?>
                                    <span class="author-post-delete-button delete-post-bttn white-bttn"> Delete Post</span>
                                <?php } ?>
                            </div>
                        </div>

                    <?php }
                } else {
                    echo '<p class="greyed-text">' . get_userdata($get_author_id)->display_name . ' has no posts.</p>';
                }
                wp_reset_postdata(); ?>
            </div>
        </div>



    </div>
    <div class="window-overlay">
        <div class="window-content">
            <h3>Edit your profile</h3>
            <?php
            if (is_user_logged_in()) {
                echo do_shortcode('[edit_profile_form]');
            }
            ?>
            <div class="window-close">
                <span class="material-icons window-close">close</span>
            </div>
        </div>
    </div>
</div>

</div>



<?php
get_footer(); ?>