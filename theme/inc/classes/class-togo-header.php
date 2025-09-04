<?php

namespace Togo;

use Togo\Helper;
use Togo\Theme;
use Togo\Templates;
use \Elementor\Plugin;

defined('ABSPATH') || exit;

class Header
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
        add_action('togo_render_header', [$this, 'header_html']);
    }

    public function header_html()
    {
        $header_type = Theme::get_header_type();
        $header_classes = Helper::header_class('header-' . $header_type);

        echo '<header id="header" class="' . esc_attr($header_classes) . '">';

        do_action('togo_render_top_bar');

        if (defined('ELEMENTOR_VERSION') && Plugin::$instance->db->is_built_with_elementor($header_type)) {
            echo Plugin::$instance->frontend->get_builder_content($header_type);
        } else {
            $header = get_post($header_type);
            if ($header && $header_type) {
                $header_content = $header->post_content;
                echo wp_kses_post($header_content);
            } else {
                echo '<div class="container-fluid">';
                echo '<div class="header-main">';
                echo Templates::canvas_menu();
                echo Templates::site_logo();
                echo Templates::main_menu();
                echo '</div>';
                echo '</div>';
            }
        }

        echo '</header>';
    }
}
