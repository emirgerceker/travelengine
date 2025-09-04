<?php

namespace Togo_Framework;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Addons init
 *
 * @since 1.0.0
 */
class Setup
{

    /**
     * Instance
     *
     * @var $instance
     */
    private static $instance;

    /**
     * Initiator
     *
     * @since 1.0.0
     * @return object
     */
    public static function instance()
    {
        if (! isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Instantiate the object.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct()
    {
        add_action('activate_plugin', array($this, 'check_theme_before_plugin_activation_notice'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('plugins_loaded', array($this, 'load_templates'));
    }

    public function check_theme_before_plugin_activation_notice($plugin)
    {
        // Replace 'your-theme-slug' with the theme slug you want to check
        $required_theme_slug = 'togo';

        // Get the active theme slug
        $active_theme = wp_get_theme()->get_template(); // Returns the directory name of the active theme

        // Check if the active theme matches the required theme
        if ($active_theme !== $required_theme_slug) {
            // Deactivate the plugin immediately
            deactivate_plugins($plugin);

            // Show an admin notice
            add_action('admin_notices', function () {
                echo '<div class="notice notice-error is-dismissible"><p>' . __('The Togo Trip plugin requires the Togo theme to be active.', 'togo-trip') . '</p></div>';
            });

            // Prevent the plugin from being activated
            exit;
        }
    }

    public function enqueue_styles()
    {
        wp_enqueue_style('togo-framework-style', TOGO_FRAMEWORK_DIR . 'assets/scss/style.min.css');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('togo-framework-frontend-js', TOGO_FRAMEWORK_DIR . 'assets/js/frontend.js', array('jquery'), true, true);
        wp_enqueue_script('togo-framework-woocommerce-js', TOGO_FRAMEWORK_DIR . 'assets/js/woocommerce.js', array('jquery'), true, true);
    }

    public function enqueue_admin_scripts()
    {
        wp_enqueue_style('togo-framework-admin-css', TOGO_FRAMEWORK_DIR . 'assets/css/admin.css');
    }

    /**
     * Load Templates
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function load_templates()
    {
        $this->includes();
        spl_autoload_register('\Togo_Framework\Auto_Loader::load');

        $this->add_actions();
    }

    /**
     * Includes files
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function includes()
    {
        // Auto Loader
        require_once TOGO_FRAMEWORK_PATH . 'togo-framework-autoload.php';
        \Togo_Framework\Auto_Loader::register([
            'Togo_Framework\Init' => TOGO_FRAMEWORK_PATH . 'inc/init.php',
            'Togo_Framework\Metabox' => TOGO_FRAMEWORK_PATH . 'inc/meta-box/meta-box.php',
            'Togo_Framework\Elementor\Setup' => TOGO_FRAMEWORK_PATH . 'inc/elementor/class-elementor.php',
            'Togo_Framework\Widgets' => TOGO_FRAMEWORK_PATH . 'inc/widgets/base.php',
            'Togo_Framework\Post_Type\Mega_Menu' => TOGO_FRAMEWORK_PATH . 'inc/post-type/mega-menu/mega-menu.php',
            'Togo_Framework\Post_Type\Trips' => TOGO_FRAMEWORK_PATH . 'inc/post-type/trips/trips.php',
            'Togo_Framework\Post_Type\Top_Bar' => TOGO_FRAMEWORK_PATH . 'inc/post-type/top-bar/top-bar.php',
            'Togo_Framework\Post_Type\Header' => TOGO_FRAMEWORK_PATH . 'inc/post-type/header/header.php',
            'Togo_Framework\Post_Type\Footer' => TOGO_FRAMEWORK_PATH . 'inc/post-type/footer/footer.php',
            'Togo_Framework\Post_Type\Bookings' => TOGO_FRAMEWORK_PATH . 'inc/post-type/bookings/bookings.php',
            'Togo_Framework\Post_Type\Reviews' => TOGO_FRAMEWORK_PATH . 'inc/post-type/reviews/reviews.php',
            'Togo_Framework\Post_Type\Trips\Metabox' => TOGO_FRAMEWORK_PATH . 'inc/post-type/trips/meta-box.php',
            'Togo_Framework\Post_Type\Reviews\Metabox' => TOGO_FRAMEWORK_PATH . 'inc/post-type/reviews/meta-box.php',
        ]);
    }

    /**
     * Add Actions
     *
     * @since 1.0.0
     *
     * @return void
     */
    protected function add_actions()
    {
        $this->get('init');
        $this->get('meta-box');
        $this->get('elementor-setup');
        $this->get('widgets');
        $this->get('post-type/mega-menu');
        $this->get('post-type/trips');
        $this->get('post-type/top-bar');
        $this->get('post-type/header');
        $this->get('post-type/footer');
        $this->get('post-type/bookings');
        $this->get('post-type/reviews');
        $this->get('post-type/trips/metabox');
        $this->get('post-type/reviews/metabox');

        add_action('after_setup_theme', array($this, 'addons_init'), 20);
    }

    /**
     * Get Addons Class instance
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function get($class)
    {
        switch ($class) {
            case 'init':
                return \Togo_Framework\Init::instance();
            case 'meta-box':
                return \Togo_Framework\Metabox::instance();
            case 'elementor-setup':
                return \Togo_Framework\Elementor\Setup::instance();
            case 'widgets':
                return \Togo_Framework\Widgets::instance();
            case 'post-type/mega-menu':
                return \Togo_Framework\Post_Type\Mega_Menu::instance();
            case 'post-type/trips':
                return \Togo_Framework\Post_Type\Trips::instance();
            case 'post-type/top-bar':
                return \Togo_Framework\Post_Type\Top_Bar::instance();
            case 'post-type/header':
                return \Togo_Framework\Post_Type\Header::instance();
            case 'post-type/footer':
                return \Togo_Framework\Post_Type\Footer::instance();
            case 'post-type/bookings':
                return \Togo_Framework\Post_Type\Bookings::instance();
            case 'post-type/reviews':
                return \Togo_Framework\Post_Type\Reviews::instance();
            case 'post-type/trips/metabox':
                return \Togo_Framework\Post_Type\Trips\Metabox::instance();
            case 'post-type/reviews/metabox':
                return \Togo_Framework\Post_Type\Reviews\Metabox::instance();
                break;
        }
    }

    /**
     * Get Togo Addons Language
     *
     * @since 1.0.0
     *
     * @return void
     */
    function addons_init()
    {
        load_plugin_textdomain('togo', false, dirname(plugin_basename(__FILE__)) . '/lang');
    }
}
