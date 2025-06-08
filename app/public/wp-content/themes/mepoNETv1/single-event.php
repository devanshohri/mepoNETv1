<?php 

get_header();
?>

<div class="main-single-post">
    <?php
    while(have_posts()){
        the_post(); ?>
        <div class="single-post">

            <div class="single-post-title">
                <h1><?php the_title(); ?></h1>
            </div>

            <div class="single-post-meta">
                <p>By: <?php the_author_posts_link(); ?></p>
                <p> <?php the_date(); ?></p>
                <p><p><span class="dashicons dashicons-calendar-alt"></span> <?php echo ucwords(get_post_type()); ?></p></p>


                <div class="related-study-paths">
                    <?php 
                    $relatedStudyPaths = get_field('related_study_paths');
                    if ($relatedStudyPaths) {
                        foreach($relatedStudyPaths as $studyPath) {
                            // Get the slug for CSS class
                            $study_path_slug = get_post_field('post_name', $studyPath);
                            ?>
                            <a href="<?php echo get_the_permalink($studyPath); ?>" 
                            class=" study-path-tag-<?php echo esc_attr($study_path_slug); ?>">
                                <?php echo get_the_title($studyPath); ?>
                            </a>
                        <?php }
                    }
                    ?>
                </div>

                
            </div>

            <div class="single-post-details">
                <h3> Event Details: </h3>
                <div class="single-post-details-wrapper">


                    <span class="single-post-event-date">
                        <p>
                        <strong>Event Date: </strong>
                        <?php 
                        $eventDate = new DateTime(get_field('event_date'));
                        echo $eventDate->format('F j, Y');
                        ?>
                        </p>
                    </span>

                    <span class="single-post-event-time">
                        <p>
                        <strong>Event Time: </strong>
                        <?php 
                        $eventTimeStart = get_field('event_time_start');
                        echo $eventTimeStart;
                        $eventTimeEnd = get_field('event_time_end');
                        if (!empty($eventTimeEnd)){
                        echo " - ", $eventTimeEnd;
                        }
                        ?>
                        </p>
                    </span>

                    <span class="single-post-event-location">
                        <p>
                        <strong>Event Location: </strong>
                        <?php 
                        $eventLocation = get_field('event_location');
                        echo $eventLocation;
                        ?>
                        </p>
                    </span>

                    <?php
                    $eventEntrance = get_field('event_entrance');
                    if (!empty($eventEntrance)){
                    ?>
                    <span class="single-post-event-entrance">
                        <p>
                        <strong>Event Entrance: </strong>
                        <?php 
                        echo $eventEntrance;
                        ?>
                        </p>
                    </span>
                    <?php } ?>

                    <?php
                    $eventLanguage = get_field('event_language');
                    if (!empty($eventLanguage)){
                    ?>
                    <span class="single-post-event-language">
                        <p>
                        <strong>Language: </strong>
                        <?php 
                        echo $eventLanguage;
                        ?>
                        </p>
                    </span>
                    <?php } ?>

                    <?php
                    $eventRegDeadline = get_field('event_registration_deadline');
                    if (!empty($eventRegDeadline)){
                    ?>
                    <span class="single-post-event-registration_deadline">
                        <p>
                        <strong>Registration Deadline: </strong>
                        <?php 
                        echo $eventRegDeadline;
                        ?>
                        </p>
                    </span>
                    <?php } ?>

                    <?php
                    $eventRegLink = get_field('event_registration_website');
                    if (!empty($eventRegLink)){
                    ?>
                    <span class="single-post-event_registration_website">
                        <a href="<?php echo $eventRegLink; ?>">
                        <p><strong>Registration Link</strong></p>
                        </a>
                    </span>
                    <?php } ?>

                </div>
            </div>
            


            <div class="single-post-content">
            <?php the_post_thumbnail('medium_large'); ?>
                <?php the_content(); ?>
            </div>

            <?php
                    $eventOrganiser = get_field('event_organiser');
                    $eventOrganiserLink = get_field('event_organiser_website');
                    if ((!empty($eventOrganiser)) || (!empty($eventOrganiserLink))){
            ?>

            <div class="single-post-organiser-details">
                <h3> Organiser Details: </h3>
                <div class="single-post-organiser-details-wrapper">

                    <?php
                        if (!empty($eventOrganiser)){
                    ?>
                        <span class="single-post-event-organiser">
                            <p>
                                <strong>Organiser: </strong>
                                <?php 
                                echo $eventOrganiser;
                                ?>
                            </p>
                        </span>
                    <?php } ?>

                    <?php
                    if (!empty($eventOrganiserLink)){
                    ?>
                        <span class="single-post-event_organiser_website">
                            <a href="<?php echo $eventOrganiserLink; ?>">
                                <p>Organiser Website</p>
                            </a>
                        </span>
                    <?php } ?>

                </div>
            </div>

            <?php } ?>

            <div class="single-post-tags">
                <?php
                    global $post;
                    if(has_tag()){
                        
                        ?><h2>Tags:</h2><?php
                        ?><p><?php

                        foreach(get_the_tags($post->ID) as $tag)
                        {
                            echo '<a href="'. get_tag_link($tag->term_id).'">'.$tag->name.'</a>';
                        }

                        }
                    else{
                        echo ' ';
                        ?></p><?php
                    }

                ?>

            </div>
            <hr>
            <div class="single-post-comment">
            <h2>Comments (<?php echo get_comments_number(); ?>):</h2>
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
        <?php
    } ?>
</div>
<?php
get_footer();

?>
