<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('Uxper_Field_Text')) {
	class Uxper_Field_Text extends Uxper_Field
	{
		function enqueue()
		{
			wp_enqueue_script(TOGO_METABOX_PREFIX . 'text', TOGO_FRAMEWORK_DIR . 'inc/meta-box/fields/text/assets/text.js', array(), null, true);
		}
		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			$input_type = isset($this->params['input_type']) ? $this->params['input_type'] : 'text';

			$attr = array();
			if (isset($this->params['maxlength'])) {
				$attr[] = sprintf('maxlength="%s"', esc_attr($this->params['maxlength']));
			}
			if (isset($this->params['pattern'])) {
				$attr[] = sprintf('pattern="%s"', esc_attr($this->params['pattern']));
			}

			if (isset($this->params['panel_title']) && $this->params['panel_title']) {
				$attr[] = 'data-panel-title="true"';
			}

			if ($input_type === 'unique_id') {
				$attr[] = 'readonly="readonly"';
				$attr[] = 'data-unique_id="true"';
				$attr[] = 'data-unique_id-prefix="' . (isset($this->params['default']) ? $this->params['default'] : '')  . '"';
				$input_type = 'text';
			}

			switch ($input_type) {
				case 'range':
				case 'number':
					if (isset($this->params['args'])) {
						if (isset($this->params['args']['min'])) {
							$attr[] = sprintf('min="%s"', esc_attr($this->params['args']['min']));
						}
						if (isset($this->params['args']['max'])) {
							$attr[] = sprintf('max="%s"', esc_attr($this->params['args']['max']));
						}
						if (isset($this->params['args']['step'])) {
							$attr[] = sprintf('step="%s"', esc_attr($this->params['args']['step']));
						}
					}
					break;
			}
?>
			<div class="uxper-field-text-inner <?php echo esc_attr($input_type == 'range' ? 'range-type' : ''); ?>">
				<input data-field-control="" class="uxper-text" type="<?php echo esc_attr($input_type); ?>" <?php echo join(' ', $attr); ?>
					name="<?php echo esc_attr($this->get_name()) ?>"
					<?php if (!empty($this->params['placeholder'])): ?>
					placeholder="<?php echo esc_attr($this->params['placeholder']); ?>"
					<?php endif; ?>
					value="<?php echo esc_attr($field_value); ?>" />
			</div>
<?php
		}
	}
}
