<?php

add_action('rest_api_init','meponetFollowRoutes');

function meponetFollowRoutes() {
    register_rest_route('mepoNET/v1', 'manageFollow', array(
        'methods'  => 'POST',
        'callback' => 'createFollow',
    ));

    register_rest_route('mepoNET/v1', 'manageFollow', array(
        'methods'  => 'DELETE',
        'callback' => 'deleteFollow',
    ));
}

function createFollow($data) {
    if(is_user_logged_in()) {

        $user = sanitize_text_field($data['userID']);

        $existQuery = new WP_Query(array(
            'author'    => get_current_user_id(),
            'post_type' => 'follow',
            'meta_query'=> array(
                array(
                    'key'       => 'followed_user_id',
                    'compare'   => '=',
                    'value'     => $user
                )
            ) 
        ));

        if($existQuery->found_posts == 0) {
            return wp_insert_post(array(
                'post_type' => 'follow',
                'post_status' => 'publish',
                'post_title' => get_the_author_meta('first_name', get_current_user_id()) . ' Followed ' . get_the_author_meta('first_name', $user),
                'meta_input' => array(
                    'followed_user_id' => $user,
                )
            ));
        } else {
            die("invalid userid");
        }

        

    } else {
        die("Only logged in users can follow");
    } 
}

function deleteFollow($data) {
    $followId = sanitize_text_field($data['follow']);


    if(get_current_user_id() == get_post_field('post_author', $followId) AND get_post_type($followId) == 'follow') {
        wp_delete_post($followId, true);
        return "UNfollowd";
    } else {
        die("You do not have permission to unfollow");
    }
}