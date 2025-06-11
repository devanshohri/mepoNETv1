<?php

get_header();
?>



<div class="main-single-post">
    <?php
    while (have_posts()) {
        the_post(); ?>
        <div class="single-post">

            <div class="single-post-title">
                <h1><?php the_title(); ?></h1>
            </div>

            <div class="single-post-meta">
                <p>By: <?php the_author_posts_link(); ?></p>
                <p>Published: <?php the_time('n.j.y'); ?></p>
                <p><?php
                $relatedStudyPaths = get_field('related_study_paths');
                if ($relatedStudyPaths) {
                    foreach ($relatedStudyPaths as $studyPath) {
                        // Get the slug for CSS class
                        $study_path_slug = get_post_field('post_name', $studyPath);
                        ?>
                            <a href="<?php echo get_the_permalink($studyPath); ?>"
                                class=" study-path-tag-<?php echo esc_attr($study_path_slug); ?>">
                                <?php echo get_the_title($studyPath);
                                get_the_ID('study-path') ?>
                            </a>
                        <?php }
                }
                ?>
                </p>
            </div>

            <div class="single-post-content">
                <?php the_post_thumbnail('medium_large'); ?>
                <?php the_content(); ?>

            </div>


            <hr>
            <div class="single-post-comment">
                <?php
                global $post;

                $likeCount = new WP_Query([
                    'post_type' => 'like',
                    'meta_query' => [
                        [
                            'key' => 'liked_post_id',
                            'compare' => '=',
                            'value' => $post->ID,
                        ]
                    ],
                ]);

                $likeStatus = 'no';
                $existQuery = null;

                if (is_user_logged_in()) {
                    $existQuery = new WP_Query([
                        'author' => get_current_user_id(),
                        'post_type' => 'like',
                        'meta_query' => [
                            [
                                'key' => 'liked_post_id',
                                'compare' => '=',
                                'value' => $post->ID,
                            ]
                        ],
                    ]);

                    if ($existQuery->found_posts) {
                        $likeStatus = 'yes';
                    }
                }
                ?>
                <div class="post-bottom-bar-bttns">
                    <div class="like-bttn" data-like="<?php echo esc_attr($existQuery->posts[0]->ID ?? ''); ?>"
                        data-post="<?php echo esc_attr($post->ID); ?>" data-exists="<?php echo esc_attr($likeStatus); ?>">

                        <?php if ($likeStatus === 'no'): ?>
                            <span class="material-symbols-outlined" style="color:#f52929;">favorite</span>
                        <?php else: ?>
                            <span class="material-symbols-rounded" style="color:#f52929;">favorite</span>
                        <?php endif; ?>

                        <span class="like-count">
                            <h4><?php echo esc_html($likeCount->found_posts); ?></h4>
                        </span>
                    </div>

                    <div class="cmmt-bttn">
                        <span class="material-symbols-outlined">comment</span>
                        <h4><?php echo get_comments_number($post->ID); ?></h4>
                    </div>
                </div>
                <?php
                $args = array(
                    'number' => '30',
                    'post_id' => $post->ID
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
                $current_user_id = get_current_user_id();
                foreach ($comments as $comment):
                    // Check if current user ID matches comment's user ID
                    $is_user_comment = ($current_user_id > 0 && $current_user_id == $comment->user_id);
                    ?>
                    <div class="posted-comment" data-comment-id="<?php echo $comment->comment_ID; ?>">
                        <div class="posted-comment-content">
                            <p><a href="<?php echo esc_url(get_comment_link($comment)); ?>">
                                    <?php
                                    echo get_avatar($comment, 42);
                                    echo esc_html($comment->comment_author) . ": ";
                                    ?>
                                </a></p>

                            <p class="comment-itself">
                                <?php echo esc_html($comment->comment_content); ?>
                            </p>
                        </div>
                        <?php if ($is_user_comment) { ?>
                            <div class="delete-comment-bttn"><span class="material-symbols-outlined">delete</span></div>
                        <?php } ?>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
        <?php
    } ?>
</div>
<?php
get_footer();

?>