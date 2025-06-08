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
                <p>Published: <?php the_time('n.j.y'); ?></p>
                <p><?php 
                        $relatedStudyPath = get_field('related_study_paths');
                        if ($relatedStudyPath) {
                        foreach($relatedStudyPath as $studyPath) { ?>
                            <li><a href="<?php echo get_the_permalink($studyPath); ?>"><?php echo get_the_title($studyPath) ?></a></li>
                        <?php }}
                    ?>
                </p>
            </div>

            <div class="single-post-content">
            <?php the_post_thumbnail('medium_large'); ?>            
                <?php the_content(); ?>

            </div>

            <div class="single-post-tags">
                 <?php 
                        $relatedStudyPath = get_field('related_study_paths');
                        if ($relatedStudyPath) {
                        echo'<h2>Related Study Path:</h2>';
                        foreach($relatedStudyPath as $studyPath) { ?>
                            <a href="<?php echo get_the_permalink($studyPath); ?>"><?php echo get_the_title($studyPath) ?></a>
                        <?php }}
                    ?>

            </div>
            <hr>
            <div class="single-post-comment">
            <h2>Comments (<?php echo get_comments_number(); ?>):</h2>
                                        <?php
                                                $args = array(
                                                    'number' => '30',
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
