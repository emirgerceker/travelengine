<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('Uxper_Field_Gallery')) {
	class Uxper_Field_Gallery extends Uxper_Field
	{
		function enqueue()
		{
			wp_enqueue_script(TOGO_METABOX_PREFIX . 'gallery', TOGO_FRAMEWORK_DIR . 'inc/meta-box/assets/js/gallery.js', array(), null, true);
			wp_enqueue_script(TOGO_METABOX_PREFIX . 'field-gallery', TOGO_FRAMEWORK_DIR . 'inc/meta-box/fields/gallery/assets/gallery.js', array(), null, true);
		}
		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			$field_value_arr = explode('|', $field_value);
?>
			<div class="uxper-field-gallery-inner">
				<input data-field-control="" type="hidden" name="<?php echo esc_attr($this->get_name()) ?>" value="<?php echo esc_attr($field_value); ?>" />
				<?php foreach ($field_value_arr as $image_id): ?>
					<?php
					if (empty($image_id)) {
						continue;
					}
					$image_url = '';
					$image_attributes = wp_get_attachment_image_src($image_id);
					if (!empty($image_attributes) && is_array($image_attributes)) {
						$image_url = $image_attributes[0];
					}
					?>
					<div class="uxper-image-preview" data-id="<?php echo esc_attr($image_id); ?>">
						<div class="centered">
							<img src="<?php echo esc_url($image_url); ?>" />
						</div>
						<span class="uxper-gallery-remove dashicons dashicons dashicons-no-alt"></span>
					</div>
				<?php endforeach; ?>
				<div class="uxper-gallery-add">
					<?php esc_html_e('+ Add Images', 'uxper-booking'); ?>
				</div>
			</div>
<?php
		}
	}
}
