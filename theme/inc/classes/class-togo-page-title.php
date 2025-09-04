<?php
defined('ABSPATH') || exit;

if (!class_exists('Togo_Page_Title')) {

	class Togo_Page_Title
	{

		protected static $instance = null;

		public static function instance()
		{
			if (null === self::$instance) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize()
		{
			// Adds custom classes to the array of body classes.
			add_filter('body_class', [$this, 'body_classes']);
			add_action('togo_after_header', [$this, 'render']);
		}

		public function body_classes($classes)
		{
			$page_title = self::page_title_type();
			$classes[] = "page-title-{$page_title}";

			/**
			 * Add class to hide entry title if this title bar has post title also.
			 */
			// Page Title support heading.
			if (in_array($page_title, ['01', '02', '03'], true) && is_singular()) {
				$post_type = get_post_type();
				$title     = '';

				switch ($post_type) {
					case 'post':
						$title = Togo\Helper::setting('page_title_single_blog_title');
						break;
					case 'product':
						$title = Togo\Helper::setting('page_title_single_product_title');
						break;
				}

				if ('' === $title) {
					$classes[] = 'page-title-has-post-title';
				}
			}

			return $classes;
		}

		public function the_wrapper_class()
		{
			$classes = array('page-title');

			$type = self::page_title_type();

			$classes[] = "page-title-{$type}";

			echo 'class="' . esc_attr(join(' ', $classes)) . '"';
		}

		public function page_title_type()
		{
			$type = Togo\Helper::get_post_meta('page_page_title_layout', '');

			if ($type === '') {
				if (class_exists('WooCommerce') && Togo\Woo\Helper::is_woocommerce_page_without_product()) {
					$type = Togo\Helper::setting('product_archive_page_title_layout');
				} elseif (Togo\Blog::is_archive()) {
					$type = Togo\Helper::setting('blog_archive_page_title_layout');
				} elseif (is_singular('post')) {
					$type = Togo\Helper::setting('single_post_page_title_layout');
				} elseif (is_singular('page')) {
					$type = Togo\Helper::setting('page_page_title_layout');
				} else {
					$type = Togo\Helper::setting('page_title_layout');
				}

				if ($type === '') {
					$type = Togo\Helper::setting('page_title_layout');
				}
			}

			$type = apply_filters('togo_page_title_type', $type);

			return $type;
		}

		public static function get_list($default_option = false)
		{
			$page_titles = get_posts(array(
				'posts_per_page' => -1,
				'post_type'      => 'elementor_library',
				'tax_query'      => array(
					array(
						'taxonomy' => 'elementor_library_category',
						'field'    => 'slug',
						'terms'    => 'page-title',
					)
				),
			));

			$arr_page_title = array('none' => __('None', 'togo'));
			if ($default_option === true) {
				$default_text = esc_html__('Default', 'togo');
				$arr_page_title   = array('' => $default_text) + $arr_page_title;
			}
			foreach ($page_titles as $page_title) {
				$arr_page_title[$page_title->ID] = $page_title->post_title;
			}

			return $arr_page_title;
		}

		public function render()
		{
			$type = self::page_title_type();
			if ('none' === $type || is_404()) {
				// Do nothing if type is 'none', 404 page, or type is empty.
			} elseif (class_exists('\Elementor\Plugin')) {
				echo \Elementor\Plugin::$instance->frontend->get_builder_content($type);
			} else {
				if (function_exists('get_template_part')) {
					get_template_part('templates/page-title/default');
				}
			}
		}

		public function render_title()
		{
			$title     = '';
			$title_tag = 'h1';

			if (is_post_type_archive()) {
				if (function_exists('is_shop') && is_shop()) {
					$title = esc_html__('Shop', 'togo');
				} else {
					$title = sprintf(esc_html__('Archives: %s', 'togo'), post_type_archive_title('', false));
				}
			} elseif (is_home()) {
				$title = Togo\Helper::setting('page_title_home_title') . single_tag_title('', false);
			} elseif (is_tag()) {
				$title = Togo\Helper::setting('page_title_archive_tag_title') . single_tag_title('', false);
			} elseif (is_author()) {
				$title = Togo\Helper::setting('page_title_archive_author_title') . '<span class="vcard">' . get_the_author() . '</span>';
			} elseif (is_year()) {
				$title = Togo\Helper::setting('page_title_archive_year_title') . get_the_date(esc_html_x('Y', 'yearly archives date format', 'togo'));
			} elseif (is_month()) {
				$title = Togo\Helper::setting('page_title_archive_month_title') . get_the_date(esc_html_x('F Y', 'monthly archives date format', 'togo'));
			} elseif (is_day()) {
				$title = Togo\Helper::setting('page_title_archive_day_title') . get_the_date(esc_html_x('F j, Y', 'daily archives date format', 'togo'));
			} elseif (is_search()) {
				$title = Togo\Helper::setting('page_title_search_title') . '"' . get_search_query() . '"';
			} elseif (is_category() || is_tax()) {
				$title = Togo\Helper::setting('page_title_archive_category_title') . single_cat_title('', false);
			} elseif (is_singular()) {
				$title = Togo\Helper::get_post_meta('page_page_title_custom_heading', '');

				if ('' === $title) {
					$post_type = get_post_type();
					switch ($post_type) {
						case 'post':
							$title = Togo\Helper::setting('page_title_single_blog_title');
							break;
						case 'product':
							$title = Togo\Helper::setting('page_title_single_product_title');
							break;
					}
				}

				if ('' === $title) {
					$title = get_the_title();
				} else {
					$title_tag = 'h2';
				}
				if (class_exists('WooCommerce')) {
					if (is_cart() || is_checkout()) {
						$title_tag = 'h2';
					}
				}
			} else {
				$title = get_the_title();
			}
?>
			<div class="page-title-heading">
				<?php printf('<%s class="heading heading-font">', $title_tag); ?>
				<?php echo wp_kses($title, array(
					'span' => [
						'class' => [],
					],
				)); ?>
				<?php printf('</%s>', $title_tag); ?>
			</div>
<?php
		}
	}

	Togo_Page_Title::instance()->initialize();
}
