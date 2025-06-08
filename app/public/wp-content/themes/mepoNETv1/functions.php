<?php

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
