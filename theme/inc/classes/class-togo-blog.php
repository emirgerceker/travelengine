<?php

namespace Togo;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
class Blog
{

	protected static $instance  = null;
	protected static $post_type = 'post';

	/**
	 * Gets the instance of the class.
	 *
	 * This function ensures that only one instance of the class is created.
	 *
	 * @since 1.0.0
	 *
	 * @return object The instance of the class.
	 */
	public static function instance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct()
	{
		add_action('wp', array($this, 'add_actions'), 0);
	}

	public function add_actions()
	{
		\Togo\Blog\Posts::instance();
	}

	/**
	 * Adds additional classes to the post class.
	 *
	 * This function adds the 'post-no-thumbnail' class to the post class if the post doesn't have a thumbnail.
	 * It also adds the 'sticky' class to the post class if the post is a sticky post.
	 *
	 * @param array $classes An array of post classes.
	 * @return array The modified array of post classes.
	 */
	function post_class($classes)
	{
		// Check if the post doesn't have a thumbnail.
		if (!has_post_thumbnail()) {
			// Add the 'post-no-thumbnail' class to the post class.
			$classes[] = 'post-no-thumbnail';
		}

		// Check if the post is a sticky post.
		if (is_sticky()) {
			// Add the 'sticky' class to the post class.
			$classes[] = 'sticky';
		}

		// Return the modified array of post classes.
		return $classes;
	}

	/**
	 * Checks if the current page is an archive page.
	 *
	 * This function checks if the current page is an archive page, author page, category page, home page, or tag page.
	 * Additionally, it checks if the post type is 'post'.
	 *
	 * @return bool Returns true if the current page is an archive page, otherwise returns false.
	 */
	public static function is_archive()
	{
		// Check if the current page is an archive page, author page, category page, home page, or tag page.
		// Also check if the post type is 'post'.
		return (is_archive() || is_author() || is_category() || is_home() || is_tag()) && 'post' == get_post_type();
	}

	/**
	 * Retrieves the value of a specific meta field for the current post.
	 *
	 * @param string $name The name of the meta field to retrieve.
	 * @param mixed  $default The default value to return if the meta field is not found.
	 * @return mixed The value of the meta field, or the default value if the meta field is not found.
	 */
	function get_the_post_meta($name = '', $default = '')
	{
		// Retrieve the serialized post meta data.
		$post_meta = get_post_meta(get_the_ID(), 'togo_post_options', true);

		// If the post meta data is not empty, unserialize it.
		if (!empty($post_meta)) {
			$post_options = maybe_unserialize($post_meta);

			// If the post options are not empty and the specified meta field is set, return its value.
			if ($post_options !== false && isset($post_options[$name])) {
				return $post_options[$name];
			}
		}

		// Return the default value if the meta field is not found.
		return $default;
	}

	/**
	 * Retrieves the format of the current post.
	 *
	 * @return string The format of the current post. An empty string if the format is not set.
	 */
	function get_the_post_format()
	{
		// Initialize the format variable with an empty string.
		$format = '';

		// Check if the post format is set.
		if (get_post_format() !== false) {
			// If the format is set, assign it to the variable.
			$format = get_post_format();
		}

		// Return the format of the current post.
		return $format;
	}

	/**
	 * Outputs the categories of the current post.
	 *
	 * @param array $args An array of arguments to customize the output.
	 *                   - classes (string): The CSS class or classes to apply to the container div.
	 *                   - separator (string): The string to use as the separator between categories.
	 *                   - show_links (bool): Whether to output links to the categories.
	 *                   - single (bool): Whether to output only the first category.
	 */
	function the_categories($args = array())
	{
		// If the post doesn't have any categories, return.
		if (!has_category()) {
			return;
		}

		// Set the default arguments.
		$defaults = array(
			'classes'    => 'post-categories',
			'separator'  => ', ',
			'show_links' => true,
			'single'     => true,
		);
		$args     = wp_parse_args($args, $defaults);

		// Output the container div.
?>
		<div class="<?php echo esc_attr($args['classes']); ?>">
			<?php
			// Get the categories of the post.
			$categories = get_the_category();
			$loop_count = 0;

			// Loop through each category.
			foreach ($categories as $category) {
				// If this is not the first category, output the separator.
				if ($loop_count > 0) {
					echo "{$args['separator']}";
				}

				// Output the category link or text.
				if (true === $args['show_links']) {
					printf(
						'<a href="%1$s"><span>%2$s</span></a>',
						esc_url(get_category_link($category->term_id)),
						$category->name
					);
				} else {
					echo "<span>{$category->name}</span>";
				}

				$loop_count++;

				// If only the first category is to be outputted, break the loop.
				if (true === $args['single']) {
					break;
				}
			}
			?>
		</div>
	<?php
	}

	/**
	 * Outputs the entry feature based on the post format.
	 *
	 * @since 1.0.0
	 */
	function entry_feature()
	{
		// Get the post format.
		$post_format    = $this->get_the_post_format();
		// Set the thumbnail size.
		$thumbnail_size = '770x400';

		// Switch based on the post format.
		switch ($post_format) {
			// If the post format is gallery, output the gallery feature.
			case 'gallery':
				$this->entry_feature_gallery($thumbnail_size);
				break;
			// If the post format is audio, output the audio feature.
			case 'audio':
				$this->entry_feature_audio();
				break;
			// If the post format is video, output the video feature.
			case 'video':
				$this->entry_feature_video($thumbnail_size);
				break;
			// If the post format is quote, output the quote feature.
			case 'quote':
				$this->entry_feature_quote();
				break;
			// If the post format is link, output the link feature.
			case 'link':
				$this->entry_feature_link();
				break;
			// For all other post formats, output the standard feature.
			default:
				$this->entry_feature_standard($thumbnail_size);
				break;
		}
	}

	/**
	 * Outputs the standard entry feature for posts.
	 *
	 * @param string $size The size of the thumbnail.
	 * @since 1.0.0
	 */
	private function entry_feature_standard($size)
	{
		// Return if the post doesn't have a thumbnail.
		if (!has_post_thumbnail()) {
			return;
		}

		// Output the entry feature markup.
	?>
		<!-- Output the standard entry feature for posts. -->
		<div class="entry-post-feature post-thumbnail">
			<?php
			// Output the post thumbnail.
			Togo_Image::the_post_thumbnail([
				'size' => $size,
			]);
			?>
		</div>
	<?php
	}

	/**
	 * Outputs the gallery entry feature for posts.
	 *
	 * @param string $size The size of the thumbnail.
	 * @since 1.0.0
	 */
	private function entry_feature_gallery($size)
	{
		// Get the gallery images from the post meta data.
		$gallery = $this->get_the_post_meta('post_gallery');

		// Return if there are no gallery images.
		if (empty($gallery)) {
			return;
		}

		// Output the gallery entry feature markup.
	?>
		<!-- Output the gallery entry feature for posts. -->
		<div class="entry-post-feature post-gallery togo-swiper-slider togo-slider" data-nav="1" data-loop="1" data-lg-gutter="30">
			<div class="swiper-inner">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?php // Loop through each gallery image.
						foreach ($gallery as $image) { ?>
							<div class="swiper-slide">
								<?php // Output the gallery image.
								Togo_Image::the_attachment_by_id(array(
									'id'   => $image['id'],
									'size' => $size,
								)); ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Outputs the audio entry feature for posts.
	 *
	 * @since 1.0.0
	 */
	private function entry_feature_audio()
	{
		// Get the audio URL from the post meta data.
		$audio = $this->get_the_post_meta('post_audio');

		// Return if there is no audio URL.
		if (empty($audio)) {
			return;
		}

		// Check if the audio URL is an MP3 file.
		if (strrpos($audio, '.mp3') !== false) {
			// Output the audio shortcode for the MP3 file.
			echo do_shortcode('[audio mp3="' . $audio . '"][/audio]');
		} else {
			// Output the audio player for the audio URL.
		?>
			<div class="entry-post-feature post-audio">
				<?php
				// Check if the audio URL is embeddable.
				if (wp_oembed_get($audio)) {
					// Output the embedded audio player.
					echo Togo\Helper::w3c_iframe(wp_oembed_get($audio));
				}
				?>
			</div>
		<?php
		}
	}

	/**
	 * Outputs the video entry feature for posts.
	 *
	 * @param string $size The size of the video thumbnail.
	 * @since 1.0.0
	 */
	private function entry_feature_video($size)
	{
		// Get the video URL from the post meta data.
		$video = $this->get_the_post_meta('post_video');

		// Return if there is no video URL.
		if (empty($video)) {
			return;
		}
		?>
		<!-- The video entry feature container. -->
		<div class="entry-post-feature post-video tm-popup-video type-poster togo-animation-zoom-in">
			<!-- The video link. -->
			<a href="<?php echo esc_url($video); ?>" class="video-link togo-box link-secret">
				<!-- The video poster container. -->
				<div class="video-poster">
					<!-- The video thumbnail. -->
					<div class="togo-image">
						<?php if (has_post_thumbnail()) { ?>
							<?php Togo_Image::the_post_thumbnail(['size' => $size,]); ?>
						<?php } ?>
					</div>
					<!-- The video overlay. -->
					<div class="video-overlay"></div>

					<!-- The video play button. -->
					<div class="video-button">
						<div class="video-play video-play-icon">
							<span class="icon"></span>
						</div>
					</div>
				</div>
			</a>
		</div>
	<?php
	}

	/**
	 * Displays the quote entry feature.
	 *
	 * @since 1.0.0
	 */
	private function entry_feature_quote()
	{
		// Get the quote text from the post meta data.
		$text = $this->get_the_post_meta('post_quote_text');

		// Return if there is no quote text.
		if (empty($text)) {
			return;
		}

		// Get the quote name and URL from the post meta data.
		$name = $this->get_the_post_meta('post_quote_name');
		$url  = $this->get_the_post_meta('post_quote_url');

		// Display the quote entry feature.
	?>
		<!-- The quote entry feature container. -->
		<div class="entry-post-feature post-quote">
			<!-- The quote content container. -->
			<div class="post-quote-content">
				<!-- The quote icon. -->
				<span class="quote-icon fas fa-quote-right"></span>
				<!-- The quote text. -->
				<h3 class="post-quote-text"><?php echo esc_html('&ldquo;' . $text . '&rdquo;'); ?></h3>
				<!-- Display the quote name and URL if available. -->
				<?php if (!empty($name)) { ?>
					<?php $name = "- $name"; ?>
					<h6 class="post-quote-name">
						<?php if (!empty($url)) { ?>
							<!-- Display the quote name as a link if there is a URL. -->
							<a href="<?php echo esc_url($url); ?>" target="_blank"><?php echo esc_html($name); ?></a>
						<?php } else { ?>
							<!-- Display the quote name as plain text if there is no URL. -->
							<?php echo esc_html($name); ?>
						<?php } ?>
					</h6>
				<?php } ?>
			</div>
		</div>
	<?php
	}

	/**
	 * Display the link entry feature for the post.
	 *
	 * This function retrieves the link from the post meta data and displays it in an entry feature container.
	 *
	 * @return void
	 */
	private function entry_feature_link()
	{
		// Get the link from the post meta data.
		$link = $this->get_the_post_meta('post_link');

		// Return if there is no link.
		if (empty($link)) {
			return;
		}

		// Display the link entry feature.
	?>
		<!-- The link entry feature container. -->
		<div class="entry-post-feature post-link">
			<!-- The link container. -->
			<a href="<?php echo esc_url($link); ?>" target="_blank"><?php echo esc_html($link); ?></a>
		</div>
<?php
	}
}
