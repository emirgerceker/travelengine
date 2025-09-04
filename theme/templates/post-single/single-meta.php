<div class="post-meta">
	<div class="post-cate">
		<?php echo get_the_category_list(); ?>
	</div>

	<div class="post-comment">
		<span>
			<i class="fal fa-comment-alt"></i>
			<?php
			$comments_number = get_comments_number();
			printf('(%1$s)', esc_html($comments_number));
			?>
		</span>
	</div>
</div>