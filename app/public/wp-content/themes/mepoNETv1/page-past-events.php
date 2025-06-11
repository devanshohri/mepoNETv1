<?php

get_header(); ?>

<div class="archive-page-container">

    <h1>Past Events</h1>

    <div class="content-archive-layout-grid">

        <div class="content-archive-sidebar">
            <?php get_template_part('template-parts/sidebar-component'); ?>
        </div>

        <div class="content-archive-main">
            <div class="event-archive-post-layout">
                <?php

                $today = date('Ymd');
                $pastEvents = new WP_Query(array(
                    'paged' => get_query_var('paged', 1),
                    'post_type' => 'event',
                    'meta_key' => 'event_date',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC',
                    'meta_query' => array(
                        array(
                            'key' => 'event_date',
                            'compare' => '<',
                            'value' => $today,
                            'type' => 'numeric',
                        )
                    )
                ));

                while ($pastEvents->have_posts()) {
                    $pastEvents->the_post(); ?>

                    <div class="event-archive-post">
                        <div class="event-archive-post-img">
                            <?php the_post_thumbnail('single-post-thumbnail'); ?>
                        </div>
                        <div class="event-archive-post-info">
                            <h3><?php the_title(); ?></h3>
                            <p><strong>Date:</strong>
                                <?php echo (new DateTime(get_field('event_date')))->format('F j, Y'); ?></p>
                            <p><strong>Time:</strong>
                                <?php the_field('event_time_start'); ?>     <?php if (get_field('event_time_end'))
                                           echo " - " . get_field('event_time_end'); ?>
                            </p>
                            <p><strong>Location:</strong>
                                <?php echo ($words = explode(' ', get_field('event_location'))) && count($words) > 6 ? implode(' ', array_slice($words, 0, 6)) . '...' : implode(' ', $words); ?>
                            </p>
                        </div>
                        <div class="black-bttn">
                            <h4><a href="<?php the_permalink(); ?>">View Event</a></h4>
                        </div>
                    </div>

                    <?php
                } ?>

            </div>

            <?php
            echo paginate_links(array(
                'total' => $pastEvents->max_num_pages
            ));
            ?>

            <p class='past-event-link'>Checkout <strong><a href="<?php echo get_post_type_archive_link('event') ?>">Upcoming
            Events</a></strong></p>

        </div>
    </div>
</div>
</div>




<?php get_footer();

?>