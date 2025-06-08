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
                <p><span class="dashicons dashicons-laptop"></span> <?php echo ucwords(get_post_type()); ?></p>
                <p><?php 
                        $relatedStudyPath = get_field('related_study_paths');
                        if ($relatedStudyPath) {
                        foreach($relatedStudyPath as $studyPath) { ?>
                            <li><a href="<?php echo get_the_permalink($studyPath); ?>"><?php echo get_the_title($studyPath) ?></a></li>
                        <?php }}
                    ?>
                </p>
            </div>

         
            <div class="single-post-details">
                <h3> Project Details: </h3>
                <div class="single-post-details-wrapper">


                    <span class="single-post-project-manager">
                        <p>
                        <strong>Project Manager: </strong>
                        <?php 
                        $projectManager = get_field('project_manager');
                        echo $projectManager;
                        ?>
                        </p>
                    </span>

                    <span class="single-post-project-timeline">
                        <p>
                        <strong> Timeline: </strong>
                        <?php 
                        $projectTimeStart = get_field('project_start_date');
                        echo $projectTimeStart;
                        $projectTimeEnd = get_field('project_end_date');
                        if (!empty($projectTimeEnd)){
                        echo " - ", $projectTimeEnd;
                        }
                        ?>
                        </p>
                    </span>

                    <span class="single-post-project-team-size">
                        <p>
                        <strong> Team Size: </strong>
                        <?php 
                        $projectTeamSize = get_field('project_team_size');
                        echo $projectTeamSize;
                        ?>
                        </p>
                    </span>

                    <?php
                    $projectRolesNeeded = get_field('project_roles_needed');
                    if (!empty($projectRolesNeeded)){
                    ?>
                    <span class="single-post-project-roles-needed">
                        <p>
                        <strong>Roles Needed: </strong>
                        <?php 
                        echo $projectRolesNeeded;
                        ?>
                        </p>
                    </span>
                    <?php } ?>

                    <?php
                    $projectReportingDeadline = get_field('project_reporting_deadline');
                    if (!empty($projectReportingDeadline)){
                    ?>
                    <span class="single-post-project-reporting-deadline">
                        <p>
                        <strong>Reporting Deadline: </strong>
                        <?php 
                        echo $projectReportingDeadline;
                        ?>
                        </p>
                    </span>
                    <?php } ?>

                    <?php
                    $projectManagerContact = get_field('event_registration_deadline');
                    if (!empty($projectManagerContact)){
                    ?>
                    <span class="single-post-project-manager-contact">
                        <p>
                        <strong>Contact: </strong>
                        <?php 
                        echo $projectManagerContact;
                        ?>
                        </p>
                    </span>
                    <?php } ?>



                </div>
            </div>


            <div class="single-post-content">
                <?php the_post_thumbnail('medium_large'); ?>
                <?php the_content(); ?>
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
        </div>
        <?php
    } ?>
</div>
<?php
get_footer();

?>
