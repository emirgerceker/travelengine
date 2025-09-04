<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

$post_id                            = get_the_ID();
$attach_id                          = get_post_thumbnail_id($post_id);
$single_post_image_size             = Togo_Helper::setting('single_post_image_size');
$thumb_url                          = Togo_Helper::togo_image_resize($attach_id, $single_post_image_size);

if (empty($attach_id)) {
	return;
}
?>
<div class="post-thumbnail togo-image">
	<img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>">
</div>