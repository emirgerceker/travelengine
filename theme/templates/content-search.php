<?php
if (have_posts()) :
	$archive_class = [
		'togo-grid',
		'togo-blog',
		'togo-animate-zoom-in',
		'togo-blog-default',
		'grid-lg-1',
		'grid-md-1',
		'grid-sm-1'
	];

	$grid_options = [
		'type'          => 'default',
		'columns'       => 1,
		'columnsTablet' => 1,
		'columnsMobile' => 1,
		'gutter'        => 30,
		'gutterTablet'  => 30,
	];
?>

	<div class="main-content">

		<div class="<?php echo join(' ', $archive_class); ?>" data-grid="<?php echo esc_attr(wp_json_encode($grid_options)); ?>">

			<?php
			/* Start the loop */
			while (have_posts()) : the_post();

				/*
				* Include the Post-Format-specific template for the content.
				* If you want to override this in a child theme, then include a file
				* called content-___.php (where ___ is the Post Format name) and that will be used instead.
				*/
				get_template_part('templates/loop/blog/content', 'default');

			endwhile;
			/* End of the loop */
			?>

		</div>

		<?php echo Togo\Templates::pagination(); ?>

	</div>

<?php
else :

	get_template_part('templates/content', 'none');

endif;
?>