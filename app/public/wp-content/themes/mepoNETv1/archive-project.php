<?php get_header(); ?>

<div class="archive-page-container">
  <h1>Upcoming Projects</h1>
  
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
              <p><strong>Project Manager:</strong> <?php the_field('project_manager'); ?></p>
              <p><strong>Timeline:</strong>
                <?php 
                the_field('project_start_date'); 
                $end_date = get_field('project_end_date');
                if ($end_date) {
                  echo ' - ' . $end_date;
                }
                ?>
              </p>
              <p><strong>Team Size:</strong> <?php the_field('project_team_size'); ?></p>
            </div>

            <div class="black-bttn">
              <h4><a href="<?php the_permalink(); ?>">View Project</a></h4>
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

    </div>
    
  </div> <!-- End content-archive-layout-grid -->
</div> <!-- End archive-page-container -->

<?php get_footer(); ?>
