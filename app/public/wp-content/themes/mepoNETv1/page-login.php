<div class="hidden-header">
    <?php get_header();?>
</div>

<div class="page-centre">
<div class="login-container">
    <div class="entry-logo">
        <div class="logo"> <a href="<?php echo site_url() ?>"><h1>MEPO<span>.network</h1></span></a></div>
    </div>
    
    <div class="login-box">
        <?php
        echo do_shortcode('[login_form]'); ?>
    </div>
    <p class="login-register-button"><a href="<?php echo wp_lostpassword_url(); ?>"><?php _e('Lost your password?'); ?></a></p>
    <p class="login-register-button">Don't have an account? <a href="<?php echo wp_registration_url() ?>">Sign Up</a></p>

</div>
</div>

<?php get_footer();
?>