<?php

namespace Togo;

use Togo\Helper;
use Togo\Theme;
use Togo\Templates;
use \Elementor\Plugin;

defined('ABSPATH') || exit;

class Top_Bar
{

    protected static $instance  = null;

    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        add_action('togo_render_top_bar', [$this, 'top_bar_html']);
    }

    public function top_bar_html()
    {
        $top_bar_type = Theme::get_topbar_type();
        echo '<div id="top_bar" class="top-bar">';

        if (defined('ELEMENTOR_VERSION') && Plugin::$instance->db->is_built_with_elementor($top_bar_type)) {
            echo Plugin::$instance->frontend->get_builder_content($top_bar_type);
        } else {
            $top_bar = get_post($top_bar_type);
            if ($top_bar && $top_bar_type) {
                $top_bar_content = $top_bar->post_content;
                echo wp_kses_post($top_bar_content);
            }
        }

        echo '</div>';
    }
}
