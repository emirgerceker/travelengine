<?php

/**
 * Togo Customizer
 * This is where all Theme Customizer runs.
 *
 * @package togo
 */

/**
 * Configuration for the Kirki Customizer
 */
if (!function_exists('togo_kirki_update_url')) {
    function togo_kirki_update_url($config)
    {
        $config['url_path'] = get_template_directory_uri() . '/inc/admin/kirki/';

        return $config;
    }
}
add_filter('kirki_config', 'togo_kirki_update_url');

/**
 * Disable default Kirki modules.
 *
 * @param array $modules List of default modules.
 *
 * @return array Filtered list of modules.
 */
function togo_kirki_modules($modules)
{
    unset($modules['css-vars']);
    unset($modules['customizer-styling']);
    unset($modules['icons']);
    unset($modules['loading']);
    unset($modules['branding']);
    unset($modules['selective-refresh']);
    unset($modules['gutenberg']);
    unset($modules['telemetry']);

    return $modules;
}
add_filter('kirki_modules', 'togo_kirki_modules');

/**
 * Remove unused native sections and controls
 *
 * @param $wp_customize
 */
function togo_customize_register($wp_customize)
{
    $wp_customize->remove_section('nav');
    $wp_customize->remove_section('colors');
    $wp_customize->remove_section('background_image');
    $wp_customize->remove_section('header_image');
    $wp_customize->remove_control('display_header_text');

    if (is_customize_preview()) {
        // Selective refresh
        $wp_customize->selective_refresh->add_partial('header_general', array(
            'selector'        => '.site-header',
            'render_callback' => '__return_false',
        ));
        $wp_customize->selective_refresh->add_partial('footer_customize', array(
            'selector'        => '.site-footer',
            'render_callback' => '__return_false',
        ));
    }

    /**
     * Register controls
     */
    $wp_customize->get_control('blogname')->section        = 'site_identity';
    $wp_customize->get_control('blogdescription')->section = 'site_identity';
    $wp_customize->get_control('site_icon')->section       = 'site_identity';

    if (get_pages()) {
        $wp_customize->get_control('show_on_front')->section  = 'system';
        $wp_customize->get_control('page_on_front')->section  = 'system';
        $wp_customize->get_control('page_for_posts')->section = 'system';
    }

    /**
     * Remove default sections
     */
    $wp_customize->remove_section('title_tagline');
    $wp_customize->remove_section('colors');

    /**
     * The custom control class
     */
    class Kirki_Controls_Notice_Control extends Kirki_Control_Base
    {
        public $type = 'notice';

        public function render_content()
        {
?>
            <h3 class="entry-notice"><?php echo esc_html($this->label); ?></h3>
<?php
        }
    }

    // Register our custom control with Kirki
    add_filter('kirki_control_types', function ($controls) {
        $controls['notice'] = 'Kirki_Controls_Notice_Control';
        return $controls;
    });
}
add_action('customize_register', 'togo_customize_register');

/**
 * Load customizer sections when all widgets init
 */
function load_customizer()
{
    \Togo\Kirki::add_config('theme', array(
        'option_type' => 'theme_mod',
        'capability'  => 'edit_theme_options',
    ));

    /**
     * Load Functions
     */
    require_once TOGO_CUSTOMIZER_DIR . '/settings/defaults.php';

    require_once TOGO_CUSTOMIZER_DIR . '/settings/functions.php';

    require_once TOGO_CUSTOMIZER_DIR . '/settings/export.php';

    require_once TOGO_CUSTOMIZER_DIR . '/settings/export-demo.php';

    /**
     * Load panel & section files
     */

    $default = togo_get_default_theme_options();

    require_once TOGO_CUSTOMIZER_DIR . '/options/site_identity.php';
    require_once TOGO_CUSTOMIZER_DIR . '/options/logo.php';
    require_once TOGO_CUSTOMIZER_DIR . '/options/pre-loading.php';
    require_once TOGO_CUSTOMIZER_DIR . '/options/socials.php';

    // Section Typography
    require_once TOGO_CUSTOMIZER_DIR . '/options/typography.php';

    // Section Color
    require_once TOGO_CUSTOMIZER_DIR . '/options/color.php';

    // Panel Button
    require_once TOGO_CUSTOMIZER_DIR . '/options/button/_panel.php';
    require_once TOGO_CUSTOMIZER_DIR . '/options/button/general.php';
    require_once TOGO_CUSTOMIZER_DIR . '/options/button/underline.php';
    require_once TOGO_CUSTOMIZER_DIR . '/options/button/full-filled.php';
    require_once TOGO_CUSTOMIZER_DIR . '/options/button/border-line.php';

    // Section Layout
    require_once TOGO_CUSTOMIZER_DIR . '/options/layout.php';

    // Section Pages
    require_once TOGO_CUSTOMIZER_DIR . '/options/page.php';

    // Section Page 404
    require_once TOGO_CUSTOMIZER_DIR . '/options/page404.php';

    // Section Header
    require_once TOGO_CUSTOMIZER_DIR . '/options/header.php';

    // Section Footer
    require_once TOGO_CUSTOMIZER_DIR . '/options/footer.php';

    // Section Page Title
    require_once TOGO_CUSTOMIZER_DIR . '/options/page-title.php';

    // Blog
    require_once TOGO_CUSTOMIZER_DIR . '/options/blog/_panel.php';
    require_once TOGO_CUSTOMIZER_DIR . '/options/blog/archive.php';
    require_once TOGO_CUSTOMIZER_DIR . '/options/blog/single.php';

    if (class_exists('WooCommerce')) {
        // Shop
        require_once TOGO_CUSTOMIZER_DIR . '/options/shop/_panel.php';
        require_once TOGO_CUSTOMIZER_DIR . '/options/shop/archive.php';
        require_once TOGO_CUSTOMIZER_DIR . '/options/shop/single.php';
        require_once TOGO_CUSTOMIZER_DIR . '/options/shop/cart.php';
    }

    // Section Social Share
    require_once TOGO_CUSTOMIZER_DIR . '/options/sharing.php';

    // Section Notices
    require_once TOGO_CUSTOMIZER_DIR . '/options/notices.php';

    // Section Advanced
    require_once TOGO_CUSTOMIZER_DIR . '/options/advanced.php';

    // IO Control
    require_once TOGO_CUSTOMIZER_DIR . '/options/io.php';

    if (class_exists('Togo_Framework')) {
        // Currency
        require_once TOGO_CUSTOMIZER_DIR . '/options/currency.php';
    }
}
add_action('init', 'load_customizer', 99);
