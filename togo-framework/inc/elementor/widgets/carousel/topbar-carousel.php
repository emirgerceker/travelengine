<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Togo_Topbar_Carousel_Widget extends Carousel_Base
{

    /**
     * Retrieves the widget name.
     *
     * @since 1.0.0
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        // Widget name used in the Elementor editor.
        return 'togo-topbar-carousel';
    }

    /**
     * Retrieves the widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        // The title of the widget, displayed in the Elementor editor.
        return __('Topbar Carousel', 'togo-framework');
    }

    /**
     * Retrieves the widget icon.
     *
     * @since 1.0.0
     *
     * @return string The widget icon.
     */
    public function get_icon_part()
    {
        // The icon of the widget, displayed in the Elementor editor.
        return 'eicon-carousel-loop';
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
        return array('togo-widget-carousel', 'togo-widget-topbar-carousel');
    }

    /**
     * Retrieves the categories of the widget.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array The categories of the widget.
     */
    public function get_categories()
    {
        // The categories of the widget, displayed in the Elementor editor.
        return array('togo');
    }

    /**
     * Registers controls for the widget.
     *
     * This protected method is called when the widget is initialized.
     * It registers the controls for the widget.
     *
     * @since 1.0.0
     */
    protected function _register_controls()
    {
        // This method is called when the widget is initialized.
        // It registers the controls for the widget.
        // It is empty in this case, as the controls are registered in the child class.
        $this->add_content_section();
        $this->add_content_style_section();
        parent::register_controls();
    }

    /**
     * Adds the content section to the widget's controls.
     *
     * This method creates a content section with a repeater control for adding
     * multiple items to the widget. The repeater control allows the user to
     * add, remove, and reorder the items.
     *
     * @return void
     */
    protected function add_content_section()
    {
        // Start the content section.
        $this->start_controls_section(
            'content_section',
            [
                // The label for the section.
                'label' => __('Content', 'togo-framework'),
                // The tab on which the section is displayed.
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control('show_close_button', [
            'label' => esc_html__('Show Close Button', 'togo'),
            'type'  => Controls_Manager::SWITCHER,
        ]);

        // Create a new repeater.
        $repeater = new Repeater();

        // Add a control for the content of each item.
        $repeater->add_control(
            'content',
            [
                'label' => __('Content', 'togo-framework'),
                'type' => Controls_Manager::WYSIWYG,
            ]
        );

        // Add the repeater control to the widget's controls.
        $this->add_control(
            'items',
            [
                'label' => esc_html__('Items', 'togo'),
                'type' => Controls_Manager::REPEATER,
                // The fields for the repeater control.
                'fields' => $repeater->get_controls(),
                // The default values for the repeater control.
                'default' => [
                    [
                        'content' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'togo'),
                    ],
                    [
                        'content' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'togo'),
                    ],
                    [
                        'content' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'togo'),
                    ],
                ],
            ]
        );

        // End the content section.
        $this->end_controls_section();
    }

    /**
     * Adds the content style section to the widget.
     *
     * This protected method is called when the widget is initialized.
     * It adds a new section to the widget's controls for styling the content.
     *
     * @return void
     */
    protected function add_content_style_section()
    {

        /**
         * Start the content style section.
         *
         * This section is for styling the content of the widget.
         */
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        /**
         * Add a responsive control for the slider's max width.
         *
         * This control allows the user to set the maximum width of the slider.
         */
        $this->add_responsive_control('slider_max_width', [
            'label'      => esc_html__('Slider Max Width', 'togo'),
            'type'       => Controls_Manager::SLIDER,
            'default'    => [
                'unit' => 'px',
            ],
            'size_units' => ['px', '%'],
            'range'      => [
                '%'  => [
                    'min' => 1,
                    'max' => 100,
                ],
                'px' => [
                    'min' => 1,
                    'max' => 1600,
                ],
            ],
            'default'    => [
                'unit' => 'px',
                'size' => 650,
            ],
            'selectors'  => [
                '{{WRAPPER}} .togo-swiper-widget' => 'max-width: {{SIZE}}{{UNIT}}; margin: 0 auto;',
            ],
        ]);

        /**
         * Add a responsive control for the slider's padding.
         *
         * This control allows the user to set the padding of the slider.
         */
        $this->add_responsive_control('slider_padding', [
            'label'      => esc_html__('Padding', 'togo'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .topbar-swiper-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        /**
         * Add a control for the content alignment.
         *
         * This control allows the user to set the alignment of the content.
         */
        $this->add_control(
            'content_align',
            [
                'label' => __('Content Alignment', 'togo-framework'),
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
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        /**
         * Add a control for the background color of the slider.
         *
         * This control allows the user to set the background color of the slider.
         */
        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .topbar-swiper-wrapper' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        /**
         * Add a control for the content color of the slider.
         *
         * This control allows the user to set the content color of the slider.
         */
        $this->add_control(
            'content_color',
            [
                'label' => __('Content Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'color: {{VALUE}}',
                ],
            ]
        );

        /**
         * Add a group control for the typography of the slider.
         *
         * This control allows the user to set the typography of the slider.
         */
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .swiper-slide',
        ]);

        /**
         * End the content style section.
         *
         * This section is for styling the content of the widget.
         */
        $this->end_controls_section();
    }

    /**
     * Renders the widget by printing the slider HTML.
     *
     * This protected method is called when the widget is initialized.
     * It retrieves the settings for the widget and calls the `print_slider` method
     * to display the slider HTML.
     *
     * @return void
     */
    protected function render()
    {
        // Get the settings for the widget.
        // These settings are used to customize the appearance and behavior of the widget.
        $settings = $this->get_settings_for_display();

        // Do nothing if there are no items.
        if (empty($settings['items']) || count($settings['items']) <= 0) {
            return;
        }

        // Get the slider settings.
        // These settings are used to customize the slider behavior.
        $slider_settings = $this->get_slider_settings($settings);

        // Add the slider settings as attributes.
        $this->add_render_attribute('slider', $slider_settings);

        // Start the slider wrapper container.
?>
        <div class="topbar-swiper-wrapper">
            <?php
            // Check if the close button should be displayed.
            // If it is, display the close button.
            if ($settings['show_close_button']) {
                echo '<div class="togo-swiper-close">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                </svg></div>';
            }
            ?>
            <div <?php $this->print_attributes_string('slider'); ?>>
                <div class="swiper-wrapper">
                    <?php
                    // Loop through each item in the slider.
                    // Display each item's content within a slider slide.
                    foreach ($settings['items'] as $key => $item) {
                        echo '<div class="swiper-slide">' . $item['content'] . '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
<?php
    }
}
