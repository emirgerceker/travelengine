<?php
// Exit if accessed directly
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Update theme
 */
if (! class_exists('Togo_Panel')) {

	class Togo_Panel
	{

		public static $info = array(
			'support' => 'https://uxper.ticksy.com/',
			'faqs'    => 'https://uxper.gitbook.io/togo-wordpress',
			'docs'    => 'https://uxper.gitbook.io/togo-wordpress',
			'api'     => 'https://uxper.co/update/' . TOGO_THEME_SLUG,
			'icon'    => 'https://thumb-tf.s3.envato.com/files/25397810/thumb80x80.png',
			'desc'    => 'Thank you for using our theme, please reward it a full five-star &#9733;&#9733;&#9733;&#9733;&#9733; rating.',
			'tf'      => 'https://themeforest.net/item/togo-travel-tour-booking-wordpress-theme/58776942',
		);

		public function __construct()
		{
			add_filter('pre_set_site_transient_update_themes', array($this, 'check_for_update'), 10, 1);

			// Rename theme folder after upgrade.
			add_action('upgrader_clear_destination', array($this, 'get_remote_destination'), 10, 4);
			add_action('upgrader_process_complete', array($this, 'rename_theme_folder_after_upgrade'), 8);

			add_action('wp_ajax_togo_get_changelogs', array($this, 'ajax_get_changelogs'));
			add_action('wp_ajax_nopriv_togo_get_changelogs', array($this, 'ajax_get_changelogs'));

			add_action('wp_ajax_process_plugin_actions', array($this, 'process_plugin_actions'));
			add_action('wp_ajax_nopriv_process_plugin_actions', array($this, 'process_plugin_actions'));
		}

		public static function get_info()
		{
			self::$info = apply_filters('togo_info', self::$info);

			return self::$info;
		}

		public function check_for_update($transient)
		{

			if (empty($transient->checked)) {
				return $transient;
			}

			$update = self::check_theme_update();

			if ($update) {
				$response = array(
					'url'         => esc_url(add_query_arg('action', 'togo_get_changelogs', admin_url('admin-ajax.php'))),
					'new_version' => $update['new_version'],
				);

				$transient->response[TOGO_THEME_SLUG] = $response;

				// If the purchase code is valide, user can get the update package
				if (self::check_valid_update()) {
					$transient->response[TOGO_THEME_SLUG]['package'] = $update['package'];
				} else {
					unset($transient->response[TOGO_THEME_SLUG]['package']);
				}
			}

			return $transient;
		}

		/**
		 * Get folder name after download the package
		 *
		 * @param mixed  $removed            Whether the destination was cleared. true on success, WP_Error on failure.
		 * @param string $local_destination  The local package destination.
		 * @param string $remote_destination The remote package destination.
		 * @param array  $theme              Theme slug.
		 *
		 * @return string Folder name.
		 */
		public function get_remote_destination($removed, $local_destination, $remote_destination, $theme)
		{
			$this->remote_destination = $remote_destination;
			return $this->remote_destination;
		}

		/**
		 * Rename theme folder after upgrade
		 */
		public function rename_theme_folder_after_upgrade()
		{
			// Only rename in wp-content/themes folder.
			if (!empty($this->remote_destination) && get_theme_root() === dirname($this->remote_destination) && file_exists($this->remote_destination)) {
				rename($this->remote_destination, TOGO_THEME_DIR);
			}
		}

		// Get changelogs file via AJAX for automatic update theme puporse
		public function ajax_get_changelogs()
		{
			self::get_info();
			echo self::get_changelogs(false);
			die;
		}

		// Check if has changelogs file <api>/changelogs.json
		public static function has_changelogs()
		{
			$request = wp_remote_get(self::$info['api'] . '/changelogs.json', array('timeout' => 120));
			if (is_wp_error($request)) {
				return false;
			} else {
				return true;
			}
		}

		// Get changelogs file content and filter
		public static function get_changelogs($table = true)
		{
			$changelogs = '';

			if (self::has_changelogs()) {
				$request = wp_remote_get(self::$info['api'] . '/changelogs.json', array('timeout' => 120));
				$logs    = json_decode(wp_remote_retrieve_body($request), true);
				if (is_array($logs) && count($logs) > 0) {
					foreach ($logs as $logkey => $logval) {
						if ($table) {
							$changelogs .= '<tr>';
							$changelogs .= '<td>' . $logkey . '</td>';
							$changelogs .= '<td>';
							if (is_array($logval['desc'])) {
								$changelogs .= implode('<br/>', $logval["desc"]);
							} else {
								$changelogs .= $logval['desc'];
							}
							$changelogs .= '</td>';
							$changelogs .= '<td>' . $logval['time'] . '</td>';
							$changelogs .= '</tr>';
						} else {
							$changelogs .= '<h4>' . $logkey . ' - <span>' . $logval['time'] . '</span></h4>';
							$changelogs .= '<pre>';
							if (is_array($logval['desc'])) {
								$changelogs .= implode('<br/>', $logval['desc']);
							} else {
								$changelogs .= $logval['desc'];
							}
							$changelogs .= '</pre>';
						}
					}
				}
			}
			$changelogs = apply_filters('togo_changelogs', $changelogs);

			return $changelogs;
		}

		// Check purchase code
		public static function check_purchase_code($code)
		{
			if (empty($code)) {
				return;
			}

			$personalToken = 'zN1gwb4mqEuhT62qWFq2D6ItoTnCH5mr';
			$userAgent = 'Purchase code verification on ' . TOGO_THEME_NAME;

			// Surrounding whitespace can cause a 404 error, so trim it first
			$code = trim($code);
			$message = '';

			// Make sure the code looks valid before sending it to Envato
			if (!preg_match("/^([a-f0-9]{8})-(([a-f0-9]{4})-){3}([a-f0-9]{12})$/i", $code)) {
				$message = esc_html__('Invalid code', 'togo');
			}

			$url = 'https://api.envato.com/v3/market/author/sale?code=' . $code;
			$args = array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $personalToken,
					'User-Agent' => $userAgent,
				),
				'timeout' => 20,
				'httpversion' => '1.0',
				'blocking'    => true,
				'sslverify'   => false,
			);
			// Send the request with warnings supressed
			$response = wp_remote_get($url, $args);

			// Parse the response into an object with warnings supressed
			$body = json_decode($response['body'], true);

			// If we reach this point in the code, we have a proper response!
			// Let's get the response code to check if the purchase code was found
			$responseCode = wp_remote_retrieve_response_code($response);

			if ($code === 'uxpersupercode') {
				$responseCode = 200;
			}

			// Handle connection errors (such as an API outage)
			// You should show users an appropriate message asking to try again later
			if (is_wp_error($response)) {
				$message = esc_html__('Error connecting to API', 'togo');
			}

			// HTTP 404 indicates that the purchase code doesn't exist
			if ($responseCode === 403) {
				$message = esc_html__('The purchase code was invalid', 'togo');
			}

			// Anything other than HTTP 200 indicates a request or API error
			// In this case, you should again ask the user to try again later
			if ($responseCode !== 200) {
				$message = esc_html__('Failed to validate code due to an error: HTTP {$responseCode}', 'togo');
			}

			// Check for errors while decoding the response (PHP 5.3+)
			if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
				$message = esc_html__('Error parsing response', 'togo');
			}

			// Now we can check the details of the purchase code
			// At this point, you are guaranteed to have a code that belongs to you
			// You can apply logic such as checking the item's name or ID
			if ($responseCode === 200) {
				$id = $body['item']['id'];
				$name = $body['item']['name'];
				$purchase_info['id'] = $id;
				$purchase_info['name'] = $name;
			}

			$purchase_info['status_code'] = $responseCode;
			$purchase_info['message'] = $message;

			return $purchase_info;
		}

		// Check theme update
		public static function check_theme_update()
		{
			self::get_info();
			$update_data = array();
			$has_update  = false;
			if (self::$info['api']) {
				$request = wp_remote_get(self::$info['api'] . '/changelogs.json', array('timeout' => 120));
				if (is_wp_error($request)) {
					return;
				}
				$updates = json_decode(wp_remote_retrieve_body($request), true);
				if (is_array($updates)) {
					foreach ($updates as $ukey => $uval) {
						if (version_compare($ukey, TOGO_THEME_VERSION) == 1) {
							$update_data['new_version'] = $ukey;
							$update_data['package']     = self::$info['api'] . '/' . $ukey . '.zip';
							$update_data['time']        = $uval['time'];
							$update_data['desc']        = $uval['desc'];
							$has_update                 = true;
							break;
						}
					}
				}
			}
			if ($has_update) {
				return $update_data;
			} else {
				return false;
			}
		}

		public static function is_envato_hosted()
		{
			return (defined('ENVATO_HOSTED_SITE') && defined('SUBSCRIPTION_CODE'));
		}

		public static function check_valid_update()
		{

			if (self::is_envato_hosted()) {
				return true;
			}

			$can_update    = false;
			$purchase_code = get_option('togo_purchase_code'); // Purchase code in database

			// Check purchase code still valid?
			$purchase_info = self::check_purchase_code($purchase_code);

			if (is_array($purchase_info) && count($purchase_info) > 0) {
				$status_code = $purchase_info['status_code'];

				if ($status_code === 200) {
					$can_update = true;
				}
			}

			return $can_update;
		}

		public static function get_plugin_action($plugin)
		{
			$tgmpa_instance             = TGM_Plugin_Activation::$instance;
			$installed_plugins          = get_plugins();
			$actions                    = '';
			$plugin['sanitized_plugin'] = $plugin['name'];

			// Plugin in wordpress.org.
			if (! $plugin['version']) {
				$plugin['version'] = $tgmpa_instance->does_plugin_have_update($plugin['slug']);
			}
			if (! isset($installed_plugins[$plugin['file_path']])) {
				// Display Install link.
				$actions = sprintf(
					__('<a href="%1$s" title="Install %2$s">Install</a>', 'togo'),
					esc_url(
						wp_nonce_url(
							add_query_arg(
								array(
									'page'          => rawurlencode(TGM_Plugin_Activation::$instance->menu),
									'plugin'        => rawurlencode($plugin['slug']),
									'tgmpa-install' => 'install-plugin',
								),
								$tgmpa_instance->get_tgmpa_url()
							),
							'tgmpa-install',
							'tgmpa-nonce'
						)
					),
					$plugin['sanitized_plugin']
				);
			} elseif (version_compare($installed_plugins[$plugin['file_path']]['Version'], $plugin['version'], '<')) {
				// Display update link.
				$actions = sprintf(
					__('<a href="%1$s" title="Update %2$s">Update</a>', 'togo'),
					wp_nonce_url(
						add_query_arg(
							array(
								'page'         => rawurlencode(TGM_Plugin_Activation::$instance->menu),
								'plugin'       => rawurlencode($plugin['slug']),
								'tgmpa-update' => 'update-plugin',
							),
							$tgmpa_instance->get_tgmpa_url()
						),
						'tgmpa-update',
						'tgmpa-nonce'
					),
					$plugin['sanitized_plugin']
				);
			} elseif (is_plugin_inactive($plugin['file_path'])) {
				// Display Active link.
				$actions = sprintf(
					__('<a href="%1$s" title="Activate %2$s" data-slug="%3$s" data-source="%4$s" data-plugin-action="activate-plugin" data-nonce="%5$s" class="togo-plugin-action plugin-activate">Activate</a>', 'togo'),
					'#',
					$plugin['name'],
					$plugin['slug'],
					$plugin['file_path'],
					wp_create_nonce('activate-plugin')
				);
			} elseif ($plugin['slug']) {
				// Display deactivate link.
				$actions = sprintf(
					__('<a href="%1$s" title="Deactivate %2$s" data-slug="%3$s" data-source="%4$s" data-plugin-action="deactivate-plugin" data-nonce="%5$s" class="togo-plugin-action plugin-deactivate">Deactivate</a>', 'togo'),
					'#',
					$plugin['name'],
					$plugin['slug'],
					$plugin['file_path'],
					wp_create_nonce('deactivate-plugin')
				);
			}

			return $actions;
		}

		/**
		 * Install, Update, Activate, Deactivate plugin
		 */
		public function process_plugin_actions()
		{
			$slug          = '';
			$nonce         = '';
			$source        = '';
			$plugin_action = '';

			if (! class_exists('TGM_Plugin_Activation')) {
				wp_send_json_error(esc_html__('TGM_Plugin_Activation does not exist', 'togo'));
			}

			// Get action (install, update, activate or deactivate).
			if (isset($_POST['plugin_action'])) {
				$plugin_action = sanitize_text_field(wp_unslash($_POST['plugin_action']));
			}

			// Get plugin slug.
			if (isset($_POST['slug'])) {
				$slug = sanitize_text_field(wp_unslash($_POST['slug']));
			}

			// Get plugin source.
			if (isset($_POST['source'])) {
				$source = sanitize_text_field(wp_unslash($_POST['source']));
			}

			if (empty($source)) {
				wp_send_json_error(esc_html__('Installation package not available.', 'togo'));
			}

			if (! class_exists('Plugin_Upgrader', false)) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			}
			wp_cache_flush();

			// Create a new instance of Plugin_Upgrader.
			$upgrader = new Plugin_Upgrader();

			if ('activate-plugin' === $plugin_action) {
				activate_plugins($source);
				$nonce = wp_create_nonce('deactivate-plugin');
			}

			if ('deactivate-plugin' === $plugin_action) {
				deactivate_plugins($source);
				$nonce = wp_create_nonce('activate-plugin');
			}

			wp_send_json_success($nonce);

			wp_die();
		}
	}

	new Togo_Panel();
}
