<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Hook action
 */
if (!class_exists('Togo_Hook')) {

	class Togo_Hook
	{
		/**
		 * The constructor.
		 */
		function __construct()
		{
			add_action('togo_after_body_open', array($this, 'pre_loader'));
			add_action('togo_after_footer', array($this, 'global_template'));
		}

		/**
		 * Register global template
		 */
		public static function pre_loader()
		{
			get_template_part('templates/global/site-loading');
		}

		/**
		 * Register global template
		 */
		public static function global_template()
		{
			self::scroll_top();
			self::content_protected();
		}

		public static function scroll_top()
		{
			$scroll_top_enable = Togo\Helper::setting('back_to_top', '0');

			if (!$scroll_top_enable) {
				return;
			}
?>
			<a class="page-scroll-up" id="page-scroll-up">
				<i class="arrow-top fal fa-angle-up"></i>
				<i class="arrow-bottom fal fa-angle-up"></i>
			</a>
		<?php
		}

		public static function content_protected()
		{
			$content_protected = Togo\Helper::setting('content_protected', '0');

			if (!$content_protected) {
				return;
			}
		?>
			<div id="togo-content-protected-box" class="togo-content-protected-box">
				<?php printf(esc_html__(
					'%sAlert:%s You are not allowed to copy content or view source !!',
					'togo'
				), '<span class="alert-label">', '</span>'); ?>
			</div>
<?php
		}
	}

	new Togo_Hook();
}
