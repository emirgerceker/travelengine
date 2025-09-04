<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('Uxper_Field_Map')) {
	class Uxper_Field_Map extends Uxper_Field
	{
		public function enqueue()
		{
			$google_map_api = \Togo\Helper::setting('togo_google_map_api', '');
			if ($google_map_api) {
				$google_map_url = apply_filters('uxper_google_map_api_url', 'https://maps.googleapis.com/maps/api/js?key=' . $google_map_api);
				wp_enqueue_script('google-map', esc_url_raw($google_map_url), array(), '', true);
			}

			wp_enqueue_script(TOGO_METABOX_PREFIX . 'map', TOGO_FRAMEWORK_DIR . 'inc/meta-box/fields/map/assets/map.js', array(), null, true);
			wp_enqueue_style(TOGO_METABOX_PREFIX . 'map', TOGO_FRAMEWORK_DIR . 'inc/meta-box/fields/map/assets/map.css', array(), null);
		}


		function field_map()
		{
			return 'location,address';
		}

		function render_content($content_args = '')
		{
			$map_type = 'google_map';
			$map_zoom_level = '12';
			$field_value = $this->get_value();
			if (!is_array($field_value)) {
				$field_value = array();
			}
			$value_default = array(
				'location' => isset($this->params['default']) ? $this->params['default'] : '37.7749, -122.4194',
				'address'  => ''
			);
			$field_value = wp_parse_args($field_value, $value_default);
			$js_options = isset($this->params['js_options']) ? $this->params['js_options'] : array();
			if (isset($js_options['styles'])) {
				$js_options['styles'] = json_decode($js_options['styles']);
			}
			$placeholder = isset($this->params['placeholder']) ? $this->params['placeholder'] : esc_html__('Enter an address...', 'uxper-booking');
?>
			<div class="uxper-field-map-inner">
				<input data-field-control="" type="hidden" class="uxper-map-location-field" name="<?php echo esc_attr($this->get_name()) ?>[location]" value="<?php echo esc_attr($field_value['location']); ?>" />
				<?php if (!isset($this->params['show_address']) || $this->params['show_address']): ?>
					<div class="uxper-map-address">
						<div class="uxper-map-address-text">
							<input data-field-control="" type="text" placeholder="<?php echo esc_attr($placeholder); ?>" name="<?php echo esc_attr($this->get_name()) ?>[address]" value="<?php echo esc_attr($field_value['address']); ?>" />
						</div>
						<button type="button" class="button"><?php echo esc_html__('Find Address', 'uxper-booking'); ?></button>
						<div class="uxper-map-suggest"></div>
					</div>
				<?php endif; ?>
				<div class="uxper-map-canvas uxper-map-type" data-maptype="<?php echo $map_type; ?>" data-options="<?php echo esc_attr(wp_json_encode($js_options)); ?>" data-zoom="<?php echo $map_zoom_level; ?>" style="height: 300px; width: 100%"></div>
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
				'location' => isset($this->params['default']) ? $this->params['default'] : '-74.5, 40',
				'address'  => ''
			);

			$field_default = isset($this->params['default']) ? $this->params['default'] : array();
			$default = wp_parse_args($field_default, $default);

			return $this->is_clone() ? array($default) : $default;
		}
	}
}
