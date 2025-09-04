<?php

namespace Togo_Framework\Elementor;

use Elementor\Plugin;

defined('ABSPATH') || exit;

class Widget_Init
{

	private static $_instance = null;

	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	function __construct()
	{
		add_shortcode('togo-template', [$this, 'togo_template_elementor']);
		add_action('elementor/elements/categories_registered', [$this, 'add_elementor_widget_categories']);

		// Registered Widgets.
		add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
		add_action('elementor/widgets/widgets_registered', [$this, 'remove_unwanted_widgets'], 15);

		add_action('elementor/frontend/after_register_scripts', [$this, 'after_register_scripts']);
		add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_scripts'], 9999);
	}

	public function after_register_scripts()
	{
		wp_register_script('togo-widget-carousel', TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/widget-carousel.js', array(
			'jquery',
		), null, true);
		wp_register_script('togo-widget-topbar-carousel', TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/topbar-carousel.js', array(
			'jquery',
		), null, true);
		wp_register_script('togo-widget-testimonials-carousel', TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/testimonials-carousel.js', array(
			'jquery',
		), null, true);
		wp_register_script('togo-widget-search-modal', TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/search-modal.js', array(
			'jquery',
		), null, true);
		wp_register_script('togo-widget-login-form', TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/login-form.js', array(
			'jquery',
		), null, true);
		wp_register_script('togo-widget-register-form', TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/register-form.js', array(
			'jquery',
		), null, true);
		wp_register_script('togo-widget-forgot-password-form', TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/forgot-password-form.js', array(
			'jquery',
		), null, true);
		wp_register_script('togo-widget-canvas-menu', TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/canvas-menu.js', array(
			'jquery',
		), null, true);
		wp_register_script('togo-widget-destinations', TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/destinations.js', array(
			'jquery',
		), null, true);
		wp_register_script('togo-widget-single-trip-gallery', TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/single-trip-gallery.js', array(
			'jquery',
		), null, true);
		wp_register_script('togo-widget-single-trip-overview', TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/single-trip-overview.js', array(
			'jquery',
		), null, true);
		wp_register_script('togo-widget-single-trip-itinerary', TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/single-trip-itinerary.js', array(
			'jquery',
		), null, true);
		$google_map_api = \Togo\Helper::setting('togo_google_map_api', '');
		if ($google_map_api) {
			wp_register_script('togo-el-google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $google_map_api, array(
				'jquery',
			), null, true);
		}

		wp_register_script('togo-widget-single-trip-tour-maps', TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/single-trip-tour-maps.js', array(
			'jquery',
		), null, true);
		wp_register_script('togo-widget-my-bookings', TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/my-bookings.js', array(
			'jquery',
		), null, true);

		wp_localize_script('togo-widget-single-trip-itinerary', 'itinerary_data', array(
			'expand_text' => esc_html__('Expand all', 'togo'),
			'collapse_text' => esc_html__('Collapse all', 'togo'),
		));

		wp_register_script(
			'togo-widget-single-trip-faqs',
			TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/single-trip-faqs.js',
			array(
				'jquery',
			),
			null,
			true
		);

		wp_register_script(
			'togo-widget-trip-destinations-faqs',
			TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/trip-destinations-faqs.js',
			array(
				'jquery',
			),
			null,
			true
		);

		wp_register_script(
			'togo-widget-trip-tab',
			TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/trip-tab.js',
			array(
				'jquery',
			),
			null,
			true
		);

		wp_localize_script(
			'togo-widget-trip-tab',
			'trip_tab_data',
			array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('togo_trip_tab_nonce'),
			)
		);

		wp_register_script(
			'togo-widget-single-trip-form-booking',
			TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/single-trip-form-booking.js',
			array(
				'jquery',
			),
			null,
			true
		);

		wp_register_script(
			'togo-widget-single-trip-availability',
			TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/single-trip-availability.js',
			array(
				'jquery',
			),
			null,
			true
		);

		wp_register_script(
			'togo-widget-my-account-service',
			TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/my-account-service.js',
			array(
				'jquery',
			),
			null,
			true
		);

		wp_register_script(
			'togo-widget-my-account-settings',
			TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/my-account-settings.js',
			array(
				'jquery',
			),
			null,
			true
		);

		wp_register_script(
			'togo-widget-marquee',
			TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/marquee.js',
			array(
				'jquery',
			),
			null,
			true
		);

		wp_register_script(
			'togo-widget-video',
			TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/togo-video.js',
			array(
				'jquery',
			),
			null,
			true
		);

		wp_register_script(
			'togo-widget-destinations-carousel',
			TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/destinations-carousel.js',
			array(
				'jquery',
			),
			null,
			true
		);

		wp_register_script(
			'togo-widget-activities-carousel',
			TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/activities-carousel.js',
			array(
				'jquery',
			),
			null,
			true
		);

		if (class_exists('WooCommerce')) {
			wp_register_script('togo-widget-minicart', TOGO_FRAMEWORK_DIR . 'inc/elementor/assets/js/widgets/minicart.js', array(
				'jquery',
			), null, true);
		}
	}

	/**
	 * enqueue scripts in editor mode.
	 */
	public function enqueue_editor_scripts()
	{
		wp_enqueue_script('togo-elementor-editor', TOGO_FRAMEWORK_PATH . '/inc/elementor/assets/js/editor.js', array('jquery'), null, true);
	}

	/**
	 * @param \Elementor\Elements_Manager $elements_manager
	 *
	 * Add category.
	 */
	function add_elementor_widget_categories($elements_manager)
	{
		$elements_manager->add_category('togo', [
			'title' => esc_html__('Togo', 'togo'),
			'icon'  => 'fa fa-plug',
		]);
		$elements_manager->add_category('single-trips', [
			'title' => esc_html__('Single Trips', 'togo'),
			'icon'  => 'fa fa-plug',
		]);
		$elements_manager->add_category('trip-destinations', [
			'title' => esc_html__('Trip Destinations', 'togo'),
			'icon'  => 'fa fa-plug',
		]);
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 */
	public function init_widgets()
	{
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/base.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/carousel/base.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/breadcrumbs.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/posts.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/mailchimp.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/logo.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/main-menu.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/search-form.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/search-modal.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/login-form.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/register-form.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/forgot-password-form.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/user.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/canvas-menu.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/icons.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/icon-list.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/destinations.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/image.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/marquee.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/trip-grid.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/trip-tab.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/destination.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/destinations-grid.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/togo-video.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/activities-grid.php';

		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/my-account/my-wishlist.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/my-account/my-bookings.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/my-account/my-settings.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/my-account/my-reviews.php';

		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/carousel/topbar-carousel.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/carousel/trip-destinations/carousel.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/carousel/trip-destinations/rates.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/carousel/testimonials.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/carousel/trip-carousel.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/carousel/trip-related.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/carousel/destinations-carousel.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/carousel/posts.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/carousel/modern-carousel.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/carousel/trip-banner.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/carousel/activities-carousel.php';

		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Widget_Breadcrumb());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Posts_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Mailchimp_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Logo_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Main_Menu_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Search_Form_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Search_Modal_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Login_Form_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Register_Form_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Forgot_Password_Form_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_User_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Canvas_Menu_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Icons_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Icon_List_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Destination_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Destinations_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Modern_Image_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Marquee_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Trip_Grid_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Trip_Tab_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Destinations_Grid_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Video_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Activities_Grid_Widget());

		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_My_Wishlist_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_My_Bookings_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_My_Settings_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_My_Reviews_Widget());

		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Topbar_Carousel_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Trip_Destinations_Carousel());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Trip_Destinations_Rates_Carousel());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Trip_Testimonials_Carousel());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Trip_Carousel_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Trip_Related_Carousel_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Destinations_Carousel_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Posts_Carousel_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Modern_Carousel_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Trip_Banner_Widget());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Activities_Carousel_Widget());

		if (class_exists('WooCommerce')) {
			require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/minicart.php';
			require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/wishlist.php';
			Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Mini_Cart_Widget());
			Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Wishlist_Widget());
		}

		if (is_plugin_active('woocommerce-currency-switcher/index.php')) {
			require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/language-currency.php';
			Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Togo_Language_Currency_Widget());
		}

		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/heading.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/mini-review.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/location.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/share.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/wishlist.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/gallery.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/overview.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/highlights.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/ie.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/cancellation.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/itinerary.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/tour-maps.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/faqs.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/services.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/reviews.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/form-booking.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/availability.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/single-trips/mobile-nav.php';
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Heading());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Mini_Review());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Location());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Share());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Wislist());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Gallery());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Overview());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Highlights());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_IE());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Cancellation());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Itinerary());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Tour_Maps());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Faqs());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Services());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Reviews());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Form_Booking());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Availability());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Single_Trips\Widget_Mobile_Nav());

		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/trip-destinations/heading.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/trip-destinations/description.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/trip-destinations/destinations.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/trip-destinations/faqs.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/trip-destinations/thumbnail.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/widgets/trip-destinations/video.php';

		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Trip_Destinations\Widget_Heading());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Trip_Destinations\Widget_Description());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Trip_Destinations\Widget_Destinations());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Trip_Destinations\Widget_FAQs());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Trip_Destinations\Widget_Thumbnail());
		Plugin::instance()->widgets_manager->register(new \Togo_Framework\Elementor\Trip_Destinations\Widget_Video());
	}

	/**
	 * @param \Elementor\Widgets_Manager $widgets_manager
	 *
	 * Remove unwanted widgets
	 */
	function remove_unwanted_widgets($widgets_manager)
	{
		$elementor_widget_blacklist = array(
			'theme-site-logo',
		);

		foreach ($elementor_widget_blacklist as $widget_name) {
			$widgets_manager->unregister_widget_type($widget_name);
		}
	}

	public function togo_template_elementor($atts)
	{
		if (!class_exists('Elementor\Plugin')) {
			return '';
		}
		if (!isset($atts['id']) || empty($atts['id'])) {
			return '';
		}

		$post_id = $atts['id'];
		$response = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($post_id);
		return $response;
	}
}
