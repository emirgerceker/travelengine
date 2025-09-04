<?php

/**
 * Elementor widget for displaying the modern carousel.
 *
 * @since 1.0.0
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;

defined('ABSPATH') || exit;

/**
 * Class Togo_Modern_Carousel_Widget
 *
 * Elementor widget for displaying the modern carousel.
 *
 * @since 1.0.0
 */
class Togo_Modern_Carousel_Widget extends Carousel_Base
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
        return 'togo-modern-carousel';
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
        return __('Modern Carousel', 'togo-framework');
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
        return array('togo-widget-carousel');
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

        // Add repeater control for testimonials
        $repeater = new Repeater();
        $repeater->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Subtitle', 'togo-framework'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'heading',
            [
                'label' => __('Heading', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Heading', 'togo-framework'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'description',
            [
                'label' => __('Description', 'togo-framework'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __('Description', 'togo-framework'),
                'label_block' => true,
            ],
        );
        $repeater->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Button Text', 'togo-framework'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'button_link',
            [
                'label' => __('Button Link', 'togo-framework'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'image',
            [
                'label' => __('Image', 'togo-framework'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'items',
            [
                'label' => __('Items', 'togo-framework'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'subtitle' => __('Subtitle', 'togo-framework'),
                        'heading' => __('Heading', 'togo-framework'),
                        'description' => __('Description', 'togo-framework'),
                        'button_text' => __('Button Text', 'togo-framework'),
                        'image' => '',
                    ],
                ],
                'title_field' => '{{{ heading }}}',
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image',
                'default' => 'full',
                'separator' => 'none',
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

        $this->add_control(
            'subtitle_heading',
            [
                'label' => __('Subtitle', 'togo-framework'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-modern-carousel .modern-carousel-item-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .togo-modern-carousel .modern-carousel-item-subtitle',
            ]
        );

        $this->add_control(
            'title_heading',
            [
                'label' => __('Title', 'togo-framework'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-modern-carousel .modern-carousel-item-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .togo-modern-carousel .modern-carousel-item-title',
            ]
        );

        $this->add_control(
            'description_heading',
            [
                'label' => __('Description', 'togo-framework'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-modern-carousel .modern-carousel-item-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .togo-modern-carousel .modern-carousel-item-text',
            ]
        );

        $this->add_control(
            'button_heading',
            [
                'label' => __('Button', 'togo-framework'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('button_skin_tabs');

        $this->start_controls_tab(
            'button_normal',
            [
                'label' => __('Normal', 'togo-framework'),
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __('Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-modern-carousel .togo-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => __('Background Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-modern-carousel .togo-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_color',
            [
                'label' => __('Border Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-modern-carousel .togo-button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_hover',
            [
                'label' => __('Hover', 'togo-framework'),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __('Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-modern-carousel .togo-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background_color',
            [
                'label' => __('Background Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-modern-carousel .togo-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __('Border Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-modern-carousel .togo-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

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
        $items = $settings['items'];

        if (empty($items)) {
            return;
        }

        // Get the slider settings.
        // These settings are used to customize the slider behavior.
        $slider_settings = $this->get_slider_settings($settings);

        // Add the slider settings as attributes.
        $this->add_render_attribute('wrapper', 'class', 'togo-modern-carousel');
        $this->add_render_attribute('slider', $slider_settings);
        echo '<div ' . $this->get_render_attribute_string('wrapper') . '>';
        echo '<div ' . $this->get_render_attribute_string('slider') . '>';
        echo '<div class="swiper-wrapper">';
        foreach ($items as $item) {
            $slide_id = $item['_id'];
            $item_key = 'item_' . $slide_id;
            $this->add_render_attribute($item_key, 'class', [
                'swiper-slide',
                'elementor-repeater-item-' . $slide_id,
            ]);
            echo '<div ' . $this->get_render_attribute_string($item_key) . '>';
            \Togo_Framework\Helper::togo_get_template('loop/widgets/modern-carousel/layout-01.php', array(
                'settings' => $settings,
                'item' => $item,
            ));
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
