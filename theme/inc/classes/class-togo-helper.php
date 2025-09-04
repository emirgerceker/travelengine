<?php

namespace Togo;

use Togo\Theme;
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

class Helper
{

	public static function get_fonts_url()
	{
		$fonts_url = '';

		$font_families[] = 'DM Sans:300,400,500,600,700';
		$font_families[] = 'Outfit:300,400,500,600,700';
		$font_families[] = 'Marcellus:400';

		$query_args = array(
			'family' => urlencode(implode('|', $font_families)),
			'subset' => urlencode('latin,latin-ext'),
		);

		$fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');

		return esc_url_raw($fonts_url);
	}
	/**
	 * Get Setting
	 */
	public static function setting($key, $default = '')
	{
		$option = '';
		$option = \Togo\Kirki::get_option('theme', $key);

		return (!empty($option)) ? $option : $default;
	}

	/**
	 * Clean Variable
	 */
	public static function togo_clean($var)
	{
		if (is_array($var)) {
			return array_map('togo_clean', $var);
		} else {
			return is_scalar($var) ? sanitize_text_field($var) : $var;
		}
	}

	public static function hexToRgb($hex)
	{
		// Remove '#' if it exists
		$hex = str_replace('#', '', $hex);

		// If it's a 3-character hex code, convert to 6 characters (e.g., #abc becomes #aabbcc)
		if (strlen($hex) == 3) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}

		// Convert hex to RGB
		return (int) hexdec(substr($hex, 0, 2)) . ', ' . (int) hexdec(substr($hex, 2, 2)) . ', ' . (int) hexdec(substr($hex, 4, 2));
	}

	/**
	 * Header Class
	 */
	public static function header_class($class = '')
	{
		$classes = array('site-header');

		$header_overlay = Theme::instance()->get_header_overlay();
		$header_float = Theme::instance()->get_header_float();

		if ($header_overlay === '1') {
			$classes[] = 'header-sticky';
		}

		if ($header_float === '1') {
			$classes[] = 'header-float';
		}

		if (!empty($class)) {
			if (!is_array($class)) {
				$class = preg_split('#\s+#', $class);
			}
			$classes = array_merge($classes, $class);
		} else {
			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		$classes = apply_filters('togo_header_class', $classes, $class);

		return join(' ', $classes);
	}

	/**
	 * Get Template
	 */
	public static function togo_get_template($slug, $args = array())
	{
		if ($args && is_array($args)) {
			extract($args);
		}
		$located = locate_template(array("templates/{$slug}.php"));

		if (!file_exists($located)) {
			_doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $slug), '1.0');
			return;
		}
		include($located);
	}

	/**
	 * Check File Base
	 */
	public static function check_file_base($name, $path)
	{
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		WP_Filesystem();
		global $wp_filesystem;

		$upload_dir = wp_upload_dir();
		$logger_dir = $upload_dir['basedir'] . '/togo/header';
		$file       =  trailingslashit($logger_dir) . $name . '.' . $path;
		$check_file = file_exists($file);

		return $check_file;
	}

	/**
	 * Get Content File Base
	 */
	public static function get_content_file_base($name, $path)
	{
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		WP_Filesystem();
		global $wp_filesystem;

		$upload_dir = wp_upload_dir();
		$logger_dir = $upload_dir['basedir'] . '/togo/header';
		$file       = $name . '.' . $path;
		$content    = $wp_filesystem->get_contents(trailingslashit($logger_dir) . $file);

		return $content;
	}

	public static function w3c_iframe($iframe)
	{
		$iframe = str_replace('frameborder="0"', '', $iframe);
		$iframe = str_replace('frameborder="no"', '', $iframe);
		$iframe = str_replace('scrolling="no"', '', $iframe);
		$iframe = str_replace('gesture="media"', '', $iframe);
		$iframe = str_replace('allow="encrypted-media"', '', $iframe);

		return $iframe;
	}

	public static function get_post_meta($name, $default = false)
	{
		global $togo_page_options;

		if ($togo_page_options != false && isset($togo_page_options[$name])) {
			return $togo_page_options[$name];
		}

		return $default;
	}

	public static function get_the_post_meta($options, $name, $default = false)
	{
		if ($options != false && isset($options[$name])) {
			return $options[$name];
		}

		return $default;
	}

	public static function get_registered_sidebars($default_option = false, $empty_option = true)
	{
		global $wp_registered_sidebars;
		$sidebars = array();
		if ($empty_option === true) {
			$sidebars['none'] = esc_html__('No Sidebar', 'togo');
		}
		if ($default_option === true) {
			$sidebars['default'] = esc_html__('Default', 'togo');
		}
		foreach ($wp_registered_sidebars as $sidebar) {
			$sidebars[$sidebar['id']] = $sidebar['name'];
		}

		return $sidebars;
	}

	/**
	 * Allowed_html
	 */
	public static function togo_kses_allowed_html()
	{
		$allowed_tags = array(
			'a' => array(
				'id'    => array(),
				'class' => array(),
				'href'  => array(),
				'rel'   => array(),
				'title' => array(),
			),
			'abbr' => array(
				'title' => array(),
			),
			'b' => array(),
			'blockquote' => array(
				'cite'  => array(),
			),
			'cite' => array(
				'title' => array(),
			),
			'code' => array(),
			'del' => array(
				'datetime' => array(),
				'title' => array(),
			),
			'dd' => array(),
			'div' => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'dl' => array(),
			'dt' => array(),
			'em' => array(),
			'h1' => array(),
			'h2' => array(),
			'h3' => array(),
			'h4' => array(),
			'h5' => array(),
			'h6' => array(),
			'i' => array(
				'class' => array(),
			),
			'img' => array(
				'alt'    => array(),
				'class'  => array(),
				'height' => array(),
				'src'    => array(),
				'width'  => array(),
			),
			'li' => array(
				'class' => array(),
			),
			'ol' => array(
				'class' => array(),
			),
			'p' => array(
				'class' => array(),
			),
			'q' => array(
				'cite' => array(),
				'title' => array(),
			),
			'span' => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'strike' => array(),
			'strong' => array(),
			'ul' => array(
				'class' => array(),
			),
			'svg'  => array(
				'xmlns'           => array(),
				'width'           => array(),
				'height'          => array(),
				'viewbox'         => array(),
				'viewBox'         => array(),
				'fill'            => array(),
				'stroke'          => array(),
				'class'           => array(),
				'aria-hidden'     => array(),
				'focusable'       => array(),
			),
			'path' => array(
				'd'                => array(),
				'fill'             => array(),
				'stroke'           => array(),
				'stroke-width'     => array(),
				'stroke-linecap'   => array(),
				'stroke-linejoin'  => array(),
			),
		);

		return $allowed_tags;
	}

	/**
	 * Image size
	 */
	public static function togo_image_resize($data, $image_size)
	{
		if (preg_match('/\d+x\d+/', $image_size)) {
			$image_sizes = explode('x', $image_size);
			$image_src  = self::togo_image_resize_id($data, $image_sizes[0], $image_sizes[1], true);
		} else {
			if (!in_array($image_size, array('full', 'thumbnail'))) {
				$image_size = 'full';
			}
			$image_src = wp_get_attachment_image_src($data, $image_size);
			if ($image_src && !empty($image_src[0])) {
				$image_src = $image_src[0];
			}
		}
		return $image_src;
	}

	/**
	 * Image resize by url
	 */
	public static function togo_image_resize_url($url, $width = NULL, $height = NULL, $crop = true, $retina = false)
	{

		global $wpdb;

		if (empty($url))
			return new WP_Error('no_image_url', esc_html__('No image URL has been entered.', 'togo'), $url);

		if (class_exists('\Jetpack') && method_exists('\Jetpack', 'get_active_modules') && in_array('photon', \Jetpack::get_active_modules())) {
			$args_crop = array(
				'resize' => $width . ',' . $height,
				'crop' => '0,0,' . $width . 'px,' . $height . 'px'
			);
			$url = jetpack_photon_url($url, $args_crop);
		}

		// Get default size from database
		$width = ($width) ? $width : get_option('thumbnail_size_w');
		$height = ($height) ? $height : get_option('thumbnail_size_h');

		// Allow for different retina sizes
		$retina = $retina ? ($retina === true ? 2 : $retina) : 1;

		// Get the image file path
		$file_path = parse_url($url);
		$file_path = TOGO_THEME_URI . $file_path['path'];
		$wp_upload_folder = wp_upload_dir();
		$wp_upload_folder = $wp_upload_folder['basedir'];
		$file_path = explode('/uploads/', $file_path);
		if (is_array($file_path)) {
			if (count($file_path) > 1) {
				$file_path = $wp_upload_folder . '/' . $file_path[1];
			} elseif (count($file_path) > 0) {
				$file_path = $wp_upload_folder . '/' . $file_path[0];
			} else {
				$file_path = '';
			}
		}

		// Check for Multisite
		if (is_multisite()) {
			global $blog_id;
			$blog_details = get_blog_details($blog_id);
			$file_path = str_replace($blog_details->path . 'files/', '/wp-content/blogs.dir/' . $blog_id . '/files/', $file_path);
		}

		// Destination width and height variables
		$dest_width = $width * $retina;
		$dest_height = $height * $retina;

		// File name suffix (appended to original file name)
		$suffix = "{$dest_width}x{$dest_height}";

		// Some additional info about the image
		$info = pathinfo($file_path);
		$dir = $info['dirname'];
		$ext = $info['extension'];
		$name = wp_basename($file_path, ".$ext");

		if ('bmp' == $ext) {
			return new WP_Error('bmp_mime_type', esc_html__('Image is BMP. Please use either JPG or PNG.', 'togo'), $url);
		}

		// Suffix applied to filename
		$suffix = "{$dest_width}x{$dest_height}";

		// Get the destination file name
		$dest_file_name = "{$dir}/{$name}-{$suffix}.{$ext}";

		if (!file_exists($dest_file_name)) {

			/*
	             *  Bail if this image isn't in the Media Library.
	             *  We only want to resize Media Library images, so we can be sure they get deleted correctly when appropriate.
	             */
			$query = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE guid='%s'", $url);
			$get_attachment = $wpdb->get_results($query);
			// if (!$get_attachment)
			//     return array('url' => $url, 'width' => $width, 'height' => $height);

			// Load Wordpress Image Editor
			$editor = wp_get_image_editor($file_path);
			if (is_wp_error($editor))
				return array('url' => $url, 'width' => $width, 'height' => $height);

			// Get the original image size
			$size = $editor->get_size();
			$orig_width = $size['width'];
			$orig_height = $size['height'];

			$src_x = $src_y = 0;
			$src_w = $orig_width;
			$src_h = $orig_height;

			if ($crop) {

				$cmp_x = $orig_width / $dest_width;
				$cmp_y = $orig_height / $dest_height;

				// Calculate x or y coordinate, and width or height of source
				if ($cmp_x > $cmp_y) {
					$src_w = round($orig_width / $cmp_x * $cmp_y);
					$src_x = round(($orig_width - ($orig_width / $cmp_x * $cmp_y)) / 2);
				} else if ($cmp_y > $cmp_x) {
					$src_h = round($orig_height / $cmp_y * $cmp_x);
					$src_y = round(($orig_height - ($orig_height / $cmp_y * $cmp_x)) / 2);
				}
			}

			// Time to crop the image!
			$editor->crop($src_x, $src_y, $src_w, $src_h, $dest_width, $dest_height);

			// Now let's save the image
			$saved = $editor->save($dest_file_name);

			// Get resized image information
			$resized_url = str_replace(wp_basename($url), wp_basename($saved['path']), $url);
			$resized_width = $saved['width'];
			$resized_height = $saved['height'];
			$resized_type = $saved['mime-type'];

			// Add the resized dimensions to original image metadata (so we can delete our resized images when the original image is delete from the Media Library)
			if ($get_attachment) {
				$metadata = wp_get_attachment_metadata($get_attachment[0]->ID);
				if (isset($metadata['image_meta'])) {
					$metadata['image_meta']['resized_images'][] = $resized_width . 'x' . $resized_height;
					wp_update_attachment_metadata($get_attachment[0]->ID, $metadata);
				}
			}

			// Create the image array
			$image_array = array(
				'url' => $resized_url,
				'width' => $resized_width,
				'height' => $resized_height,
				'type' => $resized_type
			);
		} else {
			$image_array = array(
				'url' => str_replace(wp_basename($url), wp_basename($dest_file_name), $url),
				'width' => $dest_width,
				'height' => $dest_height,
				'type' => $ext
			);
		}

		// Return image array
		return $image_array;
	}

	/**
	 * Image resize by id
	 */
	public static function togo_image_resize_id($images_id, $width = NULL, $height = NULL, $crop = true, $retina = false)
	{
		$output = '';
		$image_src = wp_get_attachment_image_src($images_id, 'full');
		if ($image_src) {
			$resize = self::togo_image_resize_url($image_src[0], $width, $height, $crop, $retina);
			if ($resize != null && is_array($resize)) {
				$output = $resize['url'];
			}
		}
		return $output;
	}

	public static function post_share()
	{
		$visibility = self::setting('single_post_share');
		if ($visibility == 'hide') return;
		echo '<div class="entry-share">';
		echo '<h6 class="entry-share-title">' . esc_html__('Share:', 'togo') . '</h6>';
		echo '<div class="entry-share-links">';
		echo '<a class="entry-share-facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . esc_url(get_permalink()) . '"><svg xmlns="http://www.w3.org/2000/svg" width="9" height="14" viewBox="0 0 9 14" fill="none"><path d="M3.26872 13.4173L3.25033 7.58398H0.916992V5.25065H3.25033V3.79232C3.25033 1.62733 4.59101 0.583984 6.52232 0.583984C7.44744 0.583984 8.24253 0.652859 8.47424 0.683647V2.94618L7.13478 2.94679C6.08442 2.94679 5.88105 3.4459 5.88105 4.17832V5.25065H8.93783L7.77116 7.58398H5.88105V13.4173H3.26872Z" fill="#51575E"/></svg></a>';
		echo '<a class="entry-share-twitter" target="_blank" href="https://twitter.com/share?url=' . esc_url(get_permalink()) . '"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M11.025 0.671875H13.1728L8.48167 6.03212L14 13.3273H9.68042L6.29475 8.90388L2.42492 13.3273H0.273583L5.29025 7.59254L0 0.671875H4.42925L7.4865 4.71496L11.025 0.671875ZM10.2708 12.0434H11.4602L3.78117 1.88871H2.50367L10.2708 12.0434Z" fill="#51575E"/></svg></a>';
		echo '<a class="entry-share-pinterest" target="_blank" href="https://pinterest.com/pin/create/button/?url=' . esc_url(get_permalink()) . '"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 0C3.15 0 0 3.15 0 7C0 9.975 1.86667 12.4833 4.43333 13.5333C4.375 13.0083 4.31667 12.1333 4.43333 11.55C4.55 11.025 5.25 8.05 5.25 8.05C5.25 8.05 5.075 7.58333 5.075 7C5.075 6.00833 5.65833 5.30833 6.35833 5.30833C6.94167 5.30833 7.23333 5.775 7.23333 6.3C7.23333 6.88333 6.825 7.81667 6.65 8.63333C6.475 9.33333 7 9.91667 7.7 9.91667C8.925 9.91667 9.91667 8.63333 9.91667 6.70833C9.91667 5.01667 8.69167 3.85 7 3.85C5.01667 3.85 3.85 5.36667 3.85 6.88333C3.85 7.46667 4.08333 8.10833 4.375 8.45833C4.43333 8.51667 4.43333 8.575 4.43333 8.63333C4.375 8.86667 4.25833 9.33333 4.25833 9.45C4.2 9.56667 4.14167 9.625 4.025 9.56667C3.15 9.15833 2.625 7.875 2.625 6.88333C2.625 4.66667 4.25833 2.625 7.23333 2.625C9.68333 2.625 11.55 4.375 11.55 6.65C11.55 9.04167 10.0333 11.025 7.93333 11.025C7.23333 11.025 6.53333 10.675 6.3 10.2083C6.3 10.2083 5.95 11.55 5.89167 11.9C5.71667 12.4833 5.30833 13.2417 5.01667 13.7083C5.6 13.8833 6.3 14 7 14C10.85 14 14 10.85 14 7C14 3.15 10.85 0 7 0Z" fill="#51575E"/></svg></a>';
		echo '<a class="entry-share-linkedin" target="_blank" href="https://www.linkedin.com/shareArticle?url=' . esc_url(get_permalink()) . '"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M13.4167 0H0.583333C0.233333 0 0 0.233333 0 0.583333V13.4167C0 13.7667 0.233333 14 0.583333 14H13.4167C13.7667 14 14 13.7667 14 13.4167V0.583333C14 0.233333 13.7667 0 13.4167 0ZM4.14167 11.9583H2.1V5.25H4.2V11.9583H4.14167ZM3.09167 4.31667C2.45 4.31667 1.86667 3.79167 1.86667 3.09167C1.86667 2.45 2.39167 1.86667 3.09167 1.86667C3.73333 1.86667 4.31667 2.39167 4.31667 3.09167C4.31667 3.79167 3.79167 4.31667 3.09167 4.31667ZM11.9583 11.9583H9.85833V8.69167C9.85833 7.93333 9.85833 6.94167 8.80833 6.94167C7.7 6.94167 7.58333 7.75833 7.58333 8.63333V11.9583H5.48333V5.25H7.46667V6.18333C7.75833 5.65833 8.4 5.13333 9.45 5.13333C11.55 5.13333 11.9583 6.53333 11.9583 8.34167V11.9583Z" fill="#51575E"/></svg></a>';
		echo '</div>';
		echo '</div>';
	}

	public static function get_taxonomy_terms($taxonomy, $has_children = false, $parent_hide_empty = false, $children_hide_empty = false)
	{
		$terms_array = array();

		// Get parent terms
		$args = array(
			'hide_empty' => $parent_hide_empty,
			'parent' => 0, // Fetch only parent terms
		);

		$parent_terms = get_terms($taxonomy, $args);

		if (!empty($parent_terms) && !is_wp_error($parent_terms)) {
			foreach ($parent_terms as $parent_term) {
				$terms_array[$parent_term->slug] = $parent_term->name;

				// If $has_children is true, fetch the child terms recursively
				if ($has_children) {
					$terms_array += \Togo\Helper::get_child_terms($parent_term->term_id, $taxonomy, $children_hide_empty);
				}
			}
		}

		return $terms_array;
	}

	public static function get_child_terms($parent_term_id, $taxonomy, $children_hide_empty, $level = 1)
	{
		$terms_with_children = array();

		$args = array(
			'hide_empty' => $children_hide_empty,
			'parent' => $parent_term_id, // Fetch children for this term
		);

		$child_terms = get_terms($taxonomy, $args);

		if (!empty($child_terms) && !is_wp_error($child_terms)) {
			foreach ($child_terms as $child_term) {
				$prefix = str_repeat('—', $level * 2); // Create visual hierarchy
				$terms_with_children[$child_term->slug] = $prefix . $child_term->name;

				// Recursively get the children of this child term
				$terms_with_children += \Togo\Helper::get_child_terms($child_term->term_id, $taxonomy, $children_hide_empty, $level + 1);
			}
		}

		return $terms_with_children;
	}

	public static function get_child_terms_by_parent_id($parent_id, $taxonomy, $hide_empty = false)
	{
		// Define the query arguments to fetch child terms
		$args = array(
			'hide_empty' => $hide_empty, // Set to true if you want to hide empty terms
			'parent'     => $parent_id, // Parent term ID
		);

		// Fetch child terms for the given taxonomy and parent ID
		$child_terms = get_terms($taxonomy, $args);

		// Initialize an array to hold the result
		$result = array();

		// Check for errors or empty result
		if (!is_wp_error($child_terms) && !empty($child_terms)) {
			foreach ($child_terms as $child_term) {
				// Populate the result array with term ID as the key and term name as the value
				array_push($result, $child_term->term_id);
			}
		}

		return $result; // Return the array with term IDs and names
	}

	public static function get_all_menus()
	{
		// Get all registered theme locations
		$locations = get_nav_menu_locations();

		// Create an array to store the result
		$result = array();

		// Iterate through each theme location
		foreach ($locations as $location => $menu_id) {
			// Find the menu corresponding to the menu_id
			$menu = wp_get_nav_menu_object($menu_id);
			if ($menu) {
				$result[$location] = $menu->name;
			} else {
				$result[$location] = esc_html__('No menu assigned', 'togo');
			}
		}

		return $result;
	}
}
