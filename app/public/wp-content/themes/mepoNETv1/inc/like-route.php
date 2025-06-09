<?php

add_action('rest_api_init', 'mepoNETLikeRoutes');

function mepoNETLikeRoutes() {
  register_rest_route('mepoNET/v1', 'manageLike', array(
    'methods' => 'POST',
    'callback' => 'createLike'
  ));

  register_rest_route('mepoNET/v1', 'manageLike', array(
    'methods' => 'DELETE',
    'callback' => 'deleteLike'
  ));
}

function createLike($data) {
  if (is_user_logged_in()) {
    $post = sanitize_text_field($data['postId']);

    $existQuery = new WP_Query(array(
      'author' => get_current_user_id(),
      'post_type' => 'like',
      'meta_query' => array(
        array(
          'key' => 'liked_post_id',
          'compare' => '=',
          'value' => $post
        )
      )
    ));

    if ($existQuery->found_posts == 0 && get_post_type($post) == 'post' || get_post_type($post) == 'event' || get_post_type($post) == 'project') {
      return wp_insert_post(array(
        'post_type' => 'like',
        'post_status' => 'publish',
        'post_title' => get_the_author_meta('first_name', get_current_user_id()) . ' liked ' . get_the_author_meta('first_name', get_post_field('post_author', $post)),
        'meta_input' => array(
          'liked_post_id' => $post
        )
      ));
    } else {
      die("Invalid post id");
    }

    
  } else {
    die("Only logged in users can create a like.");
  }

  
}

function deleteLike($data) {
  $likeId = sanitize_text_field($data['like']);
  if (get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'like') {
    wp_delete_post($likeId, true);
    return 'Congrats, like deleted.';
  } else {
    die("You do not have permission to delete that.");
  }
} ?>