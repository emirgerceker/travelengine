<?php

namespace Togo\Blog;

use Togo\Helper;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Post
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
        $single_post_style = Helper::setting('single_post_style');
        if ($single_post_style === '01') {
            add_action('togo_open_single_post_wrapper', array($this, 'entry_post_thumbnail'), 10);
        } else {
            add_action('togo_open_post_content', array($this, 'entry_post_thumbnail'), 40);
        }
        add_action('togo_open_post_content', array($this, 'open_article'), 10);
        add_action('togo_open_post_content', array($this, 'entry_post_meta'), 20);
        add_action('togo_open_post_content', array($this, 'entry_post_title'), 30);
        add_action('togo_open_post_content', array($this, 'open_entry_content'), 50);
        add_action('togo_close_post_content', array($this, 'close_entry_content'), 60);
        add_action('togo_close_post_content', array($this, 'entry_footer_post'), 70);
        add_action('togo_close_post_content', array($this, 'close_article'), 80);
        add_action('togo_close_post_content', array($this, 'entry_post_relared'), 90);
        add_action('togo_close_post_content', array($this, 'entry_post_comment'), 100);
    }

    public static function open_article()
    {
        echo '<article id="post-' . get_the_ID() . '" class="' . implode(' ', get_post_class()) . '">';
    }

    public static function entry_post_meta()
    {
        $post_id = get_the_ID();
        $categories = get_the_category($post_id);
        $author_id = get_post_field('post_author', $post_id);
        $author_name = get_the_author_meta('display_name', $author_id);
        $post_date = get_the_date('M d Y', $post_id);
        if (!empty($categories) && !empty($author_name) && !empty($post_date)) {
            echo '<div class="entry-meta"><a class="entry-category" href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a><span class="entry-author">' . esc_html__('By', 'togo') . ' ' . esc_html($author_name) . '</span><span class="entry-date">' . esc_html($post_date) . '</span></div>';
        }
    }

    public static function entry_post_title()
    {
        the_title('<h1 class="entry-title">', '</h1>');
    }

    public static function entry_post_thumbnail()
    {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }

        $post_thumbnail_id = get_post_thumbnail_id(get_the_ID());

        if (is_singular()) : ?>

            <div class="entry-thumbnail">
                <?php echo wp_get_attachment_image($post_thumbnail_id, 'full'); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="entry-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php echo wp_get_attachment_image($post_thumbnail_id, 'full'); ?>
            </a>

<?php
        endif;
    }

    public static function open_entry_content()
    {
        echo '<div class="entry-content">';
    }

    public static function close_entry_content()
    {
        echo '</div>';
    }

    public static function entry_footer_post()
    {
        echo '<div class="entry-footer">';
        $tags = get_the_tags();
        if (!empty($tags)) {
            echo '<div class="entry-tags">';
            echo '<span class="entry-tags-title">' . esc_html__('Tags:', 'togo') . '</span>';
            foreach ($tags as $tag) {
                echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
            }
            echo '</div>';
        }
        Helper::post_share();
        echo '</div>';
    }

    public static function close_article()
    {
        echo '</article>';
    }

    public static function entry_post_relared()
    {

        $post_id = get_the_ID();
        $related_query = new \WP_Query(array(
            'posts_per_page' => 3,
            'category__in' => wp_get_post_categories($post_id),
            'orderby' => 'rand',
            'post__not_in' => array($post_id),
            'ignore_sticky_posts' => 1,
        ));
        if ($related_query->have_posts() && $related_query->found_posts > 0) {
            echo '<div class="post-relared">';
            echo '<h3 class="post-relared-title">' . esc_html__('Related Articles', 'togo') . '</h3>';
            echo '<div class="post-relared-list">';
            while ($related_query->have_posts()) : $related_query->the_post();
                get_template_part('templates/content');
            endwhile;
            wp_reset_postdata();
            echo '</div>';
            echo '</div>';
        }
    }

    public static function entry_post_comment()
    {
        // If comments are open or we have at least one comment, load up the comment template.
        if ((comments_open() || get_comments_number())) {
            comments_template();
        }
    }
}
