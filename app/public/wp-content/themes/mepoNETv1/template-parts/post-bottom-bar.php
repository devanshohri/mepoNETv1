<?php
/**
 * Template part for displaying the post bottom bar including likes and comments.
 *
 * Usage: get_template_part('template-parts/post', 'bottom-bar');
 */

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

<div class="post-bottom-bar">

    <div class="related-study-paths">
        <?php
        $relatedStudyPaths = get_field('related_study_paths');
        if ($relatedStudyPaths) {
            foreach ($relatedStudyPaths as $studyPath) {
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

    <div class="post-comments">
        <?php
        $args = [
            'number' => 3,
            'post_id' => $post->ID,
        ];

        $comments_args = [
            'label_submit' => 'Post',
            'title_reply' => '',
            'comment_notes_after' => '',
            'comment_field' => '<div class="textarea-container"><textarea class="comment-textarea" oninput="commentResize(this)" placeholder="Add a Comment for ' . esc_attr(get_the_author_meta('display_name', $post->post_author)) . '" name="comment" aria-required="true"></textarea></div>',
            'logged_in_as' => '',
        ];

        comment_form($comments_args, $post->ID);

        $comments = get_comments($args);
        if ($comments):
            foreach ($comments as $comment): ?>
                <p>
                    <a href="<?php echo esc_url(get_comment_link($comment)); ?>">
                        <strong>
                            <?php echo get_avatar($comment, 42); ?>
                            <?php echo esc_html($comment->comment_author) . ':'; ?>
                        </strong>
                    </a>
                </p>
                <p class="comment-itself"><?php echo esc_html($comment->comment_content); ?></p>
            <?php endforeach;
        endif;
        ?>
    </div>
</div>