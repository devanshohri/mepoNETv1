<div class="hidden-header">
    <?php get_header();?>
</div>

<div class="page-centre">

    <div class="entry-container">

        <div class="entry-logo">
           <div class="logo"> <a href="<?php echo site_url() ?>"><h1>MEPO<span>.network</h1></span></a></div>
        </div>

        <div class="entry-buttons">
            <button class="black-bttn"><a href="<?php echo wp_login_url() ?>"><h3>Login</h3></a></button>
                <p class="greyed">or</p>
            <button class="white-bttn"><a href="<?php echo wp_registration_url() ?>"><h3>Sign Up</h3></a></button>
            
        </div>

    </div>

</div>

<?php get_footer();?>