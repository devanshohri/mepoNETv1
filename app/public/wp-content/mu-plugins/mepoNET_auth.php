<?php
/*
 * Plugin Name: MepoNET Authentication System
 * Description: Custom login and registration system with enhanced features
 * Version: 2.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) exit;

class MepoNET_Auth_System {
    
    public function __construct() {
        // Registration shortcodes
        add_shortcode('register_form', array($this, 'registration_form'));
        
        // Login shortcodes
        add_shortcode('login_form', array($this, 'login_form'));
        
        // Actions
        add_action('init', array($this, 'process_registration'));
        add_action('init', array($this, 'process_login'));
        add_action('wp_logout', array($this, 'redirect_after_logout'));
        
        // Filters
        add_filter('get_avatar', array($this, 'custom_get_avatar'), 10, 5);
        add_filter('login_redirect', array($this, 'login_redirect'), 10, 3);
        
        // User registration hooks
        add_action('user_register', array($this, 'update_user_study_paths'));
        
    }

    /* ======================
     * REGISTRATION FUNCTIONS
     * ====================== */
    
    public function registration_form() {
        if(!is_user_logged_in()) {
            $registration_enabled = get_option('users_can_register');
            
            if($registration_enabled) {
                return $this->registration_fields();
            } else {
                $admin_url = admin_url('options-general.php');
                return '<div class="register-container">' . 
                    sprintf(__('User registration is disabled. Please <a href="%s">enable it in WordPress settings</a> or contact the site administrator.'), $admin_url) . 
                    '</div>';
            }
        }
        return '<div class="register-container">' . __('You are already logged in.') . '</div>';
    }

    private function registration_fields() {
        ob_start(); ?>
        <div class="register-container">
            <div class="register-box">
                <h3 class="mepoNET_reg_header"><?php _e('Register New Account'); ?></h3>
                
                <?php $this->display_messages('registration'); ?>
                
                <form id="mepoNET-registration-form" class="mepoNET_reg_form" action="" method="POST" enctype="multipart/form-data">
                    <?php wp_nonce_field('mepoNET_register_action', 'mepoNET_register_nonce'); ?>
                    
                    <fieldset class="mepoNET_reg_fieldset">
                        <div class="reg-part-1">
                            <div class="input_container">
                                <label for="mepoNET_user_username"><?php _e('Username'); ?> *</label>
                                <input name="mepoNET_user_username" id="mepoNET_user_username" class="reg_input_field" type="text" required />
                            </div>
                            
                            <div class="input_container">
                                <label for="mepoNET_user_email"><?php _e('Email'); ?> *</label>
                                <input name="mepoNET_user_email" id="mepoNET_user_email" class="reg_input_field" type="email" required />
                            </div>
                            
                            <div class="input_container">
                                <label for="password"><?php _e('Password'); ?> *</label>
                                <input name="mepoNET_user_pass" id="password" class="reg_input_field" type="password" required minlength="6" />
                                <small><?php _e('Minimum 6 characters'); ?></small>
                            </div>
                            
                            <div class="input_container">
                                <label for="password_again"><?php _e('Confirm Password'); ?> *</label>
                                <input name="mepoNET_user_pass_confirm" id="password_again" class="reg_input_field" type="password" required />
                            </div>

                            <div class="input_container">
                                <label for="mepoNET_user_study_path"><?php _e('Related Study Path'); ?></label>
                                <select name="mepoNET_user_study_path" id="mepoNET_user_study_path" class="reg_input_field">
                                    <option value=""><?php _e('Select Study Path'); ?></option>
                                    <?php
                                    $study_paths = get_posts(array(
                                        'post_type' => 'study-path',
                                        'posts_per_page' => -1,
                                        'orderby' => 'title',
                                        'order' => 'ASC'
                                    ));

                                    foreach ($study_paths as $study_path) {
                                        echo '<option value="' . esc_attr($study_path->ID) . '">' . esc_html($study_path->post_title) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="reg-part-2">
                            <div class="input_container">
                                <label for="mepoNET_user_first"><?php _e('First Name'); ?> *</label>
                                <input name="mepoNET_user_first" id="mepoNET_user_first" type="text" class="reg_input_field" required />
                            </div>
                            
                            <div class="input_container">
                                <label for="mepoNET_user_last"><?php _e('Last Name'); ?> *</label>
                                <input name="mepoNET_user_last" id="mepoNET_user_last" type="text" class="reg_input_field" required />
                            </div>
                            
                            <div class="input_container">
                                <label><?php _e('Select Role:'); ?> *</label>
                                <div>
                                    <label>
                                        <input type="radio" name="mepoNET_user_role" value="student" required checked />
                                        <?php _e('Student'); ?>
                                    </label>
                                    <label>
                                        <input type="radio" name="mepoNET_user_role" value="faculty" />
                                        <?php _e('Faculty'); ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="input_container">
                                <label for="mepoNET_user_profile_picture"><?php _e('Profile Picture'); ?></label>
                                <input type="file" name="mepoNET_user_profile_picture" id="mepoNET_user_profile_picture"  accept="image/jpeg,image/png,image/gif" />
                                <small><?php _e('JPEG, PNG or GIF (Max 2MB)'); ?></small>
                            </div>

                             <div class="input_container">
                                <label for="mepoNET_user_bio"><?php _e('About You'); ?></label>
                                <textarea name="mepoNET_user_bio" id="mepoNET_user_bio" class="reg_input_field" rows="3" maxlength="200"></textarea>
                            </div>
                        </div>
                    </fieldset>
                    
                    
                    <div class="register-login-button">
                        <input type="submit" value="<?php _e('Register Account'); ?>" class="black-bttn" />
                    </div>
                    
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /* ==================
     * LOGIN FUNCTIONS
     * ================== */
    
    public function login_form($atts = array(), $content = null) {
        if(!is_user_logged_in()) {
            return $this->login_fields();
        }
        return '<div class="login-container">' . __('You are already logged in.') . '</div>';
    }

    private function login_fields() {
        // Check for redirect parameter
        $redirect_to = isset($_REQUEST['redirect_to']) ? esc_url($_REQUEST['redirect_to']) : '';
        
        ob_start(); ?>
        <div class="login-container">
            <div class="login-box">
                <h3 class="mepoNET_login_header"><?php _e('Login to Your Account'); ?></h3>
                
                <?php $this->display_messages('login'); ?>
                
                <form id="mepoNET-login-form" class="mepoNET_user_login" action="" method="POST">
                    <?php wp_nonce_field('mepoNET_login_action', 'mepoNET_login_nonce'); ?>
                    
                    <?php if($redirect_to): ?>
                        <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
                    <?php endif; ?>
                    
                    <fieldse class="login-fieldset">
                        <div class="input_container">
                            <label for="mepoNET_login_username"><?php _e('Username or Email'); ?> *</label>
                            <input name="mepoNET_login_username" id="mepoNET_login_username" class="login_input_field" type="text" required />
                        </div>
                        
                        <div class="input_container">
                            <label for="mepoNET_login_password"><p><?php _e('Password'); ?> *</p></label>
                            <input name="mepoNET_login_password" id="mepoNET_login_password" class="login_input_field" type="password" required />
                        </div>
                        
                        <div class="input_container">
                            <label>
                                <input name="rememberme" type="checkbox" id="rememberme" value="forever" />
                                <?php _e('Remember Me'); ?>
                            </label>
                        </div>
                        
                        <div class="login-register-button">
                            <input type="submit" value="<?php _e('Log In'); ?>" class="black-bttn" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /* ========================
     * PROCESSING FUNCTIONS
     * ======================== */
    
    public function process_registration() {
        if (!isset($_POST['mepoNET_register_nonce']) || 
            !wp_verify_nonce($_POST['mepoNET_register_nonce'], 'mepoNET_register_action')) {
            return;
        }

        // Sanitize and validate all inputs
        $user_login = sanitize_user($_POST['mepoNET_user_username']);
        $user_email = sanitize_email($_POST['mepoNET_user_email']);
        $user_first = sanitize_text_field($_POST['mepoNET_user_first']);
        $user_last = sanitize_text_field($_POST['mepoNET_user_last']);
        $user_pass = $_POST['mepoNET_user_pass'];
        $pass_confirm = $_POST['mepoNET_user_pass_confirm'];
        $user_bio = sanitize_textarea_field($_POST['mepoNET_user_bio']);
        $user_role = sanitize_text_field($_POST['mepoNET_user_role']);
        $selected_study_path = isset($_POST['mepoNET_user_study_path']) ? absint($_POST['mepoNET_user_study_path']) : 0;

        // Validate all fields
        if (empty($user_login)) {
            $this->add_error('registration', 'username_empty', __('Please enter a username'));
        } elseif (username_exists($user_login)) {
            $this->add_error('registration', 'username_exists', __('Username already exists'));
        }

        if (empty($user_email)) {
            $this->add_error('registration', 'email_empty', __('Please enter an email'));
        } elseif (!is_email($user_email)) {
            $this->add_error('registration', 'email_invalid', __('Invalid email address'));
        } elseif (email_exists($user_email)) {
            $this->add_error('registration', 'email_exists', __('Email already registered'));
        }

        if (empty($user_pass)) {
            $this->add_error('registration', 'password_empty', __('Please enter a password'));
        } elseif (strlen($user_pass) < 6) {
            $this->add_error('registration', 'password_short', __('Password must be at least 6 characters'));
        } elseif ($user_pass !== $pass_confirm) {
            $this->add_error('registration', 'password_mismatch', __('Passwords do not match'));
        }

        if (empty($user_first)) {
            $this->add_error('registration', 'first_name_empty', __('Please enter your first name'));
        }

        if (empty($user_last)) {
            $this->add_error('registration', 'last_name_empty', __('Please enter your last name'));
        }

        // If no errors, create user
        if (!$this->has_errors('registration')) {
            $userdata = array(
                'user_login' => $user_login,
                'user_email' => $user_email,
                'user_pass' => $user_pass,
                'first_name' => $user_first,
                'last_name' => $user_last,
                'description' => $user_bio,
                'role' => $user_role
            );

            $user_id = wp_insert_user($userdata);

            if (!is_wp_error($user_id)) {
                // Handle profile picture upload
                if (!empty($_FILES['mepoNET_user_profile_picture']['name'])) {
                    require_once ABSPATH . 'wp-admin/includes/image.php';
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                    require_once ABSPATH . 'wp-admin/includes/media.php';
                    
                    $attachment_id = media_handle_upload('mepoNET_user_profile_picture', 0);
                    
                    if (!is_wp_error($attachment_id)) {
                        update_user_meta($user_id, 'profile_picture', $attachment_id);
                    }
                }

                // Set study path
                if ($selected_study_path > 0) {
                    update_field('related_study_paths', array($selected_study_path), 'user_' . $user_id);
                }

                // Send notifications
                wp_new_user_notification($user_id, null, 'both');

                // Log the user in
                wp_clear_auth_cookie();
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
                
                // Redirect to prevent form resubmission
                wp_redirect(add_query_arg('registration', 'success', home_url()));
                exit;
            } else {
                $this->add_error('registration', 'registration_failed', __('Registration failed. Please try again.'));
            }
        }
    }

    public function process_login() {
        if (!isset($_POST['mepoNET_login_nonce']) || 
            !wp_verify_nonce($_POST['mepoNET_login_nonce'], 'mepoNET_login_action')) {
            return;
        }

        $credentials = array(
            'user_login'    => sanitize_user($_POST['mepoNET_login_username']),
            'user_password' => $_POST['mepoNET_login_password'],
            'remember'      => isset($_POST['rememberme']) ? true : false
        );

        // Check for empty fields
        if (empty($credentials['user_login'])) {
            $this->add_error('login', 'username_empty', __('Please enter a username or email'));
        }
        
        if (empty($credentials['user_password'])) {
            $this->add_error('login', 'password_empty', __('Please enter your password'));
        }

        if (!$this->has_errors('login')) {
            $user = wp_signon($credentials, false);
            
            if (is_wp_error($user)) {
                $this->add_error('login', 'login_failed', __('Invalid username or password'));
            } else {
                $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : home_url();
                wp_redirect($redirect_to);
                exit;
            }
        }
    }

    /* ======================
     * UTILITY FUNCTIONS
     * ====================== */
    
    public function redirect_after_logout() {
        wp_redirect(home_url());
        exit();
    }
    
    public function login_redirect($redirect_to, $requested_redirect_to, $user) {
        // Redirect to custom URL after login if no specific redirect requested
        if (empty($requested_redirect_to)) {
            return home_url();
        }
        return $redirect_to;
    }

    public function custom_get_avatar($avatar, $id_or_email, $size, $default, $alt) {
        $user = false;
        
        if (is_numeric($id_or_email)) {
            $id = (int) $id_or_email;
            $user = get_user_by('id', $id);
        } elseif (is_object($id_or_email)) {
            if (!empty($id_or_email->user_id)) {
                $id = (int) $id_or_email->user_id;
                $user = get_user_by('id', $id);
            }
        } else {
            $user = get_user_by('email', $id_or_email);
        }
        
        if ($user && is_object($user)) {
            $profile_picture = get_user_meta($user->ID, 'profile_picture', true);
            
            if ($profile_picture) {
                $image = wp_get_attachment_image_src($profile_picture, array($size, $size));
                if ($image) {
                    return '<img src="' . $image[0] . '" width="' . $size . '" height="' . $size . '" alt="' . esc_attr($alt) . '" class="avatar avatar-' . $size . '" />';
                }
            }
        }
        
        return $avatar;
    }

    public function update_user_study_paths($user_id) {
        if (isset($_POST['mepoNET_user_study_path'])) {
            $study_path_id = absint($_POST['mepoNET_user_study_path']);
            if ($study_path_id > 0) {
                update_field('related_study_paths', array($study_path_id), 'user_' . $user_id);
            }
        }
    }

    /* ======================
     * ERROR HANDLING
     * ====================== */
    
    private function add_error($context, $code, $message) {
        $errors = $this->errors($context);
        $errors->add($code, $message);
    }

    private function has_errors($context = '') {
        $errors = $this->errors($context);
        return $errors->has_errors();
    }

    private function errors($context = '') {
        static $errors = array();
        
        if (!isset($errors[$context])) {
            $errors[$context] = new WP_Error();
        }
        
        return $errors[$context];
    }

    private function display_messages($context = '') {
        $errors = $this->errors($context);
        
        if ($codes = $errors->get_error_codes()) {
            echo '<div class="mepoNET-errors">';
            foreach ($codes as $code) {
                $message = $errors->get_error_message($code);
                echo '<div class="mepoNET-error"><strong>' . __('Error') . ':</strong> ' . esc_html($message) . '</div>';
            }
            echo '</div>';
        }
        
        // Display success messages
        if (isset($_GET['registration']) && $_GET['registration'] === 'success') {
            echo '<div class="mepoNET-success">' . __('Registration successful! You are now logged in.') . '</div>';
        }
    }
}

// Initialize the plugin
new MepoNET_Auth_System();