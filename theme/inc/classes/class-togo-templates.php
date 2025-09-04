<?php

namespace Togo;

use Togo\Helper;
use Togo\Theme;
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

class Templates
{

	/**
	 * The constructor.
	 */
	function __construct() {}

	public static function site_logo($type = 'light', $important = false)
	{

		$logo        = '';
		$logo_retina = '';

		$logo_dark         = Helper::setting('logo_dark');
		$logo_dark_retina  = Helper::setting('logo_dark_retina');
		$logo_light        = Helper::setting('logo_light');
		$logo_light_retina = Helper::setting('logo_light_retina');
		if ($type == 'light') {

			if ($logo_dark) {
				$logo = $logo_dark;
			}

			if ($logo_dark_retina) {
				$logo_retina = $logo_dark_retina;
			}
		}

		if ($type == 'dark') {

			if ($logo_light) {
				$logo = $logo_light;
			}

			if ($logo_light_retina) {
				$logo_retina = $logo_light_retina;
			}
		}

		$site_name = get_bloginfo('name', 'display');
		ob_start();
?>
		<div class="site-logo">
			<?php if (!empty($logo)) : ?>
				<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr($site_name); ?>">
					<img class="site-main-logo" src="<?php echo esc_url($logo); ?>" data-retina="<?php echo esc_attr($logo_retina); ?>" alt="<?php echo esc_attr($site_name); ?>">
					<img class="site-dark-logo hide" src="<?php echo esc_url($logo_light); ?>" data-retina="<?php echo esc_attr($logo_light_retina); ?>" alt="<?php echo esc_attr($site_name); ?>">
				</a>
			<?php else : ?>
				<?php $blog_info = get_bloginfo('name'); ?>
				<?php if (!empty($blog_info)) : ?>
					<h1 class="site-title"><?php bloginfo('name'); ?></h1>
					<p><?php bloginfo('description'); ?></p>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	<?php

		return ob_get_clean();
	}

	public static function main_menu()
	{
		$classes = array('main-menu', 'ux-element', 'site-menu', 'desktop-menu', 'hidden-on-tablet', 'hidden-on-mobile');

		ob_start();
	?>
		<div class="<?php echo join(' ', $classes); ?>" data-id="main-menu">
			<?php
			$args = array('theme_location' => 'main_menu');

			$defaults = array(
				'theme_location' => 'main_menu',
				'container'      => 'ul',
				'menu_class'     => 'menu sm sm-simple',
				'extra_class'    => '',
			);

			$args = wp_parse_args($args, $defaults);

			if (has_nav_menu('main_menu') && class_exists('Togo_Walker_Nav_Menu')) {
				$args['walker'] = new \Togo_Walker_Nav_Menu;
			}

			if (has_nav_menu('main_menu')) {
				wp_nav_menu($args);
			}
			?>
		</div>
	<?php
		return ob_get_clean();
	}

	public static function canvas_menu($settings = [])
	{
		ob_start();
	?>
		<div class="canvas-menu-wrapper <?php echo !empty($settings['alignment']) ? esc_attr($settings['alignment']) : ''; ?>">
			<div class="canvas-menu-icon"><?php echo \Togo\Icon::get_svg('menu'); ?></div>
			<?php echo self::mobile_menu($settings); ?>
		</div>
	<?php
		return ob_get_clean();
	}

	public static function mobile_menu($settings = [])
	{
		$label = array_key_exists('label', $settings) ? $settings['label'] : __('Menu', 'togo');
		$language_list = array_key_exists('language_list', $settings) ? $settings['language_list'] : '';
		$currency_list = array_key_exists('currency_list', $settings) ? $settings['currency_list'] : '';
		$show_profile = array_key_exists('show_profile', $settings) ? $settings['show_profile'] : '';
		ob_start();
	?>
		<div class="mobile-menu-wrapper">
			<div class="mobile-menu-overlay"></div>
			<div class="mobile-menu-content">
				<div class="mobile-menu-top">
					<?php if (!empty($show_profile) && $show_profile == 'yes') : ?>
						<div class="mobile-menu-user">
							<?php
							$user = wp_get_current_user();

							echo '<div class="togo-user">';
							if (is_user_logged_in()) {
								$url = $settings['logged_in_url']['url'];
								$target = $settings['logged_in_url']['is_external'] ? $settings['logged_in_url']['target'] : '_self';
								$avatar = get_avatar($user->ID, 40, '', $user->display_name);
								$avatar_id = get_user_meta($user->ID, 'avatar', true);

								echo '<div class="togo-user-icon">';
								if ($avatar_id) {
									echo wp_get_attachment_image($avatar_id, array(32, 32), false, array('class' => 'avatar'));
								} else {
									echo wp_kses_post($avatar);
								}
								echo '<span class="name">' . $user->display_name . '</span>';
								echo \Togo\Icon::get_svg('chevron-down');
								echo '</div>';

								if (!empty($settings['menu'])) {
									echo '<div class="user-submenu">';
									echo '<div class="user-menu">';
									foreach ($settings['menu'] as $item) {
										echo '<a href="' . esc_url($item['url']['url']) . '" target="' . esc_attr($item['url']['is_external'] ? $item['url']['target'] : '_self') . '" >';
										echo '<span>' . $item['title'] . '</span>';
										echo '</a>';
									}
									echo '</div>';
									echo '</div>';
								}
							} else {
								$url = $settings['not_logged_in_url']['url'];
								$target = $settings['not_logged_in_url']['is_external'] ? $settings['not_logged_in_url']['target'] : '_self';
								echo '<a href="' . esc_url($url) . '" target="' . esc_attr($target) . '" class="togo-user-icon">';
								echo \Togo\Icon::get_svg('user-circle');
								echo '<span class="name">' . __('Login', 'togo') . '</span>';
								echo '</a>';
							}

							echo '</div>';
							?>
						</div>
					<?php endif; ?>
					<div class="mobie-menu-back">
						<?php echo \Togo\Icon::get_svg('arrow-left', 'mobile-menu-back'); ?>
						<span class="mobile-menu-back-text"><?php _e('Back', 'togo'); ?></span>
					</div>
					<?php echo \Togo\Icon::get_svg('x', 'mobile-menu-close'); ?>
				</div>
				<div class="mobile-menu-center">
					<?php
					if (has_nav_menu('mobile_menu')) {
						$theme_location = array_key_exists('menu_content', $settings) ? $settings['menu_content'] : 'mobile_menu';
					} else {
						$theme_location = array_key_exists('menu_content', $settings) ? $settings['menu_content'] : 'main_menu';
					}

					$args = array(
						'menu_class'     => 'mb-menu',
						'container'      => '',
						'theme_location' => $theme_location,
					);

					if (has_nav_menu('main_menu') && class_exists('Togo_Walker_Nav_Menu')) {
						$args['walker'] = new \Togo_Walker_Nav_Menu;
					}

					wp_nav_menu($args);
					?>
					<?php if (!empty($settings['button_enabled']) && $settings['button_enabled'] == 'yes') {
						$is_external = $settings['button_url']['is_external'] ? $settings['button_url']['target'] : '_self';
						if (!empty($settings['button_url']['url'])) {
							echo '<div class="moile-menu-btn">';
							echo '<a href="' . $settings['button_url']['url'] . '" target="' . $is_external . '" class="togo-button full-filled">';
							$icon = $settings['button_icon'] ? trim($settings['button_icon']['value']) : '';
							if (!empty($icon)) {
								$icon_name = str_replace('togo-svg ', '', $icon);
								echo \Togo\Icon::get_svg($icon_name);
							}
							echo esc_html($settings['button_text']);
							echo '</a>';
							echo '</div>';
						}
					} ?>
				</div>
				<?php if (!empty($language_list) || !empty($currency_list)) : ?>
					<div class="mobile-menu-bottom">
						<div class="lc-wapper">
							<div class="lc-button">
								<span><?php echo esc_html($label); ?></span>
								<?php echo \Togo\Icon::get_svg('chevron-down'); ?>
							</div>
							<div class="lc-content">
								<?php if (!empty($language_list)) : ?>
									<div class="lc-item">
										<h4 class="lc-title"><?php echo esc_html__('Language', 'togo'); ?></h4>
										<ul class="lc-list">
											<?php foreach ($language_list as $language) : ?>
												<li>
													<a href="<?php echo esc_url($language['url']['url']); ?>">
														<?php echo esc_html($language['label']); ?>
													</a>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endif; ?>
								<?php if (!empty($currency_list)) : ?>
									<div class="lc-item">
										<h4 class="lc-title"><?php echo esc_html__('Currencies', 'togo'); ?></h4>
										<ul class="lc-list">
											<?php foreach ($currency_list as $currency) : ?>
												<li>
													<a href="<?php echo esc_url($currency['url']['url']); ?>">
														<?php echo esc_html($currency['label']); ?>
													</a>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php
		return ob_get_clean();
	}

	/**
	 * Render Comments
	 */
	public static function render_comments($comment, $args, $depth)
	{
		Helper::togo_get_template('comment', array('comment' => $comment, 'args' => $args, 'depth' => $depth));
	}

	public static function render_button($args)
	{
		$defaults = [
			'text'          => '',
			'link'          => [
				'url'         => '',
				'is_external' => false,
				'nofollow'    => false,
			],
			'style'         => 'flat',
			'size'          => 'sm',
			'icon'          => '',
			'icon_align'    => 'left',
			'extra_class'   => '',
			'class'         => 'togo-button',
			'id'            => '',
			'wrapper_class' => '',
		];

		$args = wp_parse_args($args, $defaults);
		extract($args);

		$button_attrs = [];

		$button_classes   = [$class];
		$button_classes[] = $style;
		$button_classes[] = 'size-' . $size;

		if (!empty($extra_class)) {
			$button_classes[] = $extra_class;
		}

		if (!empty($icon)) {
			$button_classes[] = 'icon-' . $icon_align;
		}

		$button_attrs['class'] = implode(' ', $button_classes);

		if (!empty($id)) {
			$button_attrs['id'] = $id;
		}

		$button_tag = 'div';

		if (!empty($link['url'])) {
			$button_tag = 'a';

			$button_attrs['href'] = $link['url'];

			if (!empty($link['is_external'])) {
				$button_attrs['target'] = '_blank';
			}

			if (!empty($link['nofollow'])) {
				$button_attrs['rel'] = 'nofollow';
			}
		}

		$attributes_str = '';

		if (!empty($button_attrs)) {
			foreach ($button_attrs as $attribute => $value) {
				$attributes_str .= ' ' . $attribute . '="' . esc_attr($value) . '"';
			}
		}

		$wrapper_classes = 'togo-button-wrapper';
		if (!empty($wrapper_class)) {
			$wrapper_classes .= " $wrapper_class";
		}
	?>
		<div class="<?php echo esc_attr($wrapper_classes); ?>">
			<?php printf('<%1$s %2$s>', $button_tag, $attributes_str); ?>
			<div class="button-content-wrapper">

				<?php if (!empty($icon) && 'left' === $icon_align) : ?>
					<span class="button-icon"><i class="<?php echo esc_attr($icon); ?>"></i></span>
				<?php endif; ?>

				<?php if (!empty($text)) : ?>
					<span class="button-text"><?php echo esc_html($text); ?></span>
				<?php endif; ?>

				<?php if (!empty($icon) && 'right' === $icon_align) : ?>
					<span class="button-icon"><i class="<?php echo esc_attr($icon); ?>"></i></span>
				<?php endif; ?>
			</div>
			<?php printf('</%1$s>', $button_tag); ?>
		</div>
		<?php
	}

	public static function get_sharing_list($args = array())
	{
		$defaults = array(
			'style'            => 'icons',
			'target'           => '_blank',
			'tooltip_enable'   => true,
			'tooltip_skin'     => 'primary',
			'tooltip_position' => 'top',
		);
		$args = wp_parse_args($args, $defaults);
		$social_sharing_item_enable = Helper::setting('social_sharing_item_enable', '1');
		if (!empty($social_sharing_item_enable)) {
			$social_sharing_order = Helper::setting('social_sharing_order');
			$link_classes = '';

			if ($args['tooltip_enable'] === true) {
				$link_classes .= "hint--bounce hint--{$args['tooltip_position']} hint--{$args['tooltip_skin']}";
			}

			foreach ($social_sharing_order as $social) {
				if (in_array($social, $social_sharing_item_enable, true)) {
					if ($social === 'facebook') {
						$facebook_url = 'https://m.facebook.com/sharer.php?u=' . rawurlencode(get_permalink());
		?>
						<a class="<?php echo esc_attr($link_classes . ' facebook'); ?>" target="<?php echo esc_attr($args['target']); ?>" aria-label="<?php esc_attr_e('Facebook', 'togo'); ?>" href="<?php echo esc_url($facebook_url); ?>">
							<?php if ($args['style'] === 'text') : ?>
								<span><?php esc_html_e('Facebook', 'togo'); ?></span>
							<?php else : ?>
								<i class="fab fa-facebook-f"></i>
							<?php endif; ?>
						</a>
					<?php
					} elseif ($social === 'twitter') {
					?>
						<a class="<?php echo esc_attr($link_classes . ' twitter'); ?>" target="<?php echo esc_attr($args['target']); ?>" aria-label="<?php esc_attr_e('Twitter', 'togo'); ?>" href="https://twitter.com/share?text=<?php echo rawurlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')); ?>&url=<?php echo rawurlencode(get_permalink()); ?>">
							<?php if ($args['style'] === 'text') : ?>
								<span><?php esc_html_e('Twitter', 'togo'); ?></span>
							<?php else : ?>
								<i class="fab fa-twitter"></i>
							<?php endif; ?>
						</a>
					<?php
					} elseif ($social === 'tumblr') {
					?>
						<a class="<?php echo esc_attr($link_classes . ' tumblr'); ?>" target="<?php echo esc_attr($args['target']); ?>" aria-label="<?php esc_attr_e('Tumblr', 'togo'); ?>" href="https://www.tumblr.com/share/link?url=<?php echo rawurlencode(get_permalink()); ?>&amp;name=<?php echo rawurlencode(get_the_title()); ?>">
							<?php if ($args['style'] === 'text') : ?>
								<span><?php esc_html_e('Tumblr', 'togo'); ?></span>
							<?php else : ?>
								<i class="fab fa-tumblr-square"></i>
							<?php endif; ?>
						</a>
					<?php
					} elseif ($social === 'linkedin') {
					?>
						<a class="<?php echo esc_attr($link_classes . ' linkedin'); ?>" target="<?php echo esc_attr($args['target']); ?>" aria-label="<?php esc_attr_e('Linkedin', 'togo'); ?>" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo rawurlencode(get_permalink()); ?>&amp;title=<?php echo rawurlencode(get_the_title()); ?>">
							<?php if ($args['style'] === 'text') : ?>
								<span><?php esc_html_e('Linkedin', 'togo'); ?></span>
							<?php else : ?>
								<i class="fab fa-linkedin"></i>
							<?php endif; ?>
						</a>
					<?php
					} elseif ($social === 'email') {
					?>
						<a class="<?php echo esc_attr($link_classes . ' email'); ?>" target="<?php echo esc_attr($args['target']); ?>" aria-label="<?php esc_attr_e('Email', 'togo'); ?>" href="mailto:?subject=<?php echo rawurlencode(get_the_title()); ?>&amp;body=<?php echo rawurlencode(get_permalink()); ?>">
							<?php if ($args['style'] === 'text') : ?>
								<span><?php esc_html_e('Email', 'togo'); ?></span>
							<?php else : ?>
								<i class="fas fa-envelope"></i>
							<?php endif; ?>
						</a>
			<?php
					}
				}
			}
		}
	}

	/**
	 * Display navigation to next/previous set of posts when applicable.
	 */
	public static function pagination($pagination_position = 'center')
	{
		global $wp_query, $wp_rewrite;

		// Don't print empty markup if there's only one page.
		if ($wp_query->max_num_pages < 2) {
			return;
		}

		if (is_archive()) {
			$pagination_position = Helper::setting('blog_archive_pagination_position');
		}

		$paged        = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
		$pagenum_link = wp_kses(get_pagenum_link(), Helper::togo_kses_allowed_html());
		$query_args   = array();
		$url_parts    = explode('?', $pagenum_link);

		if (isset($url_parts[1])) {
			wp_parse_str($url_parts[1], $query_args);
		}

		$pagenum_link = esc_url(remove_query_arg(array_keys($query_args), $pagenum_link));
		$pagenum_link = trailingslashit($pagenum_link) . '%_%';

		$format = $wp_rewrite->using_index_permalinks() && !strpos(
			$pagenum_link,
			'index.php'
		) ? 'index.php/' : '';
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit(
			$wp_rewrite->pagination_base . '/%#%',
			'paged'
		) : '?paged=%#%';

		// Set up paginated links.
		$links = paginate_links(array(
			'format'    => $format,
			'total'     => $wp_query->max_num_pages,
			'current'   => $paged,
			'add_args'  => array_map('urlencode', $query_args),
			'prev_text' => '<svg fill="none" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M15 6L9 12L15 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'next_text' => '<svg fill="none" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'type'      => 'list',
		));
		$pagination_classes = array('togo-pagination', $pagination_position);

		if ($links) {
			?>
			<div class="<?php echo join(' ', $pagination_classes); ?>">
				<?php echo wp_kses($links, Helper::togo_kses_allowed_html()); ?>
			</div><!-- .pagination -->
		<?php
		}
	}
	public static function scroll_bar()
	{
		ob_start();
		?>
		<div class="scroll-bar-wrap">
			<div class="scroll-bar-current"></div>
		</div>
<?php
		return ob_get_clean();
	}
}
