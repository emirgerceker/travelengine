<?php

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
get_header();
?>

<?php do_action('togo_before_page'); ?>

<?php
/**
 * For some reasons HBook use template page.php to show single accommodation.
 */
$post_type = get_post_type();

switch ($post_type) {
	default:
		get_template_part('templates/content-single', 'page');
		break;
}
?>

<?php do_action('togo_after_page'); ?>

<?php
get_footer();
