<?php

//Include ACF
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

//Like Route
require get_theme_file_path('/inc/like-route.php');

//Follow Route
require get_theme_file_path('/inc/follow-route.php');

//Search Route
require get_theme_file_path('/inc/search-route.php');

function mepoNET_custom_rest() {
    register_rest_field('post', 'authorName', array(
        'get_callback' => function() {return get_the_author();}
    ));
}


//Files and Scripts
function mepoNET_files() {
    wp_enqueue_style('mepoNET_main_styles', get_stylesheet_uri());
    wp_enqueue_script('main-mepoNET-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    wp_enqueue_script('your-script', get_template_directory_uri() . '/src/textarea.js' ); //text area auto resize
    wp_enqueue_script('sidebar-dropdown', get_template_directory_uri() . '/src/sidebar-dropdown.js', array(), '1.0', true);

    wp_localize_script('main-mepoNET-js', 'mepoNETdata', array(
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')
    ));
}

add_action('wp_enqueue_scripts', 'mepoNET_files');




//Features
function mepoNET_features() {
    register_nav_menu('main-menu',__( 'Main Menu'));
    register_nav_menu('sub-menu',__( 'Sub Menu'));
    add_theme_support('title-tag');
    add_theme_support( 'post-thumbnails' );
}

add_action('after_setup_theme', 'mepoNET_features');



//comment author url
function your_get_comment_author_link () {
    global $comment;

    if ($comment->user_id == '0') {
        if (!empty ($comment->comment_author_url)) {
            $url = $comment->comment_author_url;
        } else {
            $url = '#';
        }
    } else {
        $url = get_author_posts_url($comment->user_id);
    }

    echo $url;
}


// Upcoming Events Query (Past events filter)       
function mepoNET_adjust_queries($query) {
    if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric',
            )
        ));
    }
}

add_action('pre_get_posts', 'mepoNET_adjust_queries');



//HOME query
function modify_main_home_query( $query ) {
    if ( !is_admin() && $query->is_main_query() && $query->is_home() ) {
        $query->set( 'post_type', array( 'post', 'event', 'project' ) );
        $query->set( 'posts_per_page', 8 );
        // You can add more modifications if needed
    }
}
add_action( 'pre_get_posts', 'modify_main_home_query' );


// Load Dashicons
function load_dashicons(){
    wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'load_dashicons');

//Redirect student and faculty accounts to home
add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend() {
    $outCurrentUser = wp_get_current_user();
    
    if (in_array('faculty', $outCurrentUser->roles) || in_array('student', $outCurrentUser->roles)) {
        wp_redirect(site_url('/'));
        exit;
    }

}

//Remove admin bar for student and faculty accounts
add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar() {
    $outCurrentUser = wp_get_current_user();
    
    // Check if the user has exactly one role and that role is either 'student' or 'faculty'
    if (count($outCurrentUser->roles) == 1 && in_array($outCurrentUser->roles[0], ['student', 'faculty'])) {
        show_admin_bar(false);
    }
}


// Helper function: safely get current page slug
function get_current_page_slug() {
    if (is_singular()) {
        return get_post_field('post_name', get_queried_object_id());
    }
    // For archive, home, etc, return something else if needed
    return '';
}

// Restrict access for logged-out users
function custom_logged_out_restrict_access() {
    // Don't run in admin, REST API, AJAX, or cron
    if ( is_admin() || defined('DOING_AJAX') && DOING_AJAX || defined('DOING_CRON') && DOING_CRON || (defined('REST_REQUEST') && REST_REQUEST) ) {
        return;
    }

    if (is_user_logged_in()) {
        return; // Allow logged-in users everywhere
    }

    $allowed_pages = array('entry', 'login', 'register');
    $current_page_slug = get_current_page_slug();

    // If slug is empty (like homepage or archive), handle as needed — here we block access
    if (empty($current_page_slug) || !in_array($current_page_slug, $allowed_pages)) {
        wp_redirect(home_url('/entry'));
        exit;
    }
}
add_action('template_redirect', 'custom_logged_out_restrict_access', 1); // run early


// Restrict access for logged-in users to specific pages
function custom_logged_in_restrict_access() {
    // Don't run in admin, REST API, AJAX, or cron
    if ( is_admin() || defined('DOING_AJAX') && DOING_AJAX || defined('DOING_CRON') && DOING_CRON || (defined('REST_REQUEST') && REST_REQUEST) ) {
        return;
    }

    if (!is_user_logged_in()) {
        return; // Non-logged-in users handled above
    }

    $restricted_pages = array('entry', 'login', 'register');
    $current_page_slug = get_current_page_slug();

    if (in_array($current_page_slug, $restricted_pages)) {
        wp_redirect(home_url('/')); // Redirect logged-in users to homepage
        exit;
    }
}
add_action('template_redirect', 'custom_logged_in_restrict_access', 2); // run after logged-out check


// Custom registration URL
add_filter('register_url', function($register_url) {
    return home_url('/register');
});

// Custom login URL
add_filter('login_url', function($login_url) {
    return home_url('/login');
});

//comment deletion
add_action('rest_api_init', function() {
    register_rest_route('wp/v2', '/comments/(?P<id>\d+)', array(
        'methods' => 'DELETE',
        'callback' => 'custom_delete_comment',
        'permission_callback' => function($request) {
            $comment = get_comment($request['id']);
            if (!$comment) {
                return new WP_Error('rest_comment_invalid_id', __('Invalid comment ID.'), array('status' => 404));
            }

            if (get_current_user_id() !== (int) $comment->user_id && !current_user_can('moderate_comments')) {
                return new WP_Error('rest_forbidden', __('You do not have permission to delete this comment.'), array('status' => 403));
            }

            return true;
        }
    ));
});

function custom_delete_comment($request) {
    $comment_id = $request['id'];
    if (wp_delete_comment($comment_id, true)) {
        return new WP_REST_Response('Comment deleted.', 200);
    } else {
        return new WP_Error('rest_comment_delete_failed', __('Failed to delete comment.'), array('status' => 500));
    }
}



?>
