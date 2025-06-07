<?php

//Files and Scripts
function mepoNET_files() {
    wp_enqueue_style('mepoNET_main_styles', get_stylesheet_uri());
    wp_enqueue_script('main-mepoNET-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    wp_enqueue_script('your-script', get_template_directory_uri() . '/src/textarea.js' ); //text area auto resize

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