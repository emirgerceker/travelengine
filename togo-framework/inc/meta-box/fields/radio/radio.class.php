<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('Uxper_Field_Radio')) {
	class Uxper_Field_Radio extends Uxper_Field
	{
		function enqueue()
		{
			wp_enqueue_script(TOGO_METABOX_PREFIX . 'radio', TOGO_FRAMEWORK_DIR . 'inc/meta-box/fields/radio/assets/radio.js', array(), null, true);
			wp_enqueue_style(TOGO_METABOX_PREFIX . 'radio', TOGO_FRAMEWORK_DIR . 'inc/meta-box/fields/radio/assets/radio.css');
		}
		function render_content($content_args = '')
		{
			if (isset($this->params['data'])) {
				switch ($this->params['data']) {
					case 'sidebar':
						$this->params['options'] = uxper_get_sidebars();
						break;
					case 'menu':
						$this->params['options'] = uxper_get_menus();
						break;
					case 'taxonomy':
						$this->params['options'] = uxper_get_taxonomies(isset($this->params['data_args']) ? $this->params['data_args'] : array());
						break;
					default:
						if (isset($this->params['data_args']) && !isset($this->params['data_args']['post_type'])) {
							$this->params['data_args']['post_type'] = $this->params['data'];
						}
						$this->params['options'] = uxper_get_posts(isset($this->params['data_args']) ? $this->params['data_args'] : array('post_type' => $this->params['data']));
						break;
				}
			}

			if (!isset($this->params['options']) || !is_array($this->params['options'])) {
				return;
			}
			$field_value = $this->get_value();
			$value_inline = isset($this->params['value_inline']) ? $this->params['value_inline'] : true;
			if ($this->params['show_svg']) {
				echo '<a class="uxper-field-select-icon" href="#">' . esc_html('Select Icon', 'togo-framework') . '</a>';
				echo '<div class="modal modal-icon">';
				echo '<div class="modal-overlay"></div>';
				echo '<div class="modal-content">';
				echo '<div class="modal-header">';
				echo '<h3 class="modal-title">';
				echo esc_html__('Select Icon', 'togo');
				echo '</h3>';
				echo '<div class="close-modal">';
				echo '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 6.00005L6 18M5.99995 6L17.9999 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>';
				echo '</div>';
				echo '</div>';
				echo '<div class="modal-body">';
				echo '<input type="text" name="togo-icon-search" class="togo-icon-search" placeholder="' . esc_html__('Search', 'togo') . '" />';
			}
?>
			<div class="uxper-field-radio-inner <?php echo esc_attr($value_inline ? 'value-inline' : ''); ?>">
				<?php foreach ($this->params['options'] as $key => $value): ?>
					<label data-name="<?php echo esc_attr($key); ?>">
						<input data-field-control="" type="radio"
							<?php if ($key === $field_value): ?>
							checked="checked"
							<?php endif; ?>
							class="uxper-radio"
							name="<?php echo esc_attr($this->get_name()) ?>"
							value="<?php echo esc_attr($key); ?>" />
						<span>
							<?php
							if ($this->params['show_svg']) {
								echo \Togo\Icon::get_svg($key, $key);
								echo '<span class="icon-name">' . esc_html($key) . '</span>';
							} else {
								echo esc_html($value);
							}
							?>
						</span>
					</label>
				<?php endforeach; ?>
			</div>
<?php
			if ($this->params['show_svg']) {
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
		}
	}
}
