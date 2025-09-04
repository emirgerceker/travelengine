<?php

/**
 * Search form
 *
 * @package Togo
 */

$post_type    = 'post';
$place_holder = esc_html__('Search...', 'togo');

?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
	<label class="screen-reader-text" for="s"><?php esc_html_e('Search for:', 'togo'); ?></label>
	<input type="text" class="search-field" id="s" name="s" placeholder="<?php echo esc_attr($place_holder); ?>" />
	<input type="hidden" name="post_type" value="<?php echo esc_attr($post_type); ?>" />
	<button type="submit" class="search-submit">
		<span class="search-btn-text"><?php esc_html_e('Search', 'togo'); ?></span>
		<i class="far fa-search search-btn-icon accent-color"></i>
	</button>
</form>