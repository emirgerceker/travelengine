<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('Uxper_Field_Image')) {
	class Uxper_Field_Image extends Uxper_Field
	{
		function field_map()
		{
			return 'id,url';
		}
		function enqueue()
		{
			wp_enqueue_script(TOGO_METABOX_PREFIX . 'image', TOGO_FRAMEWORK_DIR . 'inc/meta-box/fields/image/assets/image.js', array(), null, true);
		}

		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			if (!is_array($field_value)) {
				$field_value = array();
			}
			$default = array(
				'id'  => 0,
				'url' => ''
			);

			if (isset($this->params['default'])) {
				if (is_numeric($this->params['default'])) {
					$default['id'] = $this->params['default'];
					$default['url'] = wp_get_attachment_url($default['id']);
				} else {
					$default['url'] = $this->params['default'];
					$default['id'] = uxper_get_attachment_id($default['url']);
				}
			}
			$field_value = wp_parse_args($field_value, $default);

			$thumb_url = $field_value['url'];
			$image_attributes = wp_get_attachment_image_src($field_value['id']);
			if (!empty($image_attributes) && is_array($image_attributes)) {
				$thumb_url = $image_attributes[0];
			}
?>
			<div class="uxper-field-image-inner uxper-clearfix">
				<input data-field-control="" type="hidden" class="uxper-image-id" name="<?php echo esc_attr($this->get_name()) ?>[id]" value="<?php echo esc_attr($field_value['id']); ?>" />
				<div class="uxper-image-preview">
					<div class="centered">
						<img src="<?php echo esc_url($thumb_url); ?>" style="<?php echo esc_attr(empty($thumb_url) ? 'display:none' : '') ?>" />
					</div>
				</div>
				<div class="uxper-image-info">
					<input data-field-control="" type="text" class="uxper-image-url" placeholder="<?php esc_attr_e('No image', 'uxper-booking'); ?>" name="<?php echo esc_attr($this->get_name()) ?>[url]" value="<?php echo esc_url($field_value['url']); ?>" />
					<button type="button" class="button uxper-image-choose-image"><?php esc_html_e('Choose Image', 'uxper-booking'); ?></button>
					<button type="button"
						class="button uxper-image-remove"><?php esc_html_e('Remove', 'uxper-booking'); ?></button>
					<?php if (isset($this->params['images_select_text']) && !empty($this->params['images_select_text'])): ?>
						<button type="button" class="button uxper-image-choose-image-dir"><?php echo esc_html($this->params['images_select_text']); ?></button>
					<?php endif; ?>
				</div>
			</div>
<?php
		}

		/**
		 * Get default value
		 *
		 * @return array
		 */
		function get_default()
		{
			$default = array(
				'id'  => 0,
				'url' => ''
			);

			if (isset($this->params['default'])) {
				if (is_numeric($this->params['default'])) {
					$default['id'] = $this->params['default'];
					$default['url'] = wp_get_attachment_url($default['id']);
				} else {
					$default['url'] = $this->params['default'];
					$default['id'] = uxper_get_attachment_id($default['url']);
				}
			}

			return $this->is_clone() ? array($default) : $default;
		}
	}
}
