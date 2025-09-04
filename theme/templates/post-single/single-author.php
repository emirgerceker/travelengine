<?php
$author_id  = get_the_author_meta('ID');
$user_name  = get_the_author_meta('display_name');
$avatar_url = get_avatar_url($author_id);
$author_avatar_image_url = get_the_author_meta('author_avatar_image_url', $author_id);
if (!empty($author_avatar_image_url)) {
	$avatar_url = $author_avatar_image_url;
}

?>

<?php if (!empty($user_name)) { ?>
	<div class="post-author">
		<?php if ($avatar_url) : ?>
			<div class="inner-left align-center">
				<div class="entry-avatar">
					<a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
						<img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($user_name); ?>">
					</a>
				</div>
			</div>
		<?php endif; ?>

		<div class="inner-right">
			<div class="head-author">
				<span><?php echo esc_html__('Post by ', 'togo'); ?></span>
				<h3 class="entry-title"><?php the_author_posts_link(); ?></h3>
			</div>
			<p class="entry-bio">
				<?php echo esc_html__('Published: ', 'togo'); ?><?php echo esc_html(get_the_time(get_option('date_format'))); ?>
			</p>
		</div>
	</div>
<?php } ?>