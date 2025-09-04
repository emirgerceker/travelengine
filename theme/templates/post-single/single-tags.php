<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

$post_id = get_the_ID();
$tags    = get_the_tags($post_id);
if (empty($tags)) {
	return;
}
?>
<div class="post-tags">
	<span><?php echo esc_html('Tag', 'togo'); ?></span>
	<?php
	foreach ($tags as $tag) {
		$tag_link = get_tag_link($tag->term_id);
	?>
		<a href="<?php echo esc_url($tag_link); ?>"><?php echo esc_html($tag->name); ?></a>
	<?php } ?>
</div>