<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	<div id="comment-<?php comment_ID(); ?>" class="comment-body">
		<div class="inner-comment">
			<div class="comment-author">
				<?php if (get_avatar($comment, $args['avatar_size'])) : ?>
					<div class="entry-avatar">
						<?php echo get_avatar($comment, $args['avatar_size']); ?>
					</div>
				<?php endif; ?>

				<div class="entry-detail">
					<div class="author-name"><?php printf('%s', get_comment_author_link()) ?></div>
					<div class="comment-time">
						<span><?php printf(_x('%s ago', '%s = human-readable time difference', 'togo'), human_time_diff(get_comment_time('U'), current_time('timestamp'))); ?></span>
					</div>
				</div>

				<div class="comment-meta">
					<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => '<span>' . esc_html__('Reply', 'togo') . '</span>'))) ?>
					<?php edit_comment_link('<span>' . esc_html__('Edit', 'togo') . '</span>'); ?>
					<?php if ($comment->comment_approved == '0') : ?>
						<em><?php esc_html_e('Your comment is awaiting moderation.', 'togo'); ?></em>
					<?php endif; ?>
				</div>
			</div>

			<?php if (get_comment_text()) : ?>
				<div class="comment-content">
					<?php comment_text(); ?>
				</div>
			<?php endif; ?>

		</div>
	</div>
</li>