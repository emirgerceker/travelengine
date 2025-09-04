<?php

namespace Togo\Blog;

use Togo\Helper;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Post_Loop
{
    protected static $instance = null;

    public static function instance()
    {
        if (
            is_null(self::$instance)
        ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        add_action('togo_open_post_loop_content', array($this, 'togo_open_blog_card'), 10);
        add_action('togo_open_post_loop_content', array($this, 'togo_blog_card_open_inner'), 20);
        add_action('togo_open_post_loop_content', array($this, 'togo_blog_card_thumbnail'), 30);
        add_action('togo_open_post_loop_content', array($this, 'togo_blog_card_open_content'), 40);
        add_action('togo_open_post_loop_content', array($this, 'togo_blog_open_card_meta'), 50);
        add_action('togo_open_post_loop_content', array($this, 'togo_blog_card_category'), 60);
        add_action('togo_open_post_loop_content', array($this, 'togo_blog_close_card_meta'), 70);
        add_action('togo_open_post_loop_content', array($this, 'togo_blog_card_title'), 80);
        add_action('togo_open_post_loop_content', array($this, 'togo_blog_card_excerpt'), 90);
        add_action('togo_open_post_loop_content', array($this, 'togo_blog_card_button'), 100);
        add_action('togo_open_post_loop_content', array($this, 'togo_blog_card_close_content'), 110);
        add_action('togo_open_post_loop_content', array($this, 'togo_blog_card_close_inner'), 120);
        add_action('togo_open_post_loop_content', array($this, 'togo_close_blog_card'), 130);
    }

    public static function togo_open_blog_card()
    {
        // Open the blog card article element.
        echo '<article id="post-' . get_the_ID() . '" class="' . implode(' ', get_post_class()) . '">';
    }

    /**
     * Open the inner post wrap of the blog post card.
     *
     * This function generates the HTML markup to open the inner post wrap of the blog post card.
     *
     * @since 1.0.0
     */
    public static function togo_blog_card_open_inner()
    {
        echo '<!-- Open the inner post wrap of the blog post card. -->';
        echo '<div class="inner-post-wrap">';
    }

    /**
     * Output the thumbnail of the blog post card.
     *
     * This function generates the HTML markup for the thumbnail of the blog post card.
     *
     * @since 1.0.0
     */
    public static function togo_blog_card_thumbnail()
    {
        // Get the ID of the thumbnail attachment.
        $attach_id = get_post_thumbnail_id(get_the_ID());

        // Resize the thumbnail image.
        $thumb_url = Helper::togo_image_resize($attach_id, '400x280');

        // Check if there is a thumbnail image.
        if (has_post_thumbnail()) :
?>
            <!-- Output the thumbnail of the blog post card. -->
            <div class="post-thumbnail togo-image">
                <!-- Output the link to the post. -->
                <a href="<?php echo esc_url(get_permalink()); ?>">
                    <!-- Output the thumbnail image. -->
                    <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                </a>
            </div>
<?php
        endif;
    }

    /**
     * Open the content section of the blog post card.
     *
     * This function generates the HTML markup for opening the content
     * section of the blog post card.
     *
     * @since 1.0.0
     */
    public static function togo_blog_card_open_content()
    {
        echo '<!-- Open the content section of the blog post card -->';
        echo '<div class="post-detail">';
        echo '<!-- Start of the content section of the blog post card -->';
    }

    /**
     * Open the meta section of the blog post card.
     *
     * This function generates the HTML markup for the meta of the blog
     * post card. It includes the category.
     *
     * @since 1.0.0
     */
    public static function togo_blog_open_card_meta()
    {
        // Open the meta section.
        echo '<div class="post-meta">';
    }

    /**
     * Render the category of the blog post card.
     *
     * This function generates the HTML markup for the category of the blog
     * post card. If the category is empty, this function does nothing.
     *
     * @since 1.0.0
     */
    public static function togo_blog_card_category()
    {
        // If the category is empty, return early.
        if (get_the_category_list() == '') {
            return;
        }

        echo '<div class="post-cate">';

        // Echo the category list.
        echo get_the_category_list();

        echo '</div>';
    }

    /**
     * Close the meta section of the blog post card.
     *
     * This function generates the HTML markup for closing the meta section
     * of the blog post card.
     *
     * @since 1.0.0
     */
    public static function togo_blog_close_card_meta()
    {
        // Close the meta section of the blog post card.
        echo '</div>';
    }

    /**
     * Render the title of the blog post card.
     *
     * This function generates the HTML markup for the title of the blog
     * post card. It includes the title and the "Featured" label if the post
     * is sticky.
     *
     * @since 1.0.0
     */
    public static function togo_blog_card_title()
    {
        // Open the post title container.
        echo '<div class="post-title">';

        // Open the post title element.
        echo '<h3 class="entry-title">';

        // Output the title link.
        echo '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">';
        the_title();
        echo '</a>';

        // Check if the post is sticky and output the "Featured" label.
        if (is_sticky()) {
            echo '<span class="featured-label">' . esc_html('Featured', 'togo') . '</span>';
        }

        // Close the post title element.
        echo '</h3>';

        // Close the post title container.
        echo '</div>';
    }

    /**
     * Render the excerpt of the blog post card.
     *
     * This function generates the HTML markup for the excerpt of the blog
     * post card. If the excerpt is empty, this function does nothing.
     *
     * @since 1.0.0
     */
    public static function togo_blog_card_excerpt()
    {
        // If the excerpt is empty, return early.
        if (get_the_excerpt() == '') {
            return;
        }

        echo '<div class="post-excerpt">';

        // Trim the excerpt to 25 words and add ellipsis if necessary.
        echo wp_trim_words(get_the_excerpt(), 12, '...');

        echo '</div>';
    }

    /**
     * Render the "Read More" button for the blog post card.
     *
     * This function generates the HTML markup for the "Read More" button
     * that appears in the blog post card. It includes the link to the
     * post's permalink and the text "Read More".
     *
     * @since 1.0.0
     */
    public static function togo_blog_card_button()
    {
        // Start the button container
        echo '<div class="btn-readmore">';

        // Generate the link to the post's permalink
        echo '<a href="' . esc_url(get_permalink()) . '">';

        // Display the "Read More" text
        echo esc_html__('Read More', 'togo');

        // Close the link
        echo '</a>';

        // Close the button container
        echo '</div>';
    }

    /**
     * Close the content section of the blog post card.
     *
     * This function generates the HTML markup for closing the content
     * section of the blog post card.
     *
     * @since 1.0.0
     */
    public static function togo_blog_card_close_content()
    {
        // Close the content section of the blog post card.
        echo '</div>';
    }

    /**
     * Close the inner post wrap of the blog post card.
     *
     * This function generates the HTML markup to close the inner post wrap of the blog post card.
     *
     * @since 1.0.0
     */
    public static function togo_blog_card_close_inner()
    {
        echo '</div>'; // Close the inner post wrap.
    }

    /**
     * Close the blog card article element.
     *
     * This function generates the HTML markup to close the blog card article element.
     *
     * @since 1.0.0
     */
    public static function togo_close_blog_card()
    {
        echo '</article>';
    }
}
