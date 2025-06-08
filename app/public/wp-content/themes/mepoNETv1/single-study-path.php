<?php 

get_header(); ?>

<div class="studypath-title">
    <?php echo '<h1>' . get_the_title() . '</h1>'; ?>
</div>
<div class="content-layout-grid">

            <div class="content-sidebar">

            <div class="sidebar-event">

                    <?php 
                        $today = date('Ymd');
                        $studyPathEvents = new WP_Query(array(
                            'post_type' => 'event',
                            'meta_key' => 'event_date',
                            'orderby' => 'meta_value_num',
                            'order' => 'ASC',
                            'meta_query' => array(
                            array(
                                'key' => 'event_date',
                                'compare' => '>=',
                                'value' => $today,
                                'type' => 'numeric'
                            ),
                            array (
                                'key' => 'related_study_paths',
                                'compare' => 'LIKE',
                                'value' => '"' . get_the_ID() . '"',
                            )
                            )
                        ));
                        echo '<h4> Upcoming ' . get_the_title() . ' Events</h4>';
                        echo '<br>';
                        if($studyPathEvents->have_posts()) {
                           

                            while($studyPathEvents->have_posts()) {
                                $studyPathEvents->the_post(); ?>
                                <div class="sidebar-event-container">
                                    
                                        <div class="sidebar-event-img">
                                            <?php the_post_thumbnail('thumbnail'); ?>
                                        </div>
                                        
                                    <div class="sidebar-event-content">
                                        <div class="sidebar-event-date">
                                            <p><?php
                                            $eventDate = new DateTime(get_field('event_date'));
                                            echo $eventDate->format('M')
                                            ?>
                                            <?php echo $eventDate->format('d') ?></p>  
                                        </div>
                                        
                                        <h4 ><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                        
                                    </div>
                                </div>
                            <?php }
                        }
                        else {
                            echo '<p class="greyed-text">No Upcoming Events</p>';
                        }
                    ?>
                </div>
         
            </div>

            <?php wp_reset_postdata(); ?>

            <div class="content-main">
                <div class="main-content-feed">
                    <?php 
                        $studyPathPosts = new WP_Query(array(
                            'post_type' => 'post',
                            'orderby' => 'meta_value_num',
                            'order' => 'ASC',
                            'meta_query' => array(
                            array (
                                'key' => 'related_study_paths',
                                'compare' => 'LIKE',
                                'value' => '"' . get_the_ID() . '"',
                            ),
                            )
                        ));

                        if($studyPathPosts->have_posts()) {
                        while($studyPathPosts->have_posts()) {
                            $studyPathPosts->the_post(); ?>                            

                            <div class="feed-post">
                                <div class="post-top-bar">
                                    <div class="post-profile">
                                        <div class="post-profile-img">
                                            <?php
                                                $get_author_id = get_the_author_meta('ID');
                                                $get_author_gravatar = get_avatar_url($get_author_id, array('size' => 450));
                                                echo '<img src="'.$get_author_gravatar.'" alt="'.get_the_title().'" />';
                                            ?>
                                        </div>    
                                        <div class="post-profile-info">
                                            <h4><?php the_author_posts_link(); ?></h4>
                                            <p><?php the_author_description(); ?></p>
                                         </div>
                                    </div>
                                    <div class="post-meta">
                                        <p><?php the_date(); ?></p>
                                        <p><?php the_time(); ?></p>
                                        <p><?php 
                                                if ( 'post' == get_post_type() ) {
                                                    echo 'Post';
                                                }

                                                if ( 'event' == get_post_type() ) {
                                                    echo '<span class="dashicons dashicons-calendar-alt"></span>';
                                                } 
                                                
                                                if ( 'project' == get_post_type() ) {
                                                    echo '<span class="dashicons dashicons-laptop"></span>';
                                                }
                                                ?></p>
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
                                <hr>
                                <div class="post-bottom-bar">
                                    <div class="like-bttn">
                                        <img src="image/heart-01-svgrepo-com.svg" alt="">
                                    </div>
                                    <div class="post-comments">
                                    <h3>Comments (<?php echo get_comments_number(); ?>):</h3>
                                        <?php
                                                $args = array(
                                                    'number' => '3',
                                                    'post_id' => $post->ID
                                                );

                                                $comments_args = array(
                                                    // change the title of send button 
                                                    'label_submit'=>'Post',
                                                    // change the title of the reply section
                                                    'title_reply'=>'',
                                                    // remove "Text or HTML to be displayed after the set of comment fields"
                                                    'comment_notes_after' => '',
                                                    // redefine your own textarea (the comment body)
                                                    'comment_field' => '<div class="textarea-container"><textarea class="comment-textarea" oninput="commentResize(this)" placeholder="Add a Comment for '. get_the_author() .'" name="comment" aria-required="true" ></textarea></div>',
                                                    //Logged In As
                                                    'logged_in_as' => '',                    
                                                    
                                                );

                                                comment_form($comments_args);

                                                

                                                $comments = get_comments($args);
                                                foreach($comments as $comment) :
                                                
                                                ?>
                                                
                                                <p><a href="<?php echo your_get_comment_author_link(); ?>"><strong><?php echo get_avatar( $comment, '42' ); echo $comment->comment_author.": ";?></a></strong></p>

                                                <p class="comment-itself"><?php
                                                echo $comment->comment_content." ";?>
                                                </p><?php
                                                endforeach

                                        ?>
                                    </div>
                                </div>
                            </div>

                            <?php wp_reset_postdata(); ?>

                        <?php }

                        }
                        else {
                            echo '<p class="greyed-text">No ' . get_the_title() . ' Posts</p>';
                        }
                    ?>
                </div>
            </div>

            <?php wp_reset_postdata(); ?>

            <div class="content-aside">
                <div class="sidebar-people">
                        <div class="people-follow">
                            <div class="sidebar-users">

                                <?php 

                                        $userQuery = new WP_User_Query(array(
                                            'meta_query' => array(
                                                array(
                                                    'key' => 'related_study_paths', // Adjust this to your custom field key for study paths
                                                    'compare' => 'LIKE',
                                                    'value' => '"' . get_the_ID() . '"',
                                                ),
                                            )
                                        ));

                                        if (!empty($userQuery->results)) {
                                            echo '<h3>' . get_the_title() . ' People</h3>';
                                            

                                            foreach ($userQuery->results as $user) {
                                                $relatedStudyPaths = get_user_meta($user->ID, 'related_study_paths', true);
                                                $user_description = get_user_meta($user->ID, 'description', true);

                                                if (!empty($relatedStudyPaths) && is_array($relatedStudyPaths)) {
                                                    echo '
                                                    
                                                        <div class="sidebar-people-cards">
                                                        <a href="' . get_author_posts_url($user->ID) . '">';
                                                        if (!empty($profile_picture_id)) {
                                                            $profile_picture_url = wp_get_attachment_image_url($profile_picture_id, array(450, 450));
                                                            echo '<img src="' . $profile_picture_url . '">';
                                                        } else {
                                                            echo get_avatar($user->ID, 450);
                                                        }
                                                        echo '  <div class="main-people-text">
                                                                <h4>' . $user->display_name . '</h4>
                                                                <p>' . $user_description . '</p>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    
                                                    ';       
                                                }
                                            }   
                                        } else {
                                            echo '<p class="greyed-text">No Users with Related Study Path</p>';
                                        }                                 
                                ?>
                            </div>
                        <div class="people-all-button">
                            
                        </div>
                    </div>
                <div class="events-content-feed"></div>
            </div>
        </div>
</div>

<? get_footer();

?>