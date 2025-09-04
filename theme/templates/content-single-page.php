<?php

/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
?>

<div class="site-content">

	<div class="container">

		<div class="row">

			<?php Togo\Theme::render_sidebar('left'); ?>

			<div id="primary" class="content-area">

				<?php
				/* Start the loop */
				while (have_posts()) : the_post();
				?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php get_template_part('templates/content', 'rich-snippet'); ?>

						<div class="entry-content">
							<?php
							the_content();

							wp_link_pages(array(
								'before' => '<div class="page-links">' . esc_html__('Pages:', 'togo'),
								'after'  => '</div>',
							));
							?>
						</div>

					</article>

				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if (comments_open() || get_comments_number()) {
						comments_template();
					}

				endwhile;
				/* End of the loop */
				?>

			</div>

			<?php Togo\Theme::render_sidebar('right'); ?>

		</div>

	</div>

</div>