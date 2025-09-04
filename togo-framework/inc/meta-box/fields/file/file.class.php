<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('Uxper_Field_File')) {
	class Uxper_Field_File extends Uxper_Field
	{
		function enqueue()
		{
			wp_enqueue_script(TOGO_METABOX_PREFIX . 'file', TOGO_FRAMEWORK_DIR . 'inc/meta-box/fields/file/assets/file.js', array(), null, true);
			wp_localize_script(TOGO_METABOX_PREFIX . 'file', 'sfFileFieldMeta', array(
				'title'   => esc_html__('Select File', 'uxper-booking'),
				'button'  => esc_html__('Use these files', 'uxper-booking')
			));
		}

		/*
		 * Render field content
		 */
		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			$field_value_arr = explode('|', $field_value);
			$remove_text = esc_html__('Remove', 'uxper-booking');
?>
			<div class="uxper-field-file-inner" data-remove-text="<?php echo esc_attr($remove_text); ?>"
				<?php if (isset($this->params['lib_filter']) && !empty($this->params['lib_filter'])): ?>
				data-lib-filter="<?php echo esc_attr($this->params['lib_filter']) ?>"
				<?php endif; ?>>
				<input data-field-control="" type="hidden" name="<?php echo esc_attr($this->get_name()) ?>" value="<?php echo esc_attr($field_value); ?>" />
				<?php foreach ($field_value_arr as $file_id): ?>
					<?php
					if (empty($file_id)) {
						continue;
					}
					$file_meta = get_post($file_id);
					if ($file_meta == null) {
						continue;
					}
					?>
					<div class="uxper-file-item" data-file-id="<?php echo esc_attr($file_id); ?>">
						<span class="dashicons dashicons-media-document"></span>
						<div class="uxper-file-info">
							<a class="uxper-file-title" href="<?php echo esc_url(get_edit_post_link($file_id)); ?>" target="_blank"><?php echo esc_html($file_meta->post_title); ?></a>
							<div class="uxper-file-name"><?php echo esc_html(wp_basename($file_meta->guid)); ?></div>
							<div class="uxper-file-action">
								<span class="uxper-file-remove"><span class="dashicons dashicons-no-alt"></span> <?php echo esc_html($remove_text) ?></span>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				<div class="uxper-file-add">
					<button class="button" type="button"><?php esc_html_e('+ Add File', 'uxper-booking'); ?></button>
				</div>
			</div>
<?php
		}
	}
}
