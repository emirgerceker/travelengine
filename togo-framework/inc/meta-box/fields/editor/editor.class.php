<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('Uxper_Field_Editor')) {
	class Uxper_Field_Editor extends Uxper_Field
	{
		function enqueue()
		{
			wp_enqueue_script(TOGO_METABOX_PREFIX . 'editor', TOGO_FRAMEWORK_DIR . 'inc/meta-box/fields/editor/assets/editor.js', array(), null, true);
		}
		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			/**
			 * Setup up default args
			 */
			$defaults = array(
				'textarea_name' => $this->get_name(),
				'editor_class'  => isset($this->params['class']) ? $this->params['class'] : '',
				'textarea_rows' => 20, //Wordpress default
			);
			$this->params['args'] = isset($this->params['args']) ? $this->params['args'] : array();

			$args = wp_parse_args($this->params['args'], $defaults);
			$editor_id = $this->get_name() . '__editor';
			$editor_id = str_replace('[', '__', $editor_id);
			$editor_id = str_replace(']', '__', $editor_id);
?>
			<div class="uxper-field-editor-inner">
				<?php wp_editor($field_value, $editor_id, $args); ?>
			</div>
<?php
		}
	}
}
