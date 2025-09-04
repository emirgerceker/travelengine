<?php

/**
 * Template Name: My Account
 */
get_header();
if (!is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}
?>
<div class="dashboard-site">
    <div class="dashboard-nav">
        <div class="dashboard-nav-top">
            <?php
            echo \Togo\Templates::site_logo();
            echo \Togo\Icon::get_svg('arrow-left', 'dashboard-nav-close');
            ?>
        </div>
        <?php
        wp_nav_menu(array(
            'theme_location' => 'my_account_menu',
            'container'      => 'nav',
            'container_class' => 'my-account-menu',
            'menu_class'     => 'my-account-menu-items',
            'fallback_cb'    => false
        ));
        ?>
    </div>
    <div class="dashboard-main">
        <?php
        /* Start the loop */
        while (have_posts()) : the_post();
            the_content();
        endwhile;
        ?>
    </div>
</div>

<?php
get_footer();
