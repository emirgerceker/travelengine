<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

$type_loading_effect      = Togo\Helper::setting('type_loading_effect');
$animation_loading_effect = Togo\Helper::setting('animation_loading_effect');
$image_loading_effect     = Togo\Helper::setting('image_loading_effect');

$args = array('css-1'  => '<span class="togo-ldef-circle togo-ldef-loading"><span></span></span>', 'css-2'  => '<span class="togo-ldef-dual-ring togo-ldef-loading"></span>', 'css-3' => '<span class="togo-ldef-facebook togo-ldef-loading"><span></span><span></span><span></span></span>', 'css-4'  => '<span class="togo-ldef-heart togo-ldef-loading"><span></span></span>', 'css-5'  => '<span class="togo-ldef-ring togo-ldef-loading"><span></span><span></span><span></span><span></span></span>', 'css-6'  => '<span class="togo-ldef-roller togo-ldef-loading"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></span>', 'css-7'  => '<span class="togo-ldef-default togo-ldef-loading"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></span>', 'css-8'  => '<span class="togo-ldef-ellipsis togo-ldef-loading"><span></span><span></span><span></span><span></span></span>', 'css-9'  => '<span class="togo-ldef-grid togo-ldef-loading"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></span>', 'css-10'  => '<span class="togo-ldef-hourglass togo-ldef-loading"></span>', 'css-11'  => '<span class="togo-ldef-ripple togo-ldef-loading"><span></span><span></span></span>', 'css-12'  => '<span class="togo-ldef-spinner togo-ldef-loading"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></span>');
?>

<?php if ($type_loading_effect !== 'none') { ?>
	<div id="page-preloader" class="page-loading-effect">
		<div class="bg-overlay"></div>

		<div class="page-loading-inner">
			<?php if ($type_loading_effect == 'css_animation') { ?>
				<?php echo wp_kses($args[$animation_loading_effect], Togo\Helper::togo_kses_allowed_html()); ?>
			<?php } ?>

			<?php if ($type_loading_effect == 'image') { ?>
				<img src="<?php echo esc_url($image_loading_effect); ?>" alt="<?php esc_attr_e('Image Effect', 'togo'); ?>">
			<?php } ?>
		</div>
	</div>
<?php } ?>