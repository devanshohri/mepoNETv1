<?php 

get_header(); ?>

<div class="event-archive-post-page">

<h1>Past Events</h1>

<div class="event-archive-post-layout"> 

<?php

$today = date('Ymd');
$pastEvents = new WP_Query(array(
    'paged' => get_query_var('paged', 1),
    'post_type' => 'event',
    'meta_key'=> 'event_date',
    'orderby'=> 'meta_value_num',
    'order'=> 'ASC',
    'meta_query'=> array(
    array(
        'key' => 'event_date',
        'compare' => '<',
        'value' => $today,
        'type' => 'numeric',
    )
    )
));

while($pastEvents->have_posts()){
    $pastEvents->the_post(); ?>

    <div class="event-archive-post"> 

        <div class="event-archive-post-img">
            <h3> <?php the_post_thumbnail('single-post-thumbnail'); ?></h3>
        </div>

        <div class="event-archive-post-title">
            <h3> <?php the_title(); ?></h3>
        </div>

        <div class="event-archive-post-date">
            <p>
                <strong> Date: </strong>
                <?php 
                    $eventDate = new DateTime(get_field('event_date'));
                    echo $eventDate->format('F j, Y');
                ?>
            </p>

        </div>

        <div class="event-archive-post-time">
            <p>
                <strong> Time: </strong>
                <?php 
                    $eventTimeStart = get_field('event_time_start');
                    echo $eventTimeStart;
                    $eventTimeEnd = get_field('event_time_end');
                    if (!empty($eventTimeEnd)){
                    echo " - ", $eventTimeEnd;
                    }
                ?>
                </p>
        </div>

        <div class="event-archive-post-full-bttn">
            <h4><a href="<?php the_permalink(); ?>">View Full Post</a></h4>
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

</div>

<p class='past-event-link'>Checkout <strong><a href="<?php echo get_post_type_archive_link('event') ?>">Upcoming Events</a></strong></p>

<?php get_footer();

?>