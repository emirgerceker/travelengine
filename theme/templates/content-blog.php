<?php
if (have_posts()) :
	do_action('togo_content_blog_open_main_content');
	while (have_posts()) : the_post();
		do_action('togo_content_blog_open_article_content');
		do_action('togo_content_blog_close_article_content');
	endwhile;
	do_action('togo_content_blog_close_main_content');
else :
	do_action('togo_content_blog_no_content');
endif;
