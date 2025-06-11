<?php
/*
 * Plugin Name: MepoNET Profile Editor
 * Description: Handles user profile editing functionality
 * Version: 1.0
 */

if (!defined('ABSPATH')) exit;

class MepoNET_Profile_Editor {
    private $errors = [];

    public function __construct() {
        add_action('init', [$this, 'process_profile_update']);
        add_shortcode('edit_profile_form', [$this, 'edit_profile_form']);
    }

    public function add_error($context, $code, $message) {
        if (!isset($this->errors[$context])) {
            $this->errors[$context] = [];
        }
        $this->errors[$context][$code] = $message;
    }

    public function has_errors($context) {
        return !empty($this->errors[$context]);
    }

    public function display_messages($context) {
        if (isset($_GET[$context]) && $_GET[$context] === 'success') {
            echo '<div class="success-message">' . esc_html__('Profile updated successfully!', 'meponet') . '</div>';
        }

        if ($this->has_errors($context)) {
            echo '<div class="error-messages">';
            foreach ($this->errors[$context] as $error) {
                echo '<p class="error">' . esc_html($error) . '</p>';
            }
            echo '</div>';
        }
    }

    public function edit_profile_form() {
        if (!is_user_logged_in()) {
            return '<div class="edit-profile-container">' . esc_html__('You need to be logged in to edit your profile.', 'meponet') . '</div>';
        }

        return $this->edit_profile_fields();
    }

    private function edit_profile_fields() {
        $user_id = get_current_user_id();
        $user = get_userdata($user_id);

        // Get user meta values
        $user_bio = get_user_meta($user_id, 'description', true);
        $user_study_paths = function_exists('get_field') ? get_field('related_study_paths', 'user_' . $user_id) : [];
        $user_study_path_id = (!empty($user_study_paths) && is_array($user_study_paths)) ? $user_study_paths[0] : 0;
        $profile_picture_id = get_user_meta($user_id, 'profile_picture', true);
        $profile_picture_url = $profile_picture_id ? wp_get_attachment_url($profile_picture_id) : '';

        ob_start();
        ?>
        <div class="edit-profile-container">
            <div class="edit-profile-box">

                <?php $this->display_messages('edit_profile'); ?>

                <form id="mepoNET-edit-profile-form" class="mepoNET_edit_profile_form" action="" method="POST" enctype="multipart/form-data">
                    <?php wp_nonce_field('mepoNET_edit_profile_action', 'mepoNET_edit_profile_nonce'); ?>

                    <fieldset class="edit-profile-fieldset">

                        <div class="input_container">
                            <label for="mepoNET_edit_first_name"><?php esc_html_e('First Name', 'meponet'); ?> *</label>
                            <input type="text" name="mepoNET_edit_first_name" id="mepoNET_edit_first_name" class="reg_input_field" value="<?php echo esc_attr($user->first_name); ?>" required />
                        </div>

                        <div class="input_container">
                            <label for="mepoNET_edit_last_name"><?php esc_html_e('Last Name', 'meponet'); ?> *</label>
                            <input type="text" name="mepoNET_edit_last_name" id="mepoNET_edit_last_name" class="reg_input_field" value="<?php echo esc_attr($user->last_name); ?>" required />
                        </div>

                        <div class="input_container">
                            <label for="mepoNET_edit_email"><?php esc_html_e('Email', 'meponet'); ?> *</label>
                            <input type="email" name="mepoNET_edit_email" id="mepoNET_edit_email" class="reg_input_field" value="<?php echo esc_attr($user->user_email); ?>" required />
                        </div>

                        <div class="input_container">
                            <label for="mepoNET_edit_bio"><?php esc_html_e('About You', 'meponet'); ?></label>
                            <textarea name="mepoNET_edit_bio" id="mepoNET_edit_bio" class="reg_input_field" rows="3" maxlength="200"><?php echo esc_textarea($user->description); ?></textarea>
                        </div>

                        <div class="input_container">
                            <label for="mepoNET_edit_study_path"><?php esc_html_e('Related Study Path', 'meponet'); ?></label>
                            <select name="mepoNET_edit_study_path" id="mepoNET_edit_study_path" class="reg_input_field">
                                <option value=""><?php esc_html_e('Select Study Path', 'meponet'); ?></option>
                                <?php
                                $study_paths = get_posts([
                                    'post_type' => 'study-path',
                                    'posts_per_page' => -1,
                                    'orderby' => 'title',
                                    'order' => 'ASC',
                                ]);

                                foreach ($study_paths as $study_path) {
                                    $selected = ($study_path->ID == $user_study_path_id) ? 'selected' : '';
                                    echo '<option value="' . esc_attr($study_path->ID) . '" ' . $selected . '>' . esc_html($study_path->post_title) . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="input_container">
                            <label for="mepoNET_edit_profile_picture"><?php esc_html_e('Profile Picture', 'meponet'); ?></label>
                            <?php if ($profile_picture_url): ?>
                                <div class="profile-picture-preview">
                                    <img src="<?php echo esc_url($profile_picture_url); ?>" alt="<?php esc_attr_e('Profile Picture', 'meponet'); ?>" style="max-width:100px; max-height:100px;" />
                                </div>
                            <?php endif; ?>
                            <input type="file" name="mepoNET_edit_profile_picture" id="mepoNET_edit_profile_picture" accept="image/jpeg,image/png,image/gif" />
                            <small><?php esc_html_e('JPEG, PNG or GIF (Max 2MB)', 'meponet'); ?></small>
                        </div>


                        <div class="input_container">
                            <label for="mepoNET_edit_password"><?php esc_html_e('New Password', 'meponet'); ?></label>
                            <input type="password" name="mepoNET_edit_password" id="mepoNET_edit_password" class="reg_input_field" minlength="6" />
                            <small><?php esc_html_e('Leave blank to keep current password', 'meponet'); ?></small>
                        </div>

                        <div class="input_container">
                            <label for="mepoNET_edit_password_confirm"><?php esc_html_e('Confirm New Password', 'meponet'); ?></label>
                            <input type="password" name="mepoNET_edit_password_confirm" id="mepoNET_edit_password_confirm" class="reg_input_field" minlength="6" />
                        </div>

                    </fieldset>

                    <div class="edit-profile-submit-button">
                        <input type="submit" value="<?php esc_attr_e('Update Profile', 'meponet'); ?>" class="black-bttn" />
                    </div>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function process_profile_update() {
        if (!isset($_POST['mepoNET_edit_profile_nonce']) || 
            !wp_verify_nonce($_POST['mepoNET_edit_profile_nonce'], 'mepoNET_edit_profile_action')) {
            return;
        }

        if (!is_user_logged_in()) {
            return;
        }

        $user_id = get_current_user_id();

        $first_name = isset($_POST['mepoNET_edit_first_name']) ? sanitize_text_field($_POST['mepoNET_edit_first_name']) : '';
        $last_name = isset($_POST['mepoNET_edit_last_name']) ? sanitize_text_field($_POST['mepoNET_edit_last_name']) : '';
        $email = isset($_POST['mepoNET_edit_email']) ? sanitize_email($_POST['mepoNET_edit_email']) : '';
        $bio = isset($_POST['mepoNET_edit_bio']) ? sanitize_textarea_field($_POST['mepoNET_edit_bio']) : '';
        $selected_study_path = isset($_POST['mepoNET_edit_study_path']) ? absint($_POST['mepoNET_edit_study_path']) : 0;
        $new_password = isset($_POST['mepoNET_edit_password']) ? sanitize_text_field($_POST['mepoNET_edit_password']) : '';
        $password_confirm = isset($_POST['mepoNET_edit_password_confirm']) ? sanitize_text_field($_POST['mepoNET_edit_password_confirm']) : '';

        // Validate fields
        if (empty($first_name)) {
            $this->add_error('edit_profile', 'first_name_empty', __('Please enter your first name'));
        }

        if (empty($last_name)) {
            $this->add_error('edit_profile', 'last_name_empty', __('Please enter your last name'));
        }

        if (empty($email)) {
            $this->add_error('edit_profile', 'email_empty', __('Please enter your email'));
        } elseif (!is_email($email)) {
            $this->add_error('edit_profile', 'email_invalid', __('Please enter a valid email'));
        }

        // Password checks
        if ($new_password !== '') {
            if (strlen($new_password) < 6) {
                $this->add_error('edit_profile', 'password_short', __('Password must be at least 6 characters'));
            }

            if ($new_password !== $password_confirm) {
                $this->add_error('edit_profile', 'password_mismatch', __('Passwords do not match'));
            }
        }

        // File upload validation
        if (!empty($_FILES['mepoNET_edit_profile_picture']['name'])) {
            $file_type = $_FILES['mepoNET_edit_profile_picture']['type'];
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

            if (!in_array($file_type, $allowed_types)) {
                $this->add_error('edit_profile', 'invalid_file_type', __('Only JPEG, PNG, and GIF files are allowed.'));
            }

            if ($_FILES['mepoNET_edit_profile_picture']['size'] > 2 * 1024 * 1024) { // 2MB max
                $this->add_error('edit_profile', 'file_too_large', __('Profile picture must be less than 2MB.'));
            }
        }

        // If there are errors, do not proceed
        if ($this->has_errors('edit_profile')) {
            return;
        }

        // Update user info
        wp_update_user([
            'ID' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'user_email' => $email,
            'description' => $bio,
        ]);

        // Update study path meta (using ACF or normal meta)
        if ($selected_study_path) {
            if (function_exists('update_field')) {
                update_field('related_study_paths', [$selected_study_path], 'user_' . $user_id);
            } else {
                update_user_meta($user_id, 'related_study_paths', [$selected_study_path]);
            }
        }

        // Update password if provided
        if ($new_password !== '') {
            wp_set_password($new_password, $user_id);

            // Re-login user after password change to avoid logout
            wp_set_auth_cookie($user_id);
            wp_set_current_user($user_id);
        }

        // Handle profile picture upload
        if (!empty($_FILES['mepoNET_edit_profile_picture']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            $attachment_id = media_handle_upload('mepoNET_edit_profile_picture', 0);

            if (is_wp_error($attachment_id)) {
                $this->add_error('edit_profile', 'upload_error', __('There was an error uploading the profile picture.'));
                return;
            } else {
                update_user_meta($user_id, 'profile_picture', $attachment_id);
            }
        }

        // Redirect to avoid resubmission on page refresh, with success flag
        wp_redirect(add_query_arg('edit_profile', 'success', wp_get_referer() ?: home_url()));
        exit;
    }
}

new MepoNET_Profile_Editor();
