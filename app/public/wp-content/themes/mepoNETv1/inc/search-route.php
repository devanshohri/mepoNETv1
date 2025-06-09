<?php

add_action('rest_api_init', 'meponetRegisterSearch');

function meponetRegisterSearch() {
    register_rest_route('mepoNETWORK/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'meponetSearchResults'
    ));
}

function meponetSearchResults($data) {
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'event', 'project', 'study-path'),
        's' => sanitize_text_field($data['term'])
    ));

    $results = array(
        'posts' => array(),
        'events' => array(),
        'projects' => array(),
        'studypaths' => array(),
        'users' => array(),
    );

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();

        $postType = get_post_type();

        if ($postType == 'post') {
            array_push($results['posts'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => $postType,
                'authorName' => get_the_author()
            ));
        }

        if ($postType == 'event') {
            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_id()
            ));
        }

        if ($postType == 'project') {
            array_push($results['projects'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }

        if ($postType == 'study-path') {
            array_push($results['studypaths'], array(
                'title' => get_the_title(),
                'id' => get_the_id()
            ));
        }
    }

    // Search for users matching the term
    $user_query = new WP_User_Query(array(
        'search' => '*' . esc_attr($data['term']) . '*',
        'search_columns' => array('user_login', 'user_nicename', 'user_email', 'user_url'),
    ));

    if (!empty($user_query->results)) {
        foreach ($user_query->results as $user) {
            array_push($results['users'], array(
                'display_name' => $user->display_name,
                'permalink' => get_author_posts_url($user->ID),
            ));
        }
    }

    // If we have any study paths, fetch related content
    if (!empty($results['studypaths'])) {
        $studyPathMetaQuery = array('relation' => 'OR');

        foreach ($results['studypaths'] as $item) {
            array_push($studyPathMetaQuery, array(
                'key' => 'related_study_paths',
                'compare' => 'LIKE',
                'value' => '"' . $item['id'] . '"'
            ));
        }

        // Query related posts, events, and projects
        $relatedContentQuery = new WP_Query(array(
            'post_type' => array('post', 'event', 'project'),
            'meta_query' => $studyPathMetaQuery
        ));

        while ($relatedContentQuery->have_posts()) {
            $relatedContentQuery->the_post();

            $postType = get_post_type();

            if ($postType == 'post') {
                array_push($results['posts'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'postType' => $postType,
                    'authorName' => get_the_author()
                ));
            }

            if ($postType == 'event') {
                array_push($results['events'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'id' => get_the_id()
                ));
            }

            if ($postType == 'project') {
                array_push($results['projects'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink()
                ));
            }
        }

        // Query users related to these study paths
        foreach ($results['studypaths'] as $item) {
            $relatedUserQuery = new WP_User_Query(array(
                'meta_key' => 'related_study_paths',
                'meta_value' => '"' . $item['id'] . '"',
                'meta_compare' => 'LIKE'
            ));

            if (!empty($relatedUserQuery->results)) {
                foreach ($relatedUserQuery->results as $user) {
                    $user_data = array(
                        'display_name' => $user->display_name,
                        'permalink' => get_author_posts_url($user->ID),
                    );

                    if (!in_array($user_data, $results['users'], true)) {
                        $results['users'][] = $user_data;
                    }
                }
            }
        }

        // Remove duplicates from posts, events, and projects
        $results['posts'] = array_values(array_unique($results['posts'], SORT_REGULAR));
        $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
        $results['projects'] = array_values(array_unique($results['projects'], SORT_REGULAR));
        $results['users'] = array_values(array_unique($results['users'], SORT_REGULAR));
    }

    return $results;
}
