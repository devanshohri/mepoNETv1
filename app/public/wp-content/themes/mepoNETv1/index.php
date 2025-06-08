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

    <div class="content-main">
        <div class="main-content-feed">
            <?php

            // Modify the default loop, include custom post types
            global $wp_query;

            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

            $args = array_merge($wp_query->query, array(
                'post_type' => array('post', 'event', 'project'),
                'posts_per_page' => 8,
                'paged' => $paged
            ));


            $mainfeed_query = new WP_Query($args);

            while ($mainfeed_query->have_posts()) {
                $mainfeed_query->the_post();
                ?>

                <!-- POST HTML -->
                <?php if ('post' == get_post_type()) {?>
                    
                <div class="feed-post">
                    <div class="post-top-bar">
                        <div class="post-profile">
                            <div class="post-profile-img">
                                <?php
                                $get_author_id = get_the_author_meta('ID');
                                $get_author_gravatar = get_avatar_url($get_author_id, array('size' => 450));
                                echo '<img src="' . $get_author_gravatar . '" alt="' . get_the_title() . '" />';
                                ?>
                            </div>
                            <div class="post-profile-info">
                                <h4>
                                    <?php the_author_posts_link(); ?>
                                </h4>
                                <p class="greyed-text">

                                    <!-- User Role -->
                                    <?php
                                    $relatedStudyPaths = get_user_meta($get_author_id, 'related_study_paths', true);
                                    $user = get_userdata($get_author_id); // Get user data including roles
                                    $user_roles = $user->roles;

                                    if (!empty($user_roles) && is_array($user_roles)) {
                                        foreach ($user_roles as $role) {
                                            $role_display = ucfirst($role); // Make first letter uppercase
                                            echo $role_display . ' ';
                                        }
                                    } ?>
                                    <!-- User Role -->

                                </p>
                                <p class="greyed-text">
                                    <!-- Related Study Path -->
                                    <?php if (!empty($relatedStudyPaths) && is_array($relatedStudyPaths)) { ?>
                                        <?php
                                        foreach ($relatedStudyPaths as $studyPathId) {
                                            $studyPathName = get_the_title($studyPathId);
                                            echo $studyPathName;
                                        }
                                    }
                                    ?>
                                    <!-- Related Study Path -->

                                </p>
                            </div>
                        </div>
                        <div class="post-meta">
                            <p>
                                <?php echo get_the_date(); ?>
                            </p>
                            <p>
                                <?php echo get_the_time(); ?>
                            </p>
                            <p>
                                <?php
                                if ('post' == get_post_type()) {
                                    echo 'Post';
                                }

                                if ('event' == get_post_type()) {
                                    echo '<span class="dashicons dashicons-calendar-alt"></span>';
                                }

                                if ('project' == get_post_type()) {
                                    echo '<span class="dashicons dashicons-laptop"></span>';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="post-main-text">
                        <h3><a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a></h3>
                        <br>
                        <p>
                            <?php the_excerpt(); ?>
                        </p>
                        <p class='readmore'><a href="<?php the_permalink(); ?>">Read More</a></p>
                    </div>
                    <div class="post-main-media">
                        <?php the_post_thumbnail('single-post-thumbnail'); ?>
                    </div>
                    <hr>
                    <div class="post-bottom-bar">
                        <?php

                        $likeCount = new WP_Query(
                            array(
                                'post_type' => 'like',
                                'meta_query' => array(
                                    array(
                                        'key' => 'liked_post_id',
                                        'compare' => '=',
                                        'value' => get_the_ID()
                                    )
                                )
                            )
                        );

                        $likeStatus = 'no';

                        if (is_user_logged_in()) {
                            $existQuery = new WP_Query(
                                array(
                                    'author' => get_current_user_id(),
                                    'post_type' => 'like',
                                    'meta_query' => array(
                                        array(
                                            'key' => 'liked_post_id',
                                            'compare' => '=',
                                            'value' => get_the_ID()
                                        )
                                    )
                                )
                            );

                            if ($existQuery->found_posts) {
                                $likeStatus = 'yes';
                            }
                        }
                        ?>

                        <div class="like-bttn"
                            data-like="<?php if (isset($existQuery->posts[0]->ID))
                                echo $existQuery->posts[0]->ID; ?>"
                            data-post="<?php the_ID(); ?>" data-exists="<?php echo $likeStatus; ?>">

                            <?php if ($likeStatus == 'no') { ?>
                                <span class="material-symbols-outlined" style="color:red;">favorite</span>
                            <?php } elseif ($likeStatus == 'yes') { ?>
                                <span class="material-symbols-rounded" style="color:red;">favorite</span>
                            <?php } ?>
                            <span class="like-count">
                                <h4>
                                    <?php echo $likeCount->found_posts; ?>
                                </h4>
                            </span>

                        </div>
                        <div class="post-comments">
                            <h3>Comments (<?php echo get_comments_number(); ?>):
                            </h3>
                            <?php
                            $args = array(
                                'number' => '3',
                                'post_id' => $mainfeed_query->post->ID
                            );

                            $comments_args = array(
                                // change the title of send button 
                                'label_submit' => 'Post',
                                // change the title of the reply section
                                'title_reply' => '',
                                // remove "Text or HTML to be displayed after the set of comment fields"
                                'comment_notes_after' => '',
                                // redefine your own textarea (the comment body)
                                'comment_field' => '<div class="textarea-container"><textarea class="comment-textarea" oninput="commentResize(this)" placeholder="Add a Comment for ' . get_the_author() . '" name="comment" aria-required="true" ></textarea></div>',
                                //Logged In As
                                'logged_in_as' => '',

                            );

                            comment_form($comments_args);



                            $comments = get_comments($args);
                            foreach ($comments as $comment):

                                ?>

                                <p><a href="<?php echo your_get_comment_author_link(); ?>"><strong>
                                            <?php echo get_avatar($comment, '42');
                                            echo $comment->comment_author . ": "; ?></a></strong>
                                </p>

                                <p class="comment-itself">
                                    <?php
                                    echo $comment->comment_content . " "; ?>
                                </p>
                                <?php
                            endforeach
                            ?>
                        </div>
                    </div>
                </div>
                <?php } ?>      
                <!-- POST HTML -->

                <!-- EVENT HTML -->
                <?php if ('event' == get_post_type()) {?>
                    
                    <div class="feed-post">
                    <div class="post-top-bar">
                        <div class="post-profile">
                            <div class="post-profile-img">
                                <?php
                                $get_author_id = get_the_author_meta('ID');
                                $get_author_gravatar = get_avatar_url($get_author_id, array('size' => 450));
                                echo '<img src="' . $get_author_gravatar . '" alt="' . get_the_title() . '" />';
                                ?>
                            </div>
                            <div class="post-profile-info">
                                <h4>
                                    <?php the_author_posts_link(); ?>
                                </h4>
                                <p class="greyed-text">

                                    <!-- User Role -->
                                    <?php
                                    $relatedStudyPaths = get_user_meta($get_author_id, 'related_study_paths', true);
                                    $user = get_userdata($get_author_id); // Get user data including roles
                                    $user_roles = $user->roles;

                                    if (!empty($user_roles) && is_array($user_roles)) {
                                        foreach ($user_roles as $role) {
                                            $role_display = ucfirst($role); // Make first letter uppercase
                                            echo $role_display . ' ';
                                        }
                                    } ?>
                                    <!-- User Role -->

                                </p>
                                <p class="greyed-text">
                                    <!-- Related Study Path -->
                                    <?php if (!empty($relatedStudyPaths) && is_array($relatedStudyPaths)) { ?>
                                        <?php
                                        foreach ($relatedStudyPaths as $studyPathId) {
                                            $studyPathName = get_the_title($studyPathId);
                                            echo $studyPathName;
                                        }
                                    }
                                    ?>
                                    <!-- Related Study Path -->

                                </p>
                            </div>
                        </div>
                        <div class="post-meta">
                            <p>
                                <?php echo get_the_date(); ?>
                            </p>
                            <p>
                                <?php echo get_the_time(); ?>
                            </p>
                            <p>
                                <?php
                                if ('post' == get_post_type()) {
                                    echo 'Post';
                                }

                                if ('event' == get_post_type()) {
                                    echo '<span class="dashicons dashicons-calendar-alt"></span>';
                                }

                                if ('project' == get_post_type()) {
                                    echo '<span class="dashicons dashicons-laptop"></span>';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="post-main-text">
                        <h3><a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a></h3>
                        <br>
                        <p>
                            <?php the_excerpt(); ?>
                        </p>
                        <p class='readmore'><a href="<?php the_permalink(); ?>">Read More</a></p>
                    </div>
                    <div class="post-main-media">
                        <?php the_post_thumbnail('single-post-thumbnail'); ?>
                    </div>
                    <hr>
                    <div class="post-bottom-bar">
                        <?php

                        $likeCount = new WP_Query(
                            array(
                                'post_type' => 'like',
                                'meta_query' => array(
                                    array(
                                        'key' => 'liked_post_id',
                                        'compare' => '=',
                                        'value' => get_the_ID()
                                    )
                                )
                            )
                        );

                        $likeStatus = 'no';

                        if (is_user_logged_in()) {
                            $existQuery = new WP_Query(
                                array(
                                    'author' => get_current_user_id(),
                                    'post_type' => 'like',
                                    'meta_query' => array(
                                        array(
                                            'key' => 'liked_post_id',
                                            'compare' => '=',
                                            'value' => get_the_ID()
                                        )
                                    )
                                )
                            );

                            if ($existQuery->found_posts) {
                                $likeStatus = 'yes';
                            }
                        }
                        ?>

                        <div class="like-bttn"
                            data-like="<?php if (isset($existQuery->posts[0]->ID))
                                echo $existQuery->posts[0]->ID; ?>"
                            data-post="<?php the_ID(); ?>" data-exists="<?php echo $likeStatus; ?>">

                            <?php if ($likeStatus == 'no') { ?>
                                <span class="material-symbols-outlined" style="color:red;">favorite</span>
                            <?php } elseif ($likeStatus == 'yes') { ?>
                                <span class="material-symbols-rounded" style="color:red;">favorite</span>
                            <?php } ?>
                            <span class="like-count">
                                <h4>
                                    <?php echo $likeCount->found_posts; ?>
                                </h4>
                            </span>

                        </div>
                        <div class="post-comments">
                            <h3>Comments (<?php echo get_comments_number(); ?>):
                            </h3>
                            <?php
                            $args = array(
                                'number' => '3',
                                'post_id' => $mainfeed_query->post->ID
                            );

                            $comments_args = array(
                                // change the title of send button 
                                'label_submit' => 'Post',
                                // change the title of the reply section
                                'title_reply' => '',
                                // remove "Text or HTML to be displayed after the set of comment fields"
                                'comment_notes_after' => '',
                                // redefine your own textarea (the comment body)
                                'comment_field' => '<div class="textarea-container"><textarea class="comment-textarea" oninput="commentResize(this)" placeholder="Add a Comment for ' . get_the_author() . '" name="comment" aria-required="true" ></textarea></div>',
                                //Logged In As
                                'logged_in_as' => '',

                            );

                            comment_form($comments_args);



                            $comments = get_comments($args);
                            foreach ($comments as $comment):

                                ?>

                                <p><a href="<?php echo your_get_comment_author_link(); ?>"><strong>
                                            <?php echo get_avatar($comment, '42');
                                            echo $comment->comment_author . ": "; ?></a></strong>
                                </p>

                                <p class="comment-itself">
                                    <?php
                                    echo $comment->comment_content . " "; ?>
                                </p>
                                <?php
                            endforeach
                            ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!-- EVENT HTML -->

                <!-- PROJECT HTML -->
                <?php if ('project' == get_post_type()) {?> 
                    
                    <div class="feed-post">
                    <div class="post-top-bar">
                        <div class="post-profile">
                            <div class="post-profile-img">
                                <?php
                                $get_author_id = get_the_author_meta('ID');
                                $get_author_gravatar = get_avatar_url($get_author_id, array('size' => 450));
                                echo '<img src="' . $get_author_gravatar . '" alt="' . get_the_title() . '" />';
                                ?>
                            </div>
                            <div class="post-profile-info">
                                <h4>
                                    <?php the_author_posts_link(); ?>
                                </h4>
                                <p class="greyed-text">

                                    <!-- User Role -->
                                    <?php
                                    $relatedStudyPaths = get_user_meta($get_author_id, 'related_study_paths', true);
                                    $user = get_userdata($get_author_id); // Get user data including roles
                                    $user_roles = $user->roles;

                                    if (!empty($user_roles) && is_array($user_roles)) {
                                        foreach ($user_roles as $role) {
                                            $role_display = ucfirst($role); // Make first letter uppercase
                                            echo $role_display . ' ';
                                        }
                                    } ?>
                                    <!-- User Role -->

                                </p>
                                <p class="greyed-text">
                                    <!-- Related Study Path -->
                                    <?php if (!empty($relatedStudyPaths) && is_array($relatedStudyPaths)) { ?>
                                        <?php
                                        foreach ($relatedStudyPaths as $studyPathId) {
                                            $studyPathName = get_the_title($studyPathId);
                                            echo $studyPathName;
                                        }
                                    }
                                    ?>
                                    <!-- Related Study Path -->

                                </p>
                            </div>
                        </div>
                        <div class="post-meta">
                            <p>
                                <?php echo get_the_date(); ?>
                            </p>
                            <p>
                                <?php echo get_the_time(); ?>
                            </p>
                            <p>
                                <?php
                                if ('post' == get_post_type()) {
                                    echo 'Post';
                                }

                                if ('event' == get_post_type()) {
                                    echo '<span class="dashicons dashicons-calendar-alt"></span>';
                                }

                                if ('project' == get_post_type()) {
                                    echo '<span class="dashicons dashicons-laptop"></span>';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="post-main-text">
                        <h3><a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a></h3>
                        <br>
                        <p>
                            <?php the_excerpt(); ?>
                        </p>
                        <p class='readmore'><a href="<?php the_permalink(); ?>">Read More</a></p>
                    </div>
                    <div class="post-main-media">
                        <?php the_post_thumbnail('single-post-thumbnail'); ?>
                    </div>
                    <hr>
                    <div class="post-bottom-bar">
                        <?php

                        $likeCount = new WP_Query(
                            array(
                                'post_type' => 'like',
                                'meta_query' => array(
                                    array(
                                        'key' => 'liked_post_id',
                                        'compare' => '=',
                                        'value' => get_the_ID()
                                    )
                                )
                            )
                        );

                        $likeStatus = 'no';

                        if (is_user_logged_in()) {
                            $existQuery = new WP_Query(
                                array(
                                    'author' => get_current_user_id(),
                                    'post_type' => 'like',
                                    'meta_query' => array(
                                        array(
                                            'key' => 'liked_post_id',
                                            'compare' => '=',
                                            'value' => get_the_ID()
                                        )
                                    )
                                )
                            );

                            if ($existQuery->found_posts) {
                                $likeStatus = 'yes';
                            }
                        }
                        ?>

                        <div class="like-bttn"
                            data-like="<?php if (isset($existQuery->posts[0]->ID))
                                echo $existQuery->posts[0]->ID; ?>"
                            data-post="<?php the_ID(); ?>" data-exists="<?php echo $likeStatus; ?>">

                            <?php if ($likeStatus == 'no') { ?>
                                <span class="material-symbols-outlined" style="color:red;">favorite</span>
                            <?php } elseif ($likeStatus == 'yes') { ?>
                                <span class="material-symbols-rounded" style="color:red;">favorite</span>
                            <?php } ?>
                            <span class="like-count">
                                <h4>
                                    <?php echo $likeCount->found_posts; ?>
                                </h4>
                            </span>

                        </div>
                        <div class="post-comments">
                            <h3>Comments (<?php echo get_comments_number(); ?>):
                            </h3>
                            <?php
                            $args = array(
                                'number' => '3',
                                'post_id' => $mainfeed_query->post->ID
                            );

                            $comments_args = array(
                                // change the title of send button 
                                'label_submit' => 'Post',
                                // change the title of the reply section
                                'title_reply' => '',
                                // remove "Text or HTML to be displayed after the set of comment fields"
                                'comment_notes_after' => '',
                                // redefine your own textarea (the comment body)
                                'comment_field' => '<div class="textarea-container"><textarea class="comment-textarea" oninput="commentResize(this)" placeholder="Add a Comment for ' . get_the_author() . '" name="comment" aria-required="true" ></textarea></div>',
                                //Logged In As
                                'logged_in_as' => '',

                            );

                            comment_form($comments_args);



                            $comments = get_comments($args);
                            foreach ($comments as $comment):

                                ?>

                                <p><a href="<?php echo your_get_comment_author_link(); ?>"><strong>
                                            <?php echo get_avatar($comment, '42');
                                            echo $comment->comment_author . ": "; ?></a></strong>
                                </p>

                                <p class="comment-itself">
                                    <?php
                                    echo $comment->comment_content . " "; ?>
                                </p>
                                <?php
                            endforeach
                            ?>
                        </div>
                    </div>
                </div>
                <?php } ?> 
                <!-- PROJECT HTML -->
            <?php }
            wp_reset_postdata(); ?>
        </div>

        <div class="pagination">
            <?php
            previous_posts_link('&laquo; Previous');
            next_posts_link('Next &raquo;', $mainfeed_query->max_num_pages);
            ?>
        </div>

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


<php? get_footer();

?>