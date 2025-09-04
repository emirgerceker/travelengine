<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('Uxper_Field_Textarea')) {
	class Uxper_Field_Textarea extends Uxper_Field
	{
		function enqueue()
		{
			wp_enqueue_script(TOGO_METABOX_PREFIX . 'textarea', TOGO_FRAMEWORK_DIR . 'inc/meta-box/fields/textarea/assets/textarea.js', array(), null, true);
		}

		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
?>
			<div class="uxper-field-textarea-inner">
				<textarea data-field-control="" name="<?php echo esc_attr($this->get_name()) ?>" class="uxper-textarea"
					<?php if (isset($this->params['args']) && isset($this->params['args']['col'])): ?>
					cols="<?php echo esc_attr($this->params['args']['col']); ?>"
					<?php endif; ?>
					rows="<?php echo ((isset($this->params['args']) && isset($this->params['args']['row'])) ? esc_attr($this->params['args']['row']) : '5'); ?>"><?php echo esc_textarea($field_value); ?></textarea>
			</div>
<?php
		}
	}
}
