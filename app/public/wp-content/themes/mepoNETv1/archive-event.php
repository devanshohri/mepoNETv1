<?php get_header(); ?>

<div class="archive-page-container">
  <h1>Upcoming Events</h1>
  
  <div class="content-archive-layout-grid">
    
    <div class="content-archive-sidebar">
      <?php get_template_part('template-parts/sidebar-component'); ?>
    </div>

    <div class="content-archive-main">
      <div class="event-archive-post-layout">
        <?php while (have_posts()) : the_post(); ?>
          <div class="event-archive-post">
            <div class="event-archive-post-img">
              <?php 
              if (has_post_thumbnail()) {
                the_post_thumbnail('single-post-thumbnail');
              }
              ?>
            </div>

            <div class="event-archive-post-info">
              <h3><?php the_title(); ?></h3>
              <p><strong>Date:</strong> 
                <?php 
                $date = get_field('event_date');
                if ($date) {
                  echo (new DateTime($date))->format('F j, Y');
                }
                ?>
              </p>
              <p><strong>Time:</strong> 
                <?php 
                the_field('event_time_start'); 
                $end_time = get_field('event_time_end');
                if ($end_time) {
                  echo ' - ' . $end_time;
                }
                ?>
              </p>
              <p><strong>Location:</strong> 
                <?php 
                $location = get_field('event_location');
                $words = explode(' ', $location);
                echo count($words) > 6 ? implode(' ', array_slice($words, 0, 6)) . '...' : $location;
                ?>
              </p>
            </div>

            <div class="black-bttn">
              <h4><a href="<?php the_permalink(); ?>">View Event</a></h4>
            </div>
          </div>
        <?php endwhile; ?>
      </div>

      <!-- Pagination -->
      <div class="pagination">
        <?php
        echo paginate_links([
          'prev_text' => __('« Prev'),
          'next_text' => __('Next »'),
        ]);
        ?>
      </div>

      <div class="past-event-link">
        <p>Checkout <strong><a href="<?php echo site_url('/past-events'); ?>">Past Events</a></strong></p>
      </div>
    </div>

  </div> <!-- End content-archive-layout-grid -->
</div> <!-- End archive-page-container -->

<?php get_footer(); ?>
