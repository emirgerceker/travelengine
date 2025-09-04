<?php

/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if (have_comments()) : ?>
		<h2 class="comments-title">
			<?php
			$comments_number = get_comments_number();
			if ('1' === $comments_number) {
				/* translators: %s: post title */
				printf(_x('One Comment', 'comments title', 'togo'), get_the_title());
			} else {
				printf(
					/* translators: 1: number of comments, 2: post title */
					_nx(
						'Comment (%1$s)',
						'Comments (%1$s)',
						$comments_number,
						'comments title',
						'togo'
					),
					number_format_i18n($comments_number),
					get_the_title()
				);
			}
			?>
		</h2>

		<?php the_comments_navigation(); ?>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 50,
				)
			);
			?>
		</ol><!-- .comment-list -->

		<?php the_comments_navigation(); ?>

	<?php endif; // Check for have_comments(). 
	?>

	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if (! comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
	?>
		<p class="no-comments"><?php esc_html_e('Comments are closed.', 'togo'); ?></p>
	<?php endif; ?>

	<?php
	// Comment Form
	$args = array(
		'comment_field'  => '<p class="comment-form-comment col-lg-12"><label for="comment">' . esc_html__('Comment', 'togo') . '<span class="required">*</span></label><textarea id="comment" class="input-text" name="comment" required cols="45" rows="7"></textarea></p>',
		'fields'         => apply_filters(
			'comment_form_default_fields',
			array(
				'author' 	=> '<p class="comment-form-author col-lg-6"><label for="author">' . esc_html__('Name', 'togo') . '<span class="required">*</span></label><input id="author" class="input-text" name="author" type="text" required value="' . esc_attr($commenter['comment_author']) . '" /></p>',
				'email'  	=> '<p class="comment-form-email col-lg-6"><label for="email">' . esc_html__('Email', 'togo') . '<span class="required">*</span></label><input id="email" class="input-text" name="email" type="email" required value="' . esc_attr($commenter['comment_author_email']) . '" /></p>',
				'website'  	=> '<p class="comment-form-website col-lg-12"><label for="website">' . esc_html__('Website', 'togo') . '</label><input id="website" class="input-text" name="website" type="url" value="" /></p>',
			)
		),
		'title_reply'  => esc_html__('Leave a reply', 'togo'),
		'class_form'   => 'togo-comment-form row',
		'class_submit' => 'togo-button full-filled',
		'label_submit' => esc_html__('Post Comment', 'togo'),
	);

	comment_form($args);
	?>

</div><!-- .comments-area -->