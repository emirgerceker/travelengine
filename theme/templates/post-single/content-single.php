<?php

/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 */

$sidebar_position = Togo_Helper::setting('single_post_sidebar_position');

if ($sidebar_position === 'none') {
    $sb_class = 'no-sidebar';
} else {
    $sb_class = 'has-sidebar';
}

?>

<div class="site-content <?php echo esc_attr($sb_class); ?>">
    <?php do_action('togo_single_blog_open_main_content'); ?>
    <div class="container">

        <div class="row">

            <?php Togo_Global::render_sidebar('left'); ?>

            <div id="primary" class="content-area">

                <?php
                /* Start the loop */
                while (have_posts()) : the_post();
                ?>
                    <article <?php post_class('area-post post-content'); ?>>
                        <?php do_action('togo_single_blog_open_article_content'); ?>
                        <?php do_action('togo_single_blog_close_article_content'); ?>
                    </article>
                <?php
                endwhile;
                /* End of the loop */
                ?>

            </div>

            <?php Togo_Global::render_sidebar('right'); ?>

        </div>

    </div>
    <?php do_action('togo_single_blog_close_main_content'); ?>
</div>