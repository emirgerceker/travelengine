<?php

/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 */
get_header();

do_action('togo_open_single_post_wrapper');
?>

<div class="site-content">

	<div class="container">

		<div class="row">

			<?php Togo\Theme::render_sidebar('left'); ?>

			<div id="primary" class="content-area">

				<?php

				do_action('togo_open_single_post_content');

				while (have_posts()) : the_post();

					get_template_part('templates/content-single');

				endwhile;

				do_action('togo_close_single_post_content');
				?>

			</div>

			<?php Togo\Theme::render_sidebar('right'); ?>

		</div>

	</div>

</div>

<?php
do_action('togo_close_single_post_wrapper');
get_footer();
