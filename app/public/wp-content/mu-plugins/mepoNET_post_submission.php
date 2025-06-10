<?php

// Include ACF plugin if needed
include_once(ABSPATH . 'wp-admin/includes/plugin.php');

// Error handler class (simple implementation)
function mepoNET_errors()
{
    static $wp_error;
    if (!$wp_error) {
        $wp_error = new WP_Error();
    }
    return $wp_error;
}

// Enqueue TinyMCE scripts and styles
function mepoNET_enqueue_tinymce_scripts()
{
    wp_enqueue_script('tiny-mce');
    wp_enqueue_style('editor-buttons');
}
add_action('admin_enqueue_scripts', 'mepoNET_enqueue_tinymce_scripts');

// Display error messages above the form
function mepoNET_register_messages()
{
    $errors = mepoNET_errors()->get_error_messages();
    if (!empty($errors)) {
        echo '<div class="mepoNET-error-messages" style="color:red; margin-bottom: 20px;">';
        foreach ($errors as $error) {
            echo '<p>' . esc_html($error) . '</p>';
        }
        echo '</div>';
    }
}

// Custom Post Submission Form
function mepoNET_post_submission_form()
{
    ob_start();

    // Show errors (if any)
    mepoNET_register_messages(); ?>

    <form id="mepoNET_post_submission_form" class="mepoNET_post_form" action="" method="POST" enctype="multipart/form-data">
        <h1>Add New Post</h1>
        <fieldset>
            <p class="input_container">
                <label for="post_title">
                    <h3><?php _e('Title'); ?></h3>
                </label>
                <input name="post_title" id="post_title" class="add_post_field" type="text" maxlength="150" required
                    value="<?php echo isset($_POST['post_title']) ? esc_attr($_POST['post_title']) : ''; ?>" />
            </p>

            <p class="input_container">
                <label for="post_content">
                    <h3><?php _e('Content'); ?></h3>
                </label>
                <?php
                $content = isset($_POST['post_content']) ? wp_kses_post($_POST['post_content']) : '';
                $editor_settings = array(
                    'textarea_name' => 'post_content',
                    'media_buttons' => false,
                    'teeny' => true,
                    'tinymce' => array(
                        'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo',
                        'toolbar2' => '',
                        'toolbar3' => '',
                    ),
                );
                wp_editor($content, 'post_content', $editor_settings);
                ?>
            </p>

            <p class="input_container">
                <label for="post_featured_image">
                    <h3><?php _e('Featured Image'); ?></h3>
                </label>
                <input class="post_featured_image" type="file" name="post_featured_image" id="post_featured_image"
                    accept="image/*" required onchange="displayThumbnail(this);">
            <div class="thumbnail-container" style="display: none;">
                <img id="thumbnail-preview" alt="Thumbnail Preview" class="thumbnail-preview"
                    style="max-width: 100px; max-height: 100px;">
                <button type="button" class="delete-thumbnail" onclick="deleteThumbnail();">X</button>
            </div>
            </p>

            <script>
                function displayThumbnail(input) {
                    var thumbnailContainer = document.querySelector('.thumbnail-container');
                    var thumbnailPreview = document.getElementById('thumbnail-preview');

                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            thumbnailContainer.style.display = 'flex';
                            thumbnailPreview.src = e.target.result;
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                }

                function deleteThumbnail() {
                    var thumbnailContainer = document.querySelector('.thumbnail-container');
                    var thumbnailPreview = document.getElementById('thumbnail-preview');
                    var inputFile = document.getElementById('post_featured_image');

                    thumbnailPreview.src = '';
                    thumbnailContainer.style.display = 'none';
                    inputFile.value = ''; // Clear the file input
                }
            </script>

            <p class="input_container">
                <label>
                    <h3><?php _e('Related Study Paths (Choose one or more)'); ?></h3>
                </label>
                <br>
                <?php
                $args = array(
                    'post_type' => 'study-path',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                );
                $study_paths = new WP_Query($args);

                if ($study_paths->have_posts()):
                    while ($study_paths->have_posts()):
                        $study_paths->the_post();
                        $study_path_id = get_the_ID();
                        $study_path_title = get_the_title();
                        ?>
                        <label>
                            <p>
                                <input type="checkbox" name="post_related_study_paths[]"
                                    value="<?php echo esc_attr($study_path_id); ?>" <?php
                                       if (!empty($_POST['post_related_study_paths']) && in_array($study_path_id, $_POST['post_related_study_paths'])) {
                                           echo 'checked';
                                       }
                                       ?>>
                                <?php echo esc_html($study_path_title); ?>
                            </p>
                        </label>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </p>

            <p class="input_container">
                <?php wp_nonce_field('mepoNET-post-submission', 'mepoNET-post-submission-nonce'); ?>
                <input type="submit" value="<?php _e('Submit Post'); ?>" class="post_submit_button" />
            </p>

        </fieldset>
    </form>

    <?php
    return ob_get_clean();
}

// Process Post Submission
function mepoNET_process_post_submission()
{
    if (isset($_POST['post_title']) && wp_verify_nonce($_POST['mepoNET-post-submission-nonce'], 'mepoNET-post-submission')) {

        $post_title = sanitize_text_field($_POST['post_title']);
        $post_content = wp_kses_post($_POST['post_content']);
        $post_author = get_current_user_id();

        // Validate required fields
        if (empty($post_title)) {
            mepoNET_errors()->add('empty_title', __('Title field is required.'));
        }
        if (empty($post_content)) {
            mepoNET_errors()->add('empty_content', __('Content field is required.'));
        }

        // If there are errors, do not proceed
        if (!empty(mepoNET_errors()->get_error_codes())) {
            return; // Stop processing, errors will show on form reload
        }

        // Insert post
        $post_id = wp_insert_post(array(
            'post_title' => $post_title,
            'post_content' => $post_content,
            'post_author' => $post_author,
            'post_status' => 'publish', // Change to 'publish' if needed
            'post_type' => 'post',    // Adjust if needed
        ));

        if (!is_wp_error($post_id)) {
            // Handle featured image upload if provided
            if (!empty($_FILES['post_featured_image']['name'])) {
                require_once ABSPATH . 'wp-admin/includes/image.php';
                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/media.php';

                $attachment_id = media_handle_upload('post_featured_image', $post_id);

                if (!is_wp_error($attachment_id)) {
                    set_post_thumbnail($post_id, $attachment_id);
                    update_post_meta($post_id, '_featured_image_id', $attachment_id);
                } else {
                    mepoNET_errors()->add('featured_image_error', __('Error uploading featured image.'));
                    return; // Stop if image upload failed
                }
            }

            // Save related study paths
            $related_study_paths = isset($_POST['post_related_study_paths']) ? array_map('intval', $_POST['post_related_study_paths']) : array();
            if (!empty($related_study_paths)) {
                update_post_meta($post_id, 'related_study_paths', $related_study_paths);
            }

            // Redirect on success (you can change this URL)
            wp_redirect(home_url());
            exit;
        } else {
            mepoNET_errors()->add('post_insert_error', __('Error creating post.'));
        }
    }
}

// Hook form shortcode and process submission
add_shortcode('post_submission_form', 'mepoNET_post_submission_form');
add_action('init', 'mepoNET_process_post_submission');