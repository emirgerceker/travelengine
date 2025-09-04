<?php

/**
 * Togo Define constants
 * This is where all Theme Functions runs.
 *
 * @package togo
 */

$togo_theme = wp_get_theme();

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

if (!empty($togo_theme['Template'])) {
	$togo_theme = wp_get_theme($togo_theme['Template']);
}

if (!defined('TOGO_THEME_NAME')) {
	define('TOGO_THEME_NAME', $togo_theme['Name']);
}

if (!defined('TOGO_THEME_SLUG')) {
	define('TOGO_THEME_SLUG', $togo_theme['Template']);
}

if (!defined('TOGO_THEME_VERSION')) {
	define('TOGO_THEME_VERSION', $togo_theme['Version']);
}

if (!defined('TOGO_THEME_DIR')) {
	define('TOGO_THEME_DIR', trailingslashit(get_template_directory()));
}

if (!defined('TOGO_THEME_URI')) {
	define('TOGO_THEME_URI', get_template_directory_uri());
}

if (!defined('TOGO_THEME_PREFIX')) {
	define('TOGO_THEME_PREFIX', 'togo_');
}

if (!defined('TOGO_METABOX_PREFIX')) {
	define('TOGO_METABOX_PREFIX', 'togo-');
}

if (!defined('TOGO_IMAGES')) {
	define('TOGO_IMAGES', TOGO_THEME_URI . DS . '/assets/images/');
}

if (!defined('TOGO_CUSTOMIZER_DIR')) {
	define('TOGO_CUSTOMIZER_DIR', TOGO_THEME_DIR . 'inc/admin/customizer');
}

if (!defined('TOGO_CUSTOMIZER_URL')) {
	define('TOGO_CUSTOMIZER_URL', get_template_directory_uri() . DS . '/inc/admin/customizer');
}

if (!defined('TOGO_ELEMENTOR_DIR')) {
	define('TOGO_ELEMENTOR_DIR', get_template_directory() . DS . '/inc/elementor');
}

if (!defined('TOGO_ELEMENTOR_URI')) {
	define('TOGO_ELEMENTOR_URI', get_template_directory_uri() . DS . '/inc/elementor');
}

if (!defined('TOGO_ELEMENTOR_ASSETS')) {
	define('TOGO_ELEMENTOR_ASSETS', get_template_directory_uri() . DS . '/inc/elementor/assets');
}

if (!defined('TOGO_WIDGET_DIR')) {
	define('TOGO_WIDGET_DIR', get_template_directory() . DS . '/inc/widgets');
}

if (!defined('TOGO_WIDGET_URI')) {
	define('TOGO_WIDGET_URI', get_template_directory_uri() . DS . '/inc/widgets');
}

/**
 * Require Classes
 */
require TOGO_THEME_DIR . '/inc/classes/class-togo-setup.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-icon.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-debug.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-enqueue.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-hook.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-kirki.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-helper.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-minify.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-performance.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-theme.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-image.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-templates.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-ajax.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-metabox.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-custom-css.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-page-title.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-breadcrumb.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-seo.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-blog.php';
require TOGO_THEME_DIR . '/inc/classes/blog/class-togo-blog-post-loop.php';
require TOGO_THEME_DIR . '/inc/classes/blog/class-togo-blog-posts.php';
require TOGO_THEME_DIR . '/inc/classes/blog/class-togo-blog-post.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-profile.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-top-bar.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-header.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-footer.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-notices.php';
require TOGO_THEME_DIR . '/inc/classes/class-tgm-plugin-activation.php';
require TOGO_THEME_DIR . '/inc/classes/class-walker-nav-menu.php';
require TOGO_THEME_DIR . '/inc/classes/class-togo-plugins.php';

/**
 * Require Admin
 */
require_once TOGO_THEME_DIR . '/inc/admin/admin-init.php';

\Togo\Kirki::instance();
\Togo\Theme::instance();
\Togo\Blog::instance();
\Togo\Top_Bar::instance();
\Togo\Header::instance();
\Togo\Blog\Post_Loop::instance();
\Togo\Blog\Posts::instance();
\Togo\Blog\Post::instance();

if (class_exists('WooCommerce')) {
	require TOGO_THEME_DIR . '/inc/classes/class-togo-woo.php';
	require TOGO_THEME_DIR . '/inc/classes/woo/class-togo-woo-helper.php';
	require TOGO_THEME_DIR . '/inc/classes/woo/class-togo-woo-minicart.php';
	require TOGO_THEME_DIR . '/inc/classes/woo/class-togo-woo-archive-product.php';
	\Togo\Woo::instance();
	\Togo\Woo\Minicart::instance();
	\Togo\Woo\ArchiveProduct::instance();
}
