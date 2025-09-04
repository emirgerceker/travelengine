<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;
/**
 * Class Togo_Mini_Cart_Widget
 *
 * This class is a part of Elementor Pro plugin and is responsible for rendering
 * the mini cart widget.
 *
 * @package Togo_Elementor
 */
class Togo_Mini_Cart_Widget extends Base
{

    /**
     * Get the name of the widget.
     *
     * @return string
     */
    public function get_name()
    {
        return 'togo-mini-cart';
    }

    /**
     * Get the title of the widget.
     *
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Mini Cart', 'elementor-pro');
    }

    /**
     * Get the icon of the widget.
     *
     * @return string
     */
    public function get_icon_part()
    {
        return 'eicon-cart';
    }

    /**
     * Get the script dependencies of the widget.
     *
     * @return array
     */
    public function get_script_depends()
    {
        return array('togo-widget-minicart');
    }

    /**
     * Register the controls of the widget.
     */
    protected function register_controls()
    {
        $this->add_content_section();
        $this->add_content_style_section();
    }

    protected function add_content_section()
    {
        /**
         * Section: Menu Icon Content
         */
        $this->start_controls_section(
            'section_menu_icon_content',
            [
                'label' => esc_html__('Menu Icon', 'elementor-pro'),
            ]
        );

        /**
         * Alignment of the menu icon.
         */
        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Position', 'togo-framework'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Left', 'togo-framework'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => esc_html__('Right', 'togo-framework'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-lightbox-inner' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'start' => 'transform: translateX(-100%); left: 0; right: auto;',
                    'end' => 'transform: translateX(100%); left: auto; right: 0;',
                ],
                'default' => 'start',
            ]
        );

        $this->end_controls_section();
    }

    protected function add_content_style_section()
    {
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-svg-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-svg-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget.
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if (! wp_script_is('wc-cart-fragments')) {
            wp_enqueue_script('wc-cart-fragments');
        }

        self::render_menu_cart($settings);
    }

    /**
     * Render the plain content of the widget.
     */
    public function render_plain_content() {}

    /**
     * Get the group name of the widget.
     *
     * @return string
     */
    public function get_group_name()
    {
        return 'woocommerce';
    }

    /**
     * Render the mini cart.
     *
     * @param array $settings
     */
    public static function render_menu_cart($settings)
    {
        if (null === WC()->cart) {
            return;
        }

        $widget_cart_is_hidden = apply_filters('woocommerce_widget_cart_is_hidden', false);
?>
        <div class="togo-mini-cart">
            <?php if (! $widget_cart_is_hidden) : ?>
                <div class="mini-cart-wrapper">
                    <div class="mini-cart-lightbox" aria-hidden="true">
                        <div class="mini-cart-lightbox-bg"></div>
                        <div class="elementor-menu-cart__main mini-cart-lightbox-inner" aria-hidden="true">
                            <div class="mini-cart-lightbox-top">
                                <?php self::render_menu_cart_heading($settings); ?>
                                <?php self::render_menu_cart_close_button($settings); ?>
                            </div>
                            <div class="widget_shopping_cart_content">
                                <?php
                                woocommerce_mini_cart();
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php self::render_menu_cart_toggle_button($settings); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php
    }

    /**
     * Render the heading of the mini cart.
     *
     * @param array $settings
     */
    public static function render_menu_cart_heading($settings)
    {
    ?>
        <div class="mini-cart-heading">
            <h3 class="mini-cart-heading-title">
                <?php esc_html_e('Your Cart', 'togo'); ?>
            </h3>
        </div>
    <?php
    }

    /**
     * Render the close button of the mini cart.
     *
     * @param array $settings
     */
    public static function render_menu_cart_close_button($settings)
    {
    ?>
        <div class="mini-cart-lightbox-close">
            <?php
            echo \Togo\Icon::get_svg('x');
            ?>
        </div>
    <?php
    }

    /**
     * Render the toggle button of the mini cart.
     *
     * @param array $settings
     */
    public static function render_menu_cart_toggle_button($settings)
    {
        if (null === WC()->cart) {
            return;
        }
        $product_count = WC()->cart->get_cart_contents_count();
    ?>
        <div class="togo-minicart">
            <a href="#" class="togo-minicart-button">
                <span class="togo-minicart-icon">
                    <?php
                    echo \Togo\Icon::get_svg('cart');
                    ?>
                    <?php if ($product_count > 0) : ?>
                        <span class="togo-minicart-icon-qty" data-counter="<?php echo esc_attr($product_count); ?>">
                            <?php echo esc_html($product_count); ?>
                        </span>
                    <?php endif; ?>
                </span>
            </a>
        </div>
<?php
    }
}
