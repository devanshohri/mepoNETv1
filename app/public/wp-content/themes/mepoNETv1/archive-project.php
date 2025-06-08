<?php 

get_header(); ?>

<div class="project-archive-post-page">

<h1>Upcoming Projects</h1>

<div class="project-archive-post-layout"> 

    <?php
    while(have_posts()){
        the_post(); ?>

        <div class="project-archive-post"> 

            <div class="project-archive-post-img">
                <?php the_post_thumbnail('single-post-thumbnail'); ?>
            </div>


            <div class="project-archive-post-meta">

                <div class="project-archive-post-title">
                        <h3> <?php the_title(); ?></h3>
                </div>

                <div class="project-archive-post-date">
                    <p>
                        <strong>Project Manager: </strong>
                            <?php 
                                $projectManager = get_field('project_manager');
                                echo $projectManager;
                            ?>
                    </p>
                </div>

                <div class="project-archive-post-timeline">
                    <p>
                        <strong> Timeline: </strong>
                            <?php 
                                $projectTimeStart = get_field('project_start_date');
                                echo $projectTimeStart;
                                $projectTimeEnd = get_field('project_end_date');
                                if (!empty($projectTimeEnd)){
                                echo " - ", $projectTimeEnd;
                                }
                            ?>
                    </p>
                </div>

                <div class="project-archive-post-team-size">
                    <p>
                        <strong> Team Size: </strong>
                            <?php 
                                $projectTeamSize = get_field('project_team_size');
                                echo $projectTeamSize;
                            ?>
                    </p>
                </div>
            </div>

            <div class="project-archive-post-full-bttn">
                <a href="<?php the_permalink(); ?>"><h4>View Full Post</h4></a>
            </div>

        </div>       

    <?php } ?>

    


</div>
</div>
<?php get_footer();

?>