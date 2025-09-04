<?php

namespace Togo\Blog;

use Togo\Templates;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Posts
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
        add_action('togo_open_posts_content', array($this, 'open_post_list'), 10);
        add_action('togo_close_posts_content', array($this, 'close_post_list'), 20);
        add_action('togo_close_posts_content', array($this, 'pagination'), 30);
    }

    public function open_post_list()
    {
        $layout = \Togo\Helper::setting('blog_card_layout');
        echo '<div class="togo-posts-wrapper layout-' . $layout . '">';
    }

    public function close_post_list()
    {
        echo '</div>';
    }

    public function pagination()
    {
        echo Templates::pagination();
    }
}
