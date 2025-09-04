<?php

/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Togo
 * @since   1.0.0
 */
?>

<?php do_action('togo_before_footer'); ?>

<?php do_action('togo_render_footer'); ?>

<?php do_action('togo_after_footer'); ?>

</div><!-- End #wrapper -->

<?php wp_footer(); ?>

</body>

</html>