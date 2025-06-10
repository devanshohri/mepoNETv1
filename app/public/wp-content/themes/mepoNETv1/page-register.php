<div class="hidden-header">
    <?php acf_form_head(); ?>
    <?php get_header();?>
</div>

<div class="register-container">
    <div class="entry-logo">
    <div class="logo"> <a href="<?php echo site_url() ?>"><h1>MEPO<span>.network</h1></span></a></div>
        </div>
    <div class="register-box">
        <?php
        echo do_shortcode('[register_form]'); ?>
    </div>
    <p class="register-login-button">Already have an account? <a href="<?php echo wp_login_url() ?>">Login</a></p>
</div>

<?php get_footer();
?>