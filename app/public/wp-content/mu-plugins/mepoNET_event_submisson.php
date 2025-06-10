<?php
// Show messages
function mepoNET_event_submission_messages() {
    $errors = get_transient('mepoNET_event_submission_errors');
    if ($errors && is_wp_error($errors)) {
        echo '<div class="mepoNET-errors">';
        foreach ($errors->get_error_messages() as $message) {
            echo '<p style="color:red;">' . esc_html($message) . '</p>';
        }
        echo '</div>';
        delete_transient('mepoNET_event_submission_errors');
    }
}

// Form HTML
function mepoNET_event_submission_form() {
    ob_start(); ?>

    <?php mepoNET_event_submission_messages(); ?>

    <form id="mepoNET_event_submission_form" class="mepoNET_event_form" action="" method="POST" enctype="multipart/form-data">
        <h1>Add Event Project</h1>
        <fieldset>

            <!-- Title (optional) -->
            <p class="input_container">
                <label for="event_title"><h3><?php esc_html_e('Title'); ?></h3></label>
                <input name="event_title" id="event_title" type="text" maxlength="150" />
            </p>

            <!-- Content (optional) -->
            <p class="input_container">
                <label for="event_content"><h3><?php esc_html_e('Content'); ?></h3></label>
                <?php
                $content = isset($_POST['event_content']) ? wp_kses_post($_POST['event_content']) : '';
                wp_editor($content, 'event_content', [
                    'textarea_name' => 'event_content',
                    'media_buttons' => false,
                    'teeny' => true,
                ]);
                ?>
            </p>

            <!-- Featured Image -->
            <p class="input_container">
                <label for="event_featured_image"><h3><?php esc_html_e('Featured Image'); ?></h3></label>
                <input type="file" name="event_featured_image" id="event_featured_image" accept="image/*" onchange="previewEventImage(event)">
                <div id="event_image_preview" style="margin-top: 1rem;"></div>
            </p>

            <!-- Study Paths (optional) -->
            <p class="input_container">
                <label><h3><?php esc_html_e('Related Study Paths'); ?></h3></label>
                <?php
                $paths = new WP_Query(['post_type' => 'study-path', 'posts_per_page' => -1]);
                if ($paths->have_posts()) :
                    while ($paths->have_posts()) : $paths->the_post(); ?>
                        <label>
                            <input type="checkbox" name="event_related_study_paths[]" value="<?php echo esc_attr(get_the_ID()); ?>">
                            <?php echo esc_html(get_the_title()); ?>
                        </label><br>
                    <?php endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </p>

            <!-- Required Fields -->
            <p class="input_container">
                <label for="event_date"><h3><?php esc_html_e('Event Date *'); ?></h3></label>
                <input type="date" id="event_date" name="event_date" required>
            </p>

            <p class="input_container">
                <label for="event_time_start"><h3><?php esc_html_e('Event Time Start *'); ?></h3></label>
                <input type="time" id="event_time_start" name="event_time_start" required>
            </p>

            <p class="input_container">
                <label for="event_location"><h3><?php esc_html_e('Event Location *'); ?></h3></label>
                <input type="text" id="event_location" name="event_location" required>
            </p>

            <p class="input_container">
                <label for="event_entrance"><h3><?php esc_html_e('Event Entrance *'); ?></h3></label>
                <input type="text" id="event_entrance" name="event_entrance" required>
            </p>

            <!-- Optional Fields -->
            <p class="input_container">
                <label for="event_time_end"><h3><?php esc_html_e('Event Time End'); ?></h3></label>
                <input type="time" id="event_time_end" name="event_time_end">
            </p>

            <p class="input_container">
                <label for="event_language"><h3><?php esc_html_e('Event Language'); ?></h3></label>
                <input type="text" id="event_language" name="event_language">
            </p>

            <p class="input_container">
                <label for="event_registration_deadline"><h3><?php esc_html_e('Registration Deadline'); ?></h3></label>
                <input type="date" id="event_registration_deadline" name="event_registration_deadline">
            </p>

            <p class="input_container">
                <label for="event_registration_website"><h3><?php esc_html_e('Registration Website'); ?></h3></label>
                <input type="url" id="event_registration_website" name="event_registration_website">
            </p>

            <p class="input_container">
                <label for="event_organiser"><h3><?php esc_html_e('Organiser'); ?></h3></label>
                <input type="text" id="event_organiser" name="event_organiser">
            </p>

            <p class="input_container">
                <label for="event_organiser_website"><h3><?php esc_html_e('Organiser Website'); ?></h3></label>
                <input type="url" id="event_organiser_website" name="event_organiser_website">
            </p>

            <p class="input_container">
                <?php wp_nonce_field('mepoNET-event-submission', 'mepoNET-event-submission-nonce'); ?>
                <input class="post_submit_button" type="submit" value="<?php esc_html_e('Submit Event'); ?>" />
            </p>
        </fieldset>
    </form>

    <script>
    function previewEventImage(event) {
        const previewContainer = document.getElementById('event_image_preview');
        previewContainer.innerHTML = '';
        const file = event.target.files[0];
        if (!file) return;
        if (!file.type.startsWith('image/')) {
            previewContainer.innerHTML = '<p style="color:red;">Please select a valid image file.</p>';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '100%';
            img.style.maxHeight = '200px';
            img.style.borderRadius = '8px';
            previewContainer.appendChild(img);
        }
        reader.readAsDataURL(file);
    }
    </script>

    <?php return ob_get_clean();
}
add_shortcode('mepo_event_form', 'mepoNET_event_submission_form');


// Process the form
function mepoNET_process_event_submission() {
    if (!isset($_POST['mepoNET-event-submission-nonce']) || !wp_verify_nonce($_POST['mepoNET-event-submission-nonce'], 'mepoNET-event-submission')) return;

    $errors = new WP_Error();

    // Required
    $event_date     = sanitize_text_field($_POST['event_date']);
    $event_time_start = sanitize_text_field($_POST['event_time_start']);
    $event_location = sanitize_text_field($_POST['event_location']);
    $event_entrance = sanitize_text_field($_POST['event_entrance']);

    if (!$event_date) $errors->add('missing_date', 'Event date is required.');
    if (!$event_time_start) $errors->add('missing_start', 'Event start time is required.');
    if (!$event_location) $errors->add('missing_location', 'Event location is required.');
    if (!$event_entrance) $errors->add('missing_entrance', 'Event entrance info is required.');

    // Optional
    $event_title = sanitize_text_field($_POST['event_title']);
    $event_content = wp_kses_post($_POST['event_content']);
    $related_study_paths = isset($_POST['event_related_study_paths']) ? array_map('intval', $_POST['event_related_study_paths']) : [];
    $event_time_end = sanitize_text_field($_POST['event_time_end']);
    $event_language = sanitize_text_field($_POST['event_language']);
    $event_registration_deadline = sanitize_text_field($_POST['event_registration_deadline']);
    $event_registration_website = esc_url_raw($_POST['event_registration_website']);
    $event_organiser = sanitize_text_field($_POST['event_organiser']);
    $event_organiser_website = esc_url_raw($_POST['event_organiser_website']);

    if ($errors->has_errors()) {
        set_transient('mepoNET_event_submission_errors', $errors, 30);
        return;
    }

    $event_id = wp_insert_post([
        'post_title'   => $event_title,
        'post_content' => $event_content,
        'post_type'    => 'event',
        'post_status'  => 'publish',
        'post_author'  => get_current_user_id(),
    ]);

    if (is_wp_error($event_id)) {
        $errors->add('event_error', 'Could not save event.');
        set_transient('mepoNET_event_submission_errors', $errors, 30);
        return;
    }

    // Handle image
    if (!empty($_FILES['event_featured_image']['name'])) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $attachment_id = media_handle_upload('event_featured_image', $event_id);
        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($event_id, $attachment_id);
        }
    }

    // Meta
    update_post_meta($event_id, 'event_date', $event_date);
    update_post_meta($event_id, 'event_time_start', $event_time_start);
    update_post_meta($event_id, 'event_time_end', $event_time_end);
    update_post_meta($event_id, 'event_location', $event_location);
    update_post_meta($event_id, 'event_entrance', $event_entrance);
    update_post_meta($event_id, 'event_language', $event_language);
    update_post_meta($event_id, 'event_registration_deadline', $event_registration_deadline);
    update_post_meta($event_id, 'event_registration_website', $event_registration_website);
    update_post_meta($event_id, 'event_organiser', $event_organiser);
    update_post_meta($event_id, 'event_organiser_website', $event_organiser_website);
    update_post_meta($event_id, 'related_study_paths', $related_study_paths);

    wp_redirect(home_url());

    exit;
}
add_action('init', 'mepoNET_process_event_submission');
