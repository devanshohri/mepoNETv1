<?php

//Project SUBMIT--------------------------------------------------------------------------------------------------------------/
//----------------------------------------Project Submit-----------------------------------------------------------------------/
//ProjectSubmit-----------------------------------------------------------------------------------------------------------------/



// Include ACF
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );





// Custom Project Submission Form
function mepoNET_project_submission_form() {
    ob_start(); ?>

    <?php // Display any error messages after form submission
    mepoNET_project_submission_messages(); ?>

    <form id="mepoNET_project_submission_form" class="mepoNET_project_form" action="" method="POST" enctype="multipart/form-data">
        <h1>Add New Project</h1>
        <fieldset>
            <p class="input_container">
                <label for="project_title"><h3><?php _e('Title'); ?></h3></label>
                <input name="project_title" id="project_title" class="add_post_field" type="text" maxlength="150" required />
            </p>
            <p class="input_container">
                <label for="project_content"><h3><?php _e('Content'); ?></h3></label>
                <?php
                    $content = isset($_POST['project_content']) ? wp_kses_post($_POST['project_content']) : '';
                    $editor_settings = array(
                        'textarea_name' => 'project_content',
                        'media_buttons' => false,
                        'teeny' => true,
                        'tinymce'       => array(
                            'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo',
                            'toolbar2'      => '',
                            'toolbar3'      => '',
                        ),
                    );
                    wp_editor($content, 'project_content', $editor_settings);
                ?>
            </p>

            <div class="add-project-field-container">

            <div class="project-field-container-1">

            <p class="input_container">
                <label for="project_featured_image"><h3><?php _e('Featured Image'); ?></h3></label>
                <input class="project_featured_image" type="file" name="project_featured_image" id="project_featured_image" accept="image/*"  onchange="displayThumbnail(this, 'project');">
                <div class="thumbnail-container" style="display: none;">
                    <img id="project-thumbnail-preview" alt="Thumbnail Preview" class="thumbnail-preview" style="max-width: 100px; max-height: 100px;">
                    <button type="button" class="delete-thumbnail" onclick="deleteThumbnail('project');">X</button>
                </div>
            </p>

            <script>
                function displayThumbnail(input, type) {
                    var thumbnailContainer = document.querySelector('.thumbnail-container');
                    var thumbnailPreview = document.getElementById(type + '-thumbnail-preview');

                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            thumbnailContainer.style.display = 'flex';
                            thumbnailPreview.src = e.target.result;
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                }

                function deleteThumbnail(type) {
                    var thumbnailContainer = document.querySelector('.thumbnail-container');
                    var thumbnailPreview = document.getElementById(type + '-thumbnail-preview');

                    thumbnailPreview.src = '';
                    thumbnailContainer.style.display = 'none';
                }
            </script>


            <p class="input_container">
                <label><h3><?php _e('Related Study Paths (Choose one or more)'); ?></h3></label>
                <br>
                <?php
                // Replace 'related_study_paths' with your actual ACF field name
                $args = array(
                    'post_type' => 'study-path',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                );
                $study_paths = new WP_Query($args);

                if ($study_paths->have_posts()) :
                    while ($study_paths->have_posts()) : $study_paths->the_post();
                        $study_path_id = get_the_ID();
                        $study_path_title = get_the_title();
                        ?>
                        <label>
                            <p>
                            <input type="checkbox" name="project_related_study_paths[]" value="<?php echo esc_attr($study_path_id); ?>">
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
                <label><h3><?php _e('Project Manager'); ?><span style="color:red">*</span></h3></label>
                <input type="text" name="project_post_manager" id="project_post_manager" class="project_post_field" required>
            </p>

            <p class="input_container">
                <label><h3><?php _e('Project Start Date'); ?><span style="color:red">*</span></h3></label>
                <input type="date" name="project_post_start_date" id="project_post_start_date" class="project_post_field" required>
            </p>

            <p class="input_container">
                <label><h3><?php _e('Project End Date'); ?><span style="color:red">*</span></h3></label>
                <input type="date" name="project_post_end_date" id="project_post_end_date" class="project_post_field" required>
            </p>

            <p class="input_container">
                <label><h3><?php _e('Project Team Size'); ?><span style="color:red">*</span></h3></label>
                <input type="number" name="project_post_team_size" id="project_post_team_size" class="project_post_field" required>
            </p>

            <p class="input_container">
                <label><h3><?php _e('Project Roles Needed'); ?><span style="color:red">*</span></h3></label>
                <textarea type="text" placeholder="Address, Online, Zoom meeting..." name="project_post_roles_needed" id="project_post_roles_needed" class="project_post_field"  rows="5" cols="30" required style="resize: none;"></textarea>
            </p>

            </div>

            <div class="project-field-container-1">

            <p class="input_container">
                <label><h3><?php _e('Project Reporting Deadline'); ?><span style="color:red">*</span></h3></label>
                <input type="date" name="project_post_reporting_deadline" id="project_post_reporting_deadline" class="project_post_field" >
            </p>

            <p class="input_container">
                <label><h3><?php _e('Project Manager Contact'); ?><span style="color:red">*</span></h3></label>
                <input type="email" name="project_post_manager_contact" id="project_post_manager_contact" class="project_post_field" >
            </p>

            </div>
            
            </div>

            <p class="input_container">
                <?php wp_nonce_field('mepoNET-project-submission', 'mepoNET-project-submission-nonce'); ?>
                <input type="submit" value="<?php _e('Submit Project'); ?>" class="post_submit_button" />
            </p>

            

        </fieldset>
    </form>
    <?php
    return ob_get_clean();
}

// Process Project Submission
function mepoNET_process_project_submission() {
    if ( isset($_POST['project_title']) && wp_verify_nonce($_POST['mepoNET-project-submission-nonce'], 'mepoNET-project-submission')) {

        $project_title = sanitize_text_field($_POST['project_title']);
        $project_content = wp_kses_post($_POST['project_content']);
        $project_author = get_current_user_id();
        $related_study_paths = isset($_POST['project_related_study_paths']) ? $_POST['project_related_study_paths'] : array();
        $project_manager = sanitize_text_field($_POST['project_post_manager']);
        $project_start_date = sanitize_text_field($_POST['project_post_start_date']);
        $project_end_date = sanitize_text_field($_POST['project_post_end_date']);
        $project_team_size = sanitize_text_field($_POST['project_post_team_size']);
        $project_roles_needed = sanitize_text_field($_POST['project_post_roles_needed']);
        $project_reporting_deadline = sanitize_text_field($_POST['project_post_reporting_deadline']);
        $project_manager_contact = sanitize_text_field($_POST['project_post_manager_contact']);
        
        
        // ERRORS
        $errors = mepoNET_add_project_errors();

        if($project_title == '') {
            // empty title
            $errors->add('title_empty', __('Please enter a title'));
        }
        if($project_content  == '') {
          // empty text content
          $errors->add('content_empty', __('Please enter Text Content'));
        }

        if (empty($related_study_paths)) {
            $errors->add('study_path_empty', __('Please select a study path'));
        }

        if (empty($project_manager)) {
            $errors->add('project_manager_empty', __('Please add Project Manager'));
        }

        if (empty($project_start_date)) {
            $errors->add('project_start_date_empty', __('Please add project start date'));
        }

        if (empty($project_end_date)) {
            $errors->add('project_end_date_empty', __('Please add project end date'));
        }

        if (empty($project_team_size)) {
            $errors->add('project_team_size_empty', __('Please add project team size'));
        }

        if($errors->get_error_code()) {
            mepoNET_project_submission_messages();
            return;
        }

        // Create a new project
        $project_id = wp_insert_post(array(
            'post_title'   => $project_title,
            'post_content' => $project_content,
            'post_author'  => $project_author,
            'post_status'  => 'publish',
            'post_type'    => 'project',
        ));

        if (!is_wp_error($project_id)) {
            if (!empty($_FILES['project_featured_image']['name'])) {
                require_once ABSPATH . 'wp-admin/includes/image.php';
                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/media.php';

                $attachment_id = media_handle_upload('project_featured_image', $project_id);

                if (!is_wp_error($attachment_id)) {
                    set_post_thumbnail($project_id, $attachment_id);
                    update_post_meta($project_id, '_featured_image_id', $attachment_id);
                } else {
                    $errors->add('featured_image_error', __('Error uploading featured image.'));
                }
            }

            if (!empty($related_study_paths)) {
                update_post_meta($event_id, 'related_study_paths', $related_study_paths);
            }

            if (!empty($project_manager)) {
                update_post_meta($project_id, 'project_manager', $project_manager);
            }

            if (!empty($project_start_date)) {
                update_post_meta($project_id, 'project_start_date', $project_start_date);
            }

            if (!empty($project_end_date)) {
                update_post_meta($project_id, 'project_end_date', $project_end_date);
            }

            if (!empty($project_team_size)) {
                update_post_meta($project_id, 'project_team_size', $project_team_size);
            }

            if (!empty($project_roles_needed)) {
                update_post_meta($project_id, 'project_roles_needed', $project_roles_needed);
            }

            if (!empty($project_reporting_deadline)) {
                update_post_meta($project_id, 'project_reporting_deadline', $project_reporting_deadline);
            }

            if (!empty($project_manager_contact)) {
                update_post_meta($project_id, 'project_manager_contact', $project_manager_contact);
            }

            wp_redirect(get_permalink($project_id));
            exit;
        }
    }
}

// Hook the form and submission processing
add_shortcode('project_submission_form', 'mepoNET_project_submission_form');
add_action('init', 'mepoNET_process_project_submission');

function mepoNET_add_project_errors(){
    static $wp_error;
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error());
}

function mepoNET_project_submission_messages() {
    if($codes = mepoNET_add_project_errors()->get_error_codes()) {
        echo '<div class="mepoNET_errors">';
        foreach($codes as $code){
            $message = mepoNET_add_project_errors()->get_error_message($code);
            echo '<span class="reg_error_head"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
        }
        echo '</div>';
    }    
}
