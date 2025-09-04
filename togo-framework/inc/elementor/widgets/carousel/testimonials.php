<?php

/**
 * Elementor widget for displaying the testimonials.
 *
 * @since 1.0.0
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

/**
 * Class Togo_Testimonials_Widget
 *
 * Elementor widget for displaying the testimonials.
 *
 * @since 1.0.0
 */
class Trip_Testimonials_Carousel extends Carousel_Base
{

    /**
     * Get the widget name.
     *
     * @since 1.0.0
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-testimonials-carousel';
    }

    /**
     * Get the widget title.
     *
     * @since 1.0.0
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Modern Testimonials', 'togo-framework');
    }

    /**
     * Get the widget icon.
     *
     * @since 1.0.0
     *
     * @return string The widget icon.
     */
    public function get_icon_part()
    {
        return 'eicon-carousel';
    }

    /**
     * Retrieves the script dependencies for the widget.
     *
     * The script dependencies are the JavaScript files that need to be loaded
     * for the widget to function properly.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array The script dependencies for the widget.
     */
    public function get_script_depends()
    {
        // The script dependencies for the widget.
        // In this case, we are returning an array with a single element, the name
        // of the script dependency.
        return array('togo-widget-carousel', 'togo-widget-testimonials-carousel');
    }

    /**
     * Register the widget controls.
     *
     * @since 1.0.0
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        $this->add_content_style_section();
        parent::register_controls();
    }

    /**
     * Add the content section controls.
     *
     * @since 1.0.0
     */
    protected function add_content_section()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => __('Style', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'style-01' => __('Style 1', 'togo-framework'),
                    'style-02' => __('Style 2', 'togo-framework'),
                ],
                'default' => 'style-01',
            ]
        );

        // Add repeater control for testimonials
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'testimonial_star',
            [
                'label' => __('Star', 'togo-framework'),
                'type' => Controls_Manager::NUMBER,
                'min'     => 0,
                'max'     => 5,
                'step'    => 1,
                'default' => 5,
            ]
        );
        $repeater->add_control(
            'testimonial_heading',
            [
                'label' => __('Testimonial Heading', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Testimonial Heading', 'togo-framework'),
            ]
        );
        $repeater->add_control(
            'testimonial_text',
            [
                'label' => __('Testimonial Text', 'togo-framework'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('This is a testimonial text.', 'togo-framework'),
            ]
        );
        $repeater->add_control(
            'testimonial_author',
            [
                'label' => __('Author', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('John Doe', 'togo-framework'),
            ]
        );
        $repeater->add_control(
            'testimonial_position',
            [
                'label' => __('Position', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('CEO, Company Name', 'togo-framework'),
            ]
        );
        $repeater->add_control(
            'testimonial_image',
            [
                'label' => __('Image', 'togo-framework'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );
        $this->add_control(
            'testimonials',
            [
                'label' => __('Testimonials', 'togo-framework'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'testimonial_text' => __('This is a testimonial text.', 'togo-framework'),
                        'testimonial_author' => __('John Doe', 'togo-framework'),
                    ],
                ],
                'title_field' => '{{{ testimonial_author }}}',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add the content style section controls.
     *
     * @since 1.0.0
     */
    protected function add_content_style_section()
    {
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'slider_padding',
            [
                'label' => __('Padding', 'togo-framework'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .togo-testimonials-carousel .togo-swiper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'slider_margin',
            [
                'label' => __('Margin', 'togo-framework'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .togo-testimonials-carousel .swiper-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'slider_align',
            [
                'label' => __('Alignment', 'togo-framework'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'togo-framework'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'togo-framework'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'togo-framework'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
            ]
        );

        $this->add_control(
            'author_inline',
            [
                'label' => __('Author Inline', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'label' => __('Heading Typography', 'togo-framework'),
                'selector' => '{{WRAPPER}} .togo-testimonials-carousel .togo-testimonial-content h3',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => __('Content Typography', 'togo-framework'),
                'selector' => '{{WRAPPER}} .togo-testimonials-carousel .togo-testimonial-content p',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => __('Name Typography', 'togo-framework'),
                'selector' => '{{WRAPPER}} .togo-testimonials-carousel .togo-testimonial-name',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'position_typography',
                'label' => __('Position Typography', 'togo-framework'),
                'selector' => '{{WRAPPER}} .togo-testimonials-carousel .togo-testimonial-position',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output.
     *
     * @since 1.0.0
     *
     * @return void
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $testimonials = $settings['testimonials'];
        $style = $settings['style'];

        if (empty($testimonials)) {
            return;
        }

        // Get the slider settings.
        // These settings are used to customize the slider behavior.
        $slider_settings = $this->get_slider_settings($settings);

        // Add the slider settings as attributes.
        $this->add_render_attribute('wrapper', 'class', 'togo-testimonials-carousel align-' . $settings['slider_align']);
        if ($settings['author_inline'] == 'yes') {
            $this->add_render_attribute('wrapper', 'class', 'author-inline');
        }
        $this->add_render_attribute('slider', $slider_settings);
        echo '<div ' . $this->get_render_attribute_string('wrapper') . '>';
        echo '<div ' . $this->get_render_attribute_string('slider') . '>';
        echo '<div class="swiper-wrapper">';
        foreach ($testimonials as $testimonial) {
            echo '<div class="swiper-slide">';
            \Togo_Framework\Helper::togo_get_template('loop/widgets/testimonials/' . $style . '.php', array(
                'settings' => $settings,
                'testimonial' => $testimonial,
            ));
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
