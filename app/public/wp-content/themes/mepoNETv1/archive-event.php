<?php get_header(); ?>

<div class="archive-page-container">
  <h1>Upcoming Events</h1>
  <div class="content-archive-layout-grid">
    <div class="content-archive-sidebar">
      <?php get_template_part('template-parts/sidebar-component'); ?>
    </div>
    <div class="content-archive-main">
      <div class="event-archive-post-layout">
        <?php while (have_posts()) {
          the_post(); ?>
          <div class="event-archive-post">
            <div class="event-archive-post-img">
              <?php the_post_thumbnail('single-post-thumbnail'); ?>
            </div>
            <div class="event-archive-post-info">
              <h3><?php the_title(); ?></h3>
              <p><strong>Date:</strong> <?php echo (new DateTime(get_field('event_date')))->format('F j, Y'); ?></p>
              <p><strong>Time:</strong>
                <?php the_field('event_time_start'); ?>   <?php if (get_field('event_time_end'))
                       echo " - " . get_field('event_time_end'); ?>
              </p>
              <p><strong>Location:</strong> <?php the_field('event_location'); ?></p> 
            </div>
            <div class="black-bttn">
              <h4><a href="<?php the_permalink(); ?>">View Event</a></h4>
            </div>
          </div>
        <?php } ?>
      </div>
      <div class='past-event-link'>
        <p>Checkout <strong>
            <a href="<?php echo site_url('/past-events') ?>">Past
              Events
            </a></strong>
        </p>
      </div>
    </div>
  </div>

</div> <!-- End content-layout-grid -->
</div> <!-- End page-container -->

<?php get_footer(); ?>