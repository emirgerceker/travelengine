<?php

namespace Togo;

use Togo\Helper;
use Togo\Woo;

defined('ABSPATH') || exit;

/**
 * Initialize Global Variables
 */

class Theme
{
	protected static $instance        = null;
	protected static $page_title_type = '';
	protected static $header_type     = '';
	protected static $header_overlay  = 'no';
	protected static $header_float  = 'no';
	protected static $topbar_type     = '';
	protected static $footer_type     = '0';

	public static function instance()
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct()
	{
		/**
		 * Use hook wp instead of init because we need post meta setup.
		 * then we must wait for post loaded.
		 */
		add_action('wp', array($this, 'init_global_variable'));

		/**
		 * Setup global variables.
		 * Used priority 12 to wait override settings setup.
		 *
		 * @see Togo_Customize->setup_override_settings()
		 */
		add_action('wp', array($this, 'setup_global_variables'), 12);

		add_filter('body_class', array($this, 'theme_body_classes'));

		add_action('delete_attachment', array($this, 'delete_resized_images'));

		add_post_type_support('page', 'excerpt');
	}

	function init_global_variable()
	{
		global $togo_page_options;
		$id = get_the_ID();
		if (!is_front_page() && is_home()) {
			$id = get_queried_object_id();
		} elseif (function_exists('is_shop') && is_shop()) {
			$id = wc_get_page_id('shop');
		} else {
			$id = get_the_ID();
		}

		$togo_page_options['site_layout'] = get_post_meta($id, 'site_layout', true);
		$togo_page_options['content_top_spacing'] = get_post_meta($id, 'content_top_spacing', true);
		$togo_page_options['content_bottom_spacing'] = get_post_meta($id, 'content_bottom_spacing', true);
		$togo_page_options['content_top_spacing_tablet'] = get_post_meta($id, 'content_top_spacing_tablet', true);
		$togo_page_options['content_bottom_spacing_tablet'] = get_post_meta($id, 'content_bottom_spacing_tablet', true);
		$togo_page_options['content_top_spacing_mobile'] = get_post_meta($id, 'content_top_spacing_mobile', true);
		$togo_page_options['content_bottom_spacing_mobile'] = get_post_meta($id, 'content_bottom_spacing_mobile', true);
		$togo_page_options['content_overflow_hidden'] = get_post_meta($id, 'content_overflow_hidden', true);
		$togo_page_options['top_bar_type'] = get_post_meta($id, 'top_bar_type', true);
		$togo_page_options['header_type'] = get_post_meta($id, 'header_type', true);
		$togo_page_options['header_overlay'] = get_post_meta($id, 'header_overlay', true);
		$togo_page_options['header_float'] = get_post_meta($id, 'header_float', true);
		$togo_page_options['page_page_title_layout'] = get_post_meta($id, 'page_page_title_layout', true);
		$togo_page_options['active_sidebar'] = get_post_meta($id, 'active_sidebar', true);
		$togo_page_options['sidebar_position'] = get_post_meta($id, 'sidebar_position', true);
		$togo_page_options['footer_enable'] = get_post_meta($id, 'footer_enable', true);
		$togo_page_options['footer_type'] = get_post_meta($id, 'footer_type', true);
	}

	public function setup_global_variables()
	{
		$this->set_header_options();
		$this->set_page_title_type();
		$this->set_topbar_type();
		$this->set_header_type();
		$this->set_footer_type();
	}

	public function theme_body_classes($classes)
	{
		if (is_rtl()) {
			$classes[] = 'rtl';
		}

		$layout_content = Helper::setting('layout_content');
		$site_layout  = Helper::get_post_meta('site_layout', '');
		$post_archive_sidebar_position = Helper::setting('post_archive_sidebar_position');
		$single_post_sidebar_position = Helper::setting('single_post_sidebar_position');
		$single_post_style = Helper::setting('single_post_style');

		if ($site_layout === '') {
			$classes[] = $layout_content;
		} else {
			$classes[] = $site_layout;
		}

		if (is_archive()) {
			if ($post_archive_sidebar_position === 'left') {
				$classes[] = 'left-sidebar';
			} elseif ($post_archive_sidebar_position === 'none') {
				$classes[] = 'no-sidebar';
			} elseif ($post_archive_sidebar_position === 'right') {
				$classes[] = 'right-sidebar';
			}
		} elseif (is_singular()) {
			$post_type = get_post_type();

			switch ($post_type) {
				case 'post':
					if ($single_post_sidebar_position === 'left') {
						$classes[] = 'left-sidebar';
					} elseif ($single_post_sidebar_position === 'none') {
						$classes[] = 'no-sidebar';
					} elseif ($single_post_sidebar_position === 'right') {
						$classes[] = 'right-sidebar';
					}

					if ($single_post_style) {
						$classes[] = 'single-post-' . $single_post_style;
					}
					break;

				default:
					$classes[] = 'right-sidebar';
					break;
			}
		}

		$body_class = Helper::get_post_meta('body_class', '');
		if ($body_class !== '') {
			$classes[] = $body_class;
		}

		return $classes;
	}

	public static function delete_resized_images($post_id)
	{
		// Get attachment image metadata
		$metadata = wp_get_attachment_metadata($post_id);
		if (!$metadata)
			return;

		// Do some bailing if we cannot continue
		if (!isset($metadata['file']) || !isset($metadata['image_meta']['resized_images']))
			return;
		$pathinfo = pathinfo($metadata['file']);
		$resized_images = $metadata['image_meta']['resized_images'];

		// Get Wordpress uploads directory (and bail if it doesn't exist)
		$wp_upload_dir = wp_upload_dir();
		$upload_dir = $wp_upload_dir['basedir'];
		if (!is_dir($upload_dir))
			return;

		// Delete the resized images
		foreach ($resized_images as $dims) {

			// Get the resized images filename
			$file = $upload_dir . '/' . $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '-' . $dims . '.' . $pathinfo['extension'];

			// Delete the resized image
			@unlink($file);
		}
	}

	public static function get_topbar_type()
	{
		return self::$topbar_type;
	}

	public static function get_header_type()
	{
		return self::$header_type;
	}

	public static function get_header_overlay()
	{
		return self::$header_overlay;
	}

	public static function get_header_float()
	{
		return self::$header_float;
	}

	public static function get_page_title_type()
	{
		return self::$page_title_type;
	}

	public static function get_footer_type()
	{
		return self::$footer_type;
	}

	public static function get_list_templates($default_option = true, $post_type = null, $term_slug = null, $none_option = false)
	{
		$query_args = array(
			'posts_per_page' => -1,
			'post_type'      => $post_type,
		);

		// Add tax_query only if $term_slug is not null
		if ($term_slug !== null) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'elementor_library_category',
					'field'    => 'slug',
					'terms'    => $term_slug,
				),
			);
		}

		$templates = get_posts($query_args);

		$arr_template = array();
		if ($default_option === true) {
			$default_text = esc_html__('Default', 'togo');
			$arr_template = array('' => $default_text) + $arr_template;
		}
		if ($none_option === true) {
			$none_text = esc_html__('None', 'togo');
			$arr_template = array('none' => $none_text) + $arr_template;
		}
		foreach ($templates as $template) {
			$arr_template[$template->ID] = $template->post_title;
		}

		return $arr_template;
	}

	function set_footer_type()
	{
		$type = Helper::get_post_meta('footer_type', '');

		if ($type === '' || $type === false) {
			$type = Helper::setting('footer_type');
		}

		self::$footer_type = $type;
	}

	function set_topbar_type()
	{
		$type = Helper::get_post_meta('top_bar_type', '');
		if ($type === '' || $type === false) {
			$type = Helper::setting('top_bar_type');
		}
		if ($type === 'none') {
			$type = '';
		}

		self::$topbar_type = $type;
	}

	function set_header_type()
	{
		$type = Helper::get_post_meta('header_type', '');
		if ($type === '' || $type === false) {
			$type = Helper::setting('header_type');
		}

		self::$header_type = $type;
	}

	function set_header_options()
	{
		$header_overlay = Helper::get_post_meta('header_overlay', '');
		$header_float = Helper::get_post_meta('header_float', '');

		if ($header_overlay === '') {
			$header_overlay = Helper::setting('header_overlay');
		}

		if ($header_float === '') {
			$header_float = Helper::setting('header_float');
		}

		$header_overlay = apply_filters('togo_header_overlay', $header_overlay);
		$header_float = apply_filters('togo_header_float', $header_float);

		self::$header_overlay = $header_overlay;
		self::$header_float = $header_float;
	}

	function set_page_title_type()
	{
		$type = Helper::get_post_meta('page_page_title_layout', '');

		if ($type === '') {
			if (class_exists('WooCommerce') && Woo\Helper::is_woocommerce_page_without_product()) {
				$type = Helper::setting('product_archive_page_title_layout');
			} elseif (is_archive()) {
				$type = Helper::setting('blog_archive_page_title_layout');
			} elseif (is_singular('post')) {
				$type = Helper::setting('single_post_page_title_layout');
			} elseif (is_singular('page')) {
				$type = Helper::setting('page_page_title_layout');
			} elseif (is_singular('product')) {
				$type = Helper::setting('product_single_page_title_layout');
			} else {
				$type = Helper::setting('page_title_layout');
			}

			if ($type === '') {
				$type = Helper::setting('page_title_layout');
			}
		}

		$type = apply_filters('togo_page_title_type', $type);

		self::$page_title_type = $type;
	}

	public static function render_sidebar($position = 'right')
	{
		$classes 		  = array();
		$classes[]        = 'sidebar-' . $position;
		$active_sidebar   = 'blog_sidebar';
		$sidebar_position = 'right';

		if (\Togo\Blog::is_archive()) {
			$sidebar_position = Helper::setting('blog_archive_sidebar_position');
			$active_sidebar   = Helper::setting('blog_archive_active_sidebar');
			$classes[]        = 'sidebar-blog-archive';
		} elseif (is_archive()) {

			if (\Togo\Woo\Helper::is_product_archive()) {
				$sidebar_position = Helper::setting('product_archive_sidebar_position');
				$active_sidebar   = Helper::setting('product_archive_active_sidebar');
				$classes[]        = 'sidebar-product-archive';
			} else {
				$sidebar_position = Helper::setting('blog_archive_sidebar_position');
				$active_sidebar   = Helper::setting('blog_archive_active_sidebar');
				$classes[]        = 'sidebar-blog-archive';
			}
		} elseif (class_exists('WooCommerce') && \Togo\Woo\Helper::is_product_archive()) {
			$sidebar_position = Helper::setting('product_archive_sidebar_position');
			$active_sidebar   = Helper::setting('product_archive_active_sidebar');
			$classes[]        = 'sidebar-product-archive';
		} elseif (is_search()) {
			$sidebar_position = Helper::setting('blog_archive_sidebar_position');
			$active_sidebar   = Helper::setting('blog_archive_active_sidebar');
		} elseif (is_singular()) {
			$post_type = get_post_type();
			// Get values from page options.
			$sidebar_position = Helper::get_post_meta('sidebar_position', 'default');
			$active_sidebar   = Helper::get_post_meta('active_sidebar', 'default');

			switch ($post_type) {
				case 'post':
					$single_post_style = Helper::setting('single_post_style');
					if ($sidebar_position === '' || $sidebar_position === 'default') {
						$sidebar_position = Helper::setting('single_post_sidebar_position');
					}
					if ($active_sidebar === '' || $active_sidebar === 'default') {
						$active_sidebar = Helper::setting('single_post_active_sidebar');
					}
					if ($single_post_style === '02') {
						$sidebar_position = 'none';
						$active_sidebar   = 'none';
					}
					$classes[] = 'sidebar-single-post';
					break;

				case 'product':
					$classes[] = 'sidebar-single-product';
					break;

				case 'togo_mega_menu':
					$sidebar_position = 'none';
					break;

				default:
					if ($sidebar_position === 'default') {
						$sidebar_position = Helper::setting('page_sidebar_position');
					}
					if ($active_sidebar === 'default') {
						$active_sidebar = Helper::setting('page_active_sidebar');
					}
					break;
			}
		}

		$sidebar_position = apply_filters('togo_sidebar_position', $sidebar_position);

		if ($position === $sidebar_position) {
			self::get_sidebar($classes, $active_sidebar);
		}
	}

	public static function get_sidebar($classes, $name)
	{
		if (!is_active_sidebar($name)) {
			return;
		}
?>
		<aside id="secondary" class="<?php echo join(' ', $classes); ?>">
			<div class="inner-sidebar" itemscope="itemscope">
				<?php dynamic_sidebar($name); ?>
			</div>
		</aside>
<?php
	}
}
