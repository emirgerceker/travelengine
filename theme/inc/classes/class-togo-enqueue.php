<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Togo_Enqueue')) {

	/**
	 *  Class Togo_Enqueue
	 */
	class Togo_Enqueue
	{

		/**
		 * The constructor.
		 */
		function __construct()
		{
			add_action('wp_enqueue_scripts',  array($this, 'adjust_styles_load_order'), 9);
			add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'), 9999);
			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 9999);
		}

		public function adjust_styles_load_order()
		{
			wp_dequeue_style('elementor-frontend');
			wp_enqueue_style('elementor-frontend');
		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 */
		public function enqueue_styles()
		{
			wp_enqueue_style('togo-fonts', \Togo\Helper::get_fonts_url(), array(), null);
			/*
			 * Enqueue Third Party Styles
			 */

			wp_enqueue_style('swiper', TOGO_THEME_URI . '/assets/libs/swiper/css/swiper-bundle.min.css', array(), '11.1.9', 'all');

			wp_enqueue_style('growl', TOGO_THEME_URI . '/assets/libs/growl/css/jquery.growl.min.css', array(), '1.3.3', 'all');

			if (is_rtl()) {
				wp_enqueue_style('togo-style', get_template_directory_uri() . '/style-rtl.min.css');
			} else {
				wp_enqueue_style('togo-style', get_template_directory_uri() . '/style.min.css');
			}
		}

		/**
		 * Register the JavaScript for the admin area.
		 */
		public function enqueue_scripts()
		{

			/*
			 * Enqueue Third Party Scripts
			 */
			wp_enqueue_script('swiper', TOGO_THEME_URI . '/assets/libs/swiper/js/swiper-bundle.min.js', array('jquery'), '11.1.9', true);

			wp_enqueue_script('growl', TOGO_THEME_URI . '/assets/libs/growl/js/jquery.growl.min.js', array('jquery'), '1.3.3', true);

			wp_enqueue_script('validate', TOGO_THEME_URI . '/assets/libs/validate/jquery.validate.min.js', array('jquery'), '2.2.0', true);

			$google_map_api = \Togo\Helper::setting('togo_google_map_api', '');
			wp_register_script('google-map-callback', 'https://maps.googleapis.com/maps/api/js?key=' . $google_map_api . '&callback=initMap', array('jquery'), null, true);
			wp_register_script('google-map', 'https://maps.googleapis.com/maps/api/js?key=' . $google_map_api, array('jquery'), null, true);

			/*
			 * Enqueue Theme Scripts
			 */
			if (defined('WP_DEBUG')) {
				wp_enqueue_script('togo-main-js', TOGO_THEME_URI . '/assets/js/vendor.js', array('jquery'), TOGO_THEME_VERSION, true);
			} else {
				wp_enqueue_script('togo-main-js', TOGO_THEME_URI . '/assets/js/vendor.min.js', array('jquery'), TOGO_THEME_VERSION, true);
			}

			$ajax_url     = admin_url('admin-ajax.php');
			$current_lang = apply_filters('wpml_current_language', null);

			if ($current_lang) {
				$ajax_url = add_query_arg('lang', $current_lang, $ajax_url);
			}

			// Get currency settings of woocommerce first, if not available then use theme settings
			// Check if the WooCommerce plugin is active
			if (class_exists('WooCommerce')) {
				$symbol = get_woocommerce_currency_symbol();
				$currency_position = get_option('woocommerce_currency_pos', 'left');
				$currency_thousand_separator = get_option('woocommerce_thousand_sep', ',');
				$currency_decimal_separator = get_option('woocommerce_decimal_sep', '.');
				$currency_number_of_decimals = get_option('woocommerce_price_num_decimals', 0);
			} else {
				if (class_exists('\Togo_Framework\Helper')) {
					$symbol = \Togo_Framework\Helper::get_currency_symbol(\Togo\Helper::setting('currency', 'USD'));
				} else {
					$symbol = \Togo\Helper::setting(
						'currency',
						'USD'
					);
				}
				$currency_position = \Togo\Helper::setting('currency_position', 'left');
				$currency_thousand_separator = \Togo\Helper::setting('currency_thousand_separator', ',');
				$currency_decimal_separator = \Togo\Helper::setting('currency_decimal_separator', '.');
				$currency_number_of_decimals = \Togo\Helper::setting('currency_number_of_decimals') ? \Togo\Helper::setting('currency_number_of_decimals') : 0;
			}
			wp_localize_script(
				'togo-main-js',
				'theme_vars',
				array(
					'ajax_url'                 	=> esc_url($ajax_url),
					'header_sticky'            	=> Togo\Theme::get_header_overlay(),
					'header_float'            	=> Togo\Theme::get_header_float(),
					'content_protected_enable' 	=> Togo\Helper::setting('content_protected', '0'),
					'scroll_top_enable'        	=> Togo\Helper::setting('back_to_top', '0'),
					'send_user_info' 			=> esc_html__('Sending user info,please wait...', 'togo'),
					'notice_cookie_enable'      => Togo\Helper::setting('notice_cookie_enable'),
					'notice_cookie_confirm'     => isset($_COOKIE['notice_cookie_confirm']) ? 'yes' : 'no',
					'notice_cookie_messages'    => Togo_Notices::instance()->get_notice_cookie_messages(),
					'togo_add_to_wishlist_nonce' => wp_create_nonce('togo_add_to_wishlist'),
					'upload_images_nonce' => wp_create_nonce('upload_images_nonce'),
					'remove_images_nonce' => wp_create_nonce('remove_images_nonce'),
					'add_review_nonce' => wp_create_nonce('add_review_nonce'),
					'edit_review_nonce' => wp_create_nonce('edit_review_nonce'),
					'delete_review_nonce' => wp_create_nonce('delete_review_nonce'),
					'cancel_booking_nonce' => wp_create_nonce('cancel_booking_nonce'),
					'get_itinerary_nonce' => wp_create_nonce('get_itinerary_nonce'),
					'apply_coupon_nonce' => wp_create_nonce('apply_coupon_nonce'),
					'prevText' => esc_html__('Prev', 'togo'),
					'nextText' => esc_html__('Next', 'togo'),
					'guest' => esc_html__('guest', 'togo'),
					'guests' => esc_html__('guests', 'togo'),
					'upload_failed' => esc_html__('Upload failed', 'togo'),
					'failed_to_remove_image' => esc_html__('Failed to remove image.', 'togo'),
					'error_while_upload' => esc_html__('An error occurred while uploading.', 'togo'),
					'error_while_remove' => esc_html__('An error occurred while removing image.', 'togo'),
					'failed_to_delete_review' => esc_html__('Failed to delete review.', 'togo'),
					'failed_to_cancel_booking' => esc_html__('Failed to cancel booking.', 'togo'),
					'symbol' => $symbol,
					'currency_position' => $currency_position,
					'currency_thousand_separator' => $currency_thousand_separator,
					'currency_decimal_separator' => $currency_decimal_separator,
					'currency_number_of_decimals' => $currency_number_of_decimals,
					'google_map_api' => $google_map_api,
				)
			);

			/*
			 * The comment-reply script.
			 */
			if (is_singular() && comments_open() && get_option('thread_comments')) {
				wp_enqueue_script('comment-reply');
			}
		}
	}

	new Togo_Enqueue();
}
