<?php

/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */
get_header();
?>

<div class="site-content">

	<div class="container">

		<div class="row">

			<?php Togo\Theme::render_sidebar('left'); ?>

			<div id="primary" class="content-area">

				<?php
				do_action('togo_open_posts_content');
				if (have_posts()) :
					/* Start the Loop */
					while (have_posts()) :
						the_post();

						/*
						* Include the Post-Type-specific template for the content.
						* If you want to override this in a child theme, then include a file
						* called content-___.php (where ___ is the Post Type name) and that will be used instead.
						*/
						get_template_part('templates/content', get_post_type());

					endwhile;
				else :
					get_template_part('templates/content', 'none');
				endif;
				do_action('togo_close_posts_content');
				?>

			</div>

			<?php Togo\Theme::render_sidebar('right'); ?>

		</div>

	</div>

</div>

<?php
get_footer();
