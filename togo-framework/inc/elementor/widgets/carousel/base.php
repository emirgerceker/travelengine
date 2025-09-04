<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Box_Shadow;

defined('ABSPATH') || exit;

abstract class Carousel_Base extends Widget_Base
{

    /**
     * Get the icon part.
     *
     * Retrieve the icon part for the widget.
     *
     * @since 1.0.0
     * @access protected
     *
     * @return string Icon part.
     */
    protected function get_icon_part()
    {
        // The icon part is the class name for the icon.
        // This function should be overridden in the child class.
        //
        // Default value is 'eicon-carousel-loop'.

        return 'eicon-carousel-loop';
    }

    /**
     * Get the icon for the widget.
     *
     * Retrieve the CSS class for the widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string The CSS class for the widget icon.
     */
    public function get_icon()
    {
        // The icon part is the class name for the icon.
        // The 'togo-badge' class is added to all Togo Elementor widgets
        // to provide a consistent styling for their badges.
        //
        // The child class should override the get_icon_part() method
        // to return the specific class name for its icon.

        return 'togo-badge ' . $this->get_icon_part();
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the button widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since  2.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['togo'];
    }

    /**
     * Print the attributes string.
     *
     * Prints the attributes string for the widget.
     *
     * @since 1.0.0
     * @access protected
     *
     * @param array $attr The attributes array.
     * @return void
     */
    protected function print_attributes_string($attr)
    {
        // Print the attributes string.
        // The get_render_attribute_string() method is used to generate the attributes string.
        // The attributes string is then printed using echo.

        echo '' . $this->get_render_attribute_string($attr);
    }

    /**
     * Registers the controls for the widget.
     *
     * This method adds the swiper options section, swiper arrows style section, and swiper dots style section.
     *
     * @return void
     */
    protected function register_controls()
    {
        // Add the swiper options section
        $this->add_swiper_options_section();

        // Add the swiper arrows style section
        $this->add_swiper_arrows_style_section();

        // Add the swiper dots style section
        $this->add_swiper_dots_style_section();
    }

    /**
     * Adds the swiper options section.
     *
     * This function adds the swiper options section to the widget.
     *
     * @return void
     */
    private function add_swiper_options_section()
    {
        // Start the controls section for the swiper options
        $this->start_controls_section(
            'swiper_options_section',
            [
                'label' => esc_html__('Carousel Options', 'togo-framework'),
            ]
        );

        // Add the transition control
        $this->add_control(
            'swiper_effect',
            [
                'label'   => esc_html__('Transition', 'togo-framework'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'slide' => esc_html__('Slide', 'togo-framework'),
                    'fade'  => esc_html__('Fade', 'togo-framework'),
                ],
                'default' => 'slide',
            ]
        );

        // Add the slides per view control
        $this->add_responsive_control(
            'swiper_items',
            [
                'label'          => esc_html__('Slides Per View', 'togo-framework'),
                'type'           => Controls_Manager::SELECT,
                'options'        => [
                    '1'          => '1',
                    '2'          => '2',
                    '3'          => '3',
                    '4'          => '4',
                    '5'          => '5',
                    '6'          => '6',
                ],
                'default'        => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'condition' => [
                    'swiper_effect' => 'slide',
                ],
            ]
        );

        // Add the space between slides control
        $this->add_responsive_control(
            'swiper_gutter',
            [
                'label'   => esc_html__('Space Between', 'togo-framework'),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 0,
                'max'     => 200,
                'step'    => 1,
                'default' => 30,
                'condition'     => [
                    'swiper_effect' => 'slide',
                ],
            ]
        );

        // Add the transition duration control
        $this->add_control(
            'swiper_speed',
            [
                'label'   => esc_html__('Transition Duration', 'togo-framework'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 1000,
            ]
        );

        // Add the autoplay control
        $this->add_control(
            'swiper_autoplay',
            [
                'label'       => esc_html__('Auto Play', 'togo-framework'),
                'description' => esc_html__('Delay between transitions (in ms). For e.g: 3000. Leave blank to disabled. Input 1 to make smooth transition.', 'togo-framework'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '',
            ]
        );

        // Add the infinite loop control
        $this->add_control(
            'swiper_loop',
            [
                'label'   => esc_html__('Infinite Loop', 'togo-framework'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        // Add the centered control
        $this->add_control(
            'swiper_centered',
            [
                'label' => esc_html__('Centered', 'togo-framework'),
                'type'  => Controls_Manager::SWITCHER,
                'condition'     => [
                    'swiper_effect' => 'slide',
                ],
            ]
        );

        // Add the show full right control
        $this->add_control(
            'swiper_show_full_right',
            [
                'label' => esc_html__('Show Full Right', 'togo-framework'),
                'type'  => Controls_Manager::SWITCHER,
                'description' => esc_html__('Only works if loop is disabled', 'togo-framework'),
                'condition'     => [
                    'swiper_effect' => 'slide',
                ],
            ]
        );

        // Add the touchable control
        $this->add_control(
            'swiper_touch',
            [
                'label'       => esc_html__('Touchable', 'togo-framework'),
                'description' => esc_html__('Click and drag to change slides', 'togo-framework'),
                'type'        => Controls_Manager::SWITCHER,
                'default'     => 'yes',
            ]
        );

        // Add the mouse wheel control
        $this->add_control(
            'swiper_mousewheel',
            [
                'label' => esc_html__('Mousewheel', 'togo-framework'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        // Add the navigation heading
        $this->add_control(
            'swiper_navigation_heading',
            [
                'label'     => esc_html__('Navigation', 'togo-framework'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Add the control for showing/hiding the arrows
        $this->add_control(
            'swiper_arrows_show',
            [
                'label' => esc_html__('Arrows', 'togo-framework'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        // Add custom arrows control
        $this->add_control(
            'swiper_custom_arrows',
            [
                'label' => esc_html__('Custom Arrows', 'togo-framework'),
                'type'  => Controls_Manager::SWITCHER,
                'condition' => [
                    'swiper_arrows_show' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'swiper_arrows_next_button_class',
            [
                'label' => esc_html__('Next Button Class', 'togo-framework'),
                'type'  => Controls_Manager::TEXT,
                'label_block' => true,
                'description' => esc_html__('Add custom class to the next arrow. Ex: arrow-next', 'togo-framework'),
                'condition' => [
                    'swiper_custom_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'swiper_arrows_prev_button_class',
            [
                'label' => esc_html__('Previous Button Class', 'togo-framework'),
                'type'  => Controls_Manager::TEXT,
                'label_block' => true,
                'description' => esc_html__('Add custom class to the previous arrow. Ex: arrow-prev', 'togo-framework'),
                'condition' => [
                    'swiper_custom_arrows' => 'yes',
                ]
            ]
        );

        // Add the control for showing/hiding the dots
        $this->add_control(
            'swiper_dots_show',
            [
                'label' => esc_html__('Dots', 'togo-framework'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        // End the controls section
        $this->end_controls_section();
    }


    private function add_swiper_arrows_style_section()
    {
        $this->start_controls_section('swiper_arrows_style_section', [
            'label'     => esc_html__('Carousel Arrows', 'togo-framework'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => [
                'swiper_arrows_show' => 'yes',
            ],
        ]);

        $this->add_responsive_control('swiper_arrows_size', [
            'label'      => esc_html__('Size', 'togo-framework'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => [
                'px' => [
                    'min'  => 10,
                    'max'  => 200,
                    'step' => 1,
                ],
            ],
            'selectors'  => [
                '{{WRAPPER}} .swiper-nav-button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
            ],
        ]);

        $this->start_controls_tabs('swiper_arrows_style_tabs');

        $this->start_controls_tab('swiper_arrows_style_normal_tab', [
            'label' => esc_html__('Normal', 'togo-framework'),
        ]);

        $this->add_control('swiper_arrows_text_color', [
            'label'     => esc_html__('Color', 'togo-framework'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .swiper-nav-button' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('swiper_arrows_background_color', [
            'label'     => esc_html__('Background Color', 'togo-framework'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .swiper-nav-button' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('swiper_arrows_border_color', [
            'label'     => esc_html__('Border Color', 'togo-framework'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .swiper-nav-button' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'swiper_arrows_box_shadow',
            'selector' => '{{WRAPPER}} .swiper-nav-button',
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('swiper_arrows_style_hover_tab', [
            'label' => esc_html__('Hover', 'togo-framework'),
        ]);

        $this->add_control('swiper_arrows_hover_text_color', [
            'label'     => esc_html__('Color', 'togo-framework'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .swiper-nav-button:hover' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('swiper_arrows_hover_background_color', [
            'label'     => esc_html__('Background Color', 'togo-framework'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .swiper-nav-button:hover' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('swiper_arrows_hover_border_color', [
            'label'     => esc_html__('Border Color', 'togo-framework'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .swiper-nav-button:hover' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'swiper_arrows_hover_box_shadow',
            'selector' => '{{WRAPPER}} .swiper-nav-button:hover',
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control('swiper_arrows_border_width', [
            'label'     => esc_html__('Border Width', 'togo-framework'),
            'type'      => Controls_Manager::SLIDER,
            'selectors' => [
                '{{WRAPPER}} .swiper-nav-button' => 'border-width: {{SIZE}}{{UNIT}}',
            ],
            'separator' => 'before',
        ]);

        $this->add_responsive_control('swiper_arrows_border_radius', [
            'label'      => esc_html__('Border Radius', 'togo-framework'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['%', 'px'],
            'range'      => [
                '%'  => [
                    'max'  => 100,
                    'step' => 1,
                ],
                'px' => [
                    'max'  => 200,
                    'step' => 1,
                ],
            ],
            'selectors'  => [
                '{{WRAPPER}} .swiper-nav-button' => 'border-radius: {{SIZE}}{{UNIT}}',
            ],
        ]);

        $this->add_responsive_control('swiper_arrows_next_button_position', [
            'label'     => esc_html__('Next Button Position', 'togo-framework'),
            'type'      => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => [
                'px' => [
                    'min'  => -100,
                    'max'  => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-button-next' => 'right: {{SIZE}}{{UNIT}}',
            ],
            'separator' => 'before',
        ]);

        $this->add_responsive_control('swiper_arrows_prev_button_position', [
            'label'     => esc_html__('Previous Button Position', 'togo-framework'),
            'type'      => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => [
                'px' => [
                    'min'  => -100,
                    'max'  => 100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}}',
            ],
        ]);

        $this->end_controls_section();
    }

    /**
     * Adds a style section for the carousel dots.
     *
     * This function adds a controls section to the Elementor editor for styling
     * the carousel dots. It includes controls for the primary and secondary
     * colors of the dots.
     */
    private function add_swiper_dots_style_section()
    {
        // Start the controls section for the carousel dots
        $this->start_controls_section(
            'swiper_dots_style_section',
            [
                'label'     => esc_html__('Carousel Dots', 'togo-framework'), // Section label
                'tab'       => Controls_Manager::TAB_STYLE, // Section tab
                'condition' => [
                    'swiper_dots_show' => 'yes', // Condition for showing the section
                ],
            ]
        );

        $this->add_control(
            'is_absolute_dots',
            [
                'label'   => esc_html__('Absolute Dots', 'togo-framework'), // Control label
                'type'    => Controls_Manager::SWITCHER, // Control type
                'default' => 'yes', // Default value
            ]
        );

        $this->add_control(
            'spacing_between_dots',
            [
                'label'     => esc_html__('Spacing Between Dots', 'togo-framework'), // Control label
                'type'      => Controls_Manager::SLIDER, // Control type
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-horizontal>.swiper-pagination-bullets .swiper-pagination-bullet,{{WRAPPER}} .swiper-pagination-horizontal.swiper-pagination-bullets .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'swiper_dots_type',
            [
                'label'   => esc_html__('Type', 'togo-framework'), // Control label
                'type'    => Controls_Manager::SELECT, // Control type
                'options' => [
                    'bullets' => esc_html__('Bullets', 'togo-framework'), // Option label
                    'fraction' => esc_html__('Fraction', 'togo-framework'), // Option label
                    'progressbar' => esc_html__('Progress Bar', 'togo-framework'), // Option label
                ],
                'default' => 'bullets', // Default value
            ],
        );

        // Add a control for the primary color of the dots
        $this->add_control(
            'swiper_dots_color',
            [
                'label'     => esc_html__('Color', 'togo-framework'), // Control label
                'type'      => Controls_Manager::COLOR, // Control type
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet'   => 'background-color: {{VALUE}};', // CSS selector and style
                ],
            ]
        );

        // End the controls section for the carousel dots
        $this->end_controls_section();
    }

    /**
     * Retrieves the settings for the slider.
     *
     * @param array $settings The settings for the slider.
     * @return array The slider settings.
     */
    protected function get_slider_settings(array $settings)
    {
        // Initialize the slider settings array
        $slider_settings = [
            'class'          => ['togo-swiper', 'togo-swiper-widget'], // Classes for the slider
            'data-lg-items'  => $settings['swiper_items'], // Number of items displayed on large screens
            'data-md-items'  => isset($settings['swiper_items_tablet']) && !empty($settings['swiper_items_tablet']) ? $settings['swiper_items_tablet'] : '2', // Number of items displayed on medium screens
            'data-sm-items'  => isset($settings['swiper_items_mobile']) && !empty($settings['swiper_items_mobile']) ? $settings['swiper_items_mobile'] : '1', // Number of items displayed on small screens
            'data-lg-gutter' => $settings['swiper_gutter'], // Gutter size on large screens
            'data-md-gutter' => isset($settings['swiper_gutter_tablet']) && !empty($settings['swiper_gutter_tablet']) ? $settings['swiper_gutter_tablet'] : '30', // Gutter size on medium screens
            'data-sm-gutter' => isset($settings['swiper_gutter_mobile']) && !empty($settings['swiper_gutter_mobile']) ? $settings['swiper_gutter_mobile'] : '15', // Gutter size on small screens
        ];

        // Add navigation settings if navigation is enabled
        if (!empty($settings['swiper_arrows_show'])) {
            $slider_settings['data-nav']            = '1'; // Enable navigation
        }

        if (!empty($settings['swiper_custom_arrows'])) {
            $slider_settings['data-nav'] = '0';
            $slider_settings['data-custom-nav'] = '1';
            $slider_settings['data-next-button-class'] = $settings['swiper_arrows_next_button_class'] ? $settings['swiper_arrows_next_button_class'] : '';
            $slider_settings['data-prev-button-class'] = $settings['swiper_arrows_prev_button_class'] ? $settings['swiper_arrows_prev_button_class'] : '';
        }

        // Add pagination settings if pagination is enabled
        if (!empty($settings['swiper_dots_show'])) {
            $slider_settings['data-pagination'] = '1'; // Enable pagination
        }

        if (!empty($settings['is_absolute_dots'])) {
            $slider_settings['class'][] = 'pagination-absolute';
        }

        // Add pagination type settings if pagination type is set
        if (!empty($settings['swiper_dots_type'])) {
            $slider_settings['data-pagination-type'] = $settings['swiper_dots_type']; // Set pagination type
        }

        // Add loop settings if loop is enabled
        if (!empty($settings['swiper_loop']) && 'yes' === $settings['swiper_loop']) {
            $slider_settings['data-loop'] = '1'; // Enable loop
        }

        // Add centered mode settings if centered mode is enabled
        if (!empty($settings['swiper_centered']) && 'yes' === $settings['swiper_centered']) {
            $slider_settings['data-centered'] = '1'; // Enable centered mode
        }

        // Add mousewheel settings if mousewheel is enabled
        if (!empty($settings['swiper_mousewheel']) && 'yes' === $settings['swiper_mousewheel']) {
            $slider_settings['data-mousewheel'] = '1'; // Enable mousewheel control
        }

        // Add touch simulation settings if touch simulation is enabled
        if (!empty($settings['swiper_touch']) && 'yes' === $settings['swiper_touch']) {
            $slider_settings['data-simulate-touch'] = '1'; // Enable touch simulation
        }

        // Add class settings if show full right is enabled
        if (!empty($settings['swiper_show_full_right']) && 'yes' === $settings['swiper_show_full_right']) {
            $slider_settings['class'][] = 'show-full-right'; // Enable show full right
        }

        // Add transition speed settings if transition speed is set
        if (!empty($settings['swiper_speed'])) {
            $slider_settings['data-speed'] = $settings['swiper_speed']; // Set transition speed
        }

        // Add autoplay settings if autoplay is set
        if (!empty($settings['swiper_autoplay'])) {
            $slider_settings['data-autoplay'] = $settings['swiper_autoplay']; // Set autoplay delay
        }

        // Add transition effect settings if transition effect is set
        if (!empty($settings['swiper_effect'])) {
            $slider_settings['data-effect'] = $settings['swiper_effect']; // Set transition effect
        }

        return $slider_settings;
    }

    /**
     * Prints the slider HTML.
     *
     * @param array|null $settings The settings for the slider. Defaults to the active settings.
     * @return void
     */
    protected function print_slider(array $settings = null)
    {
        // If no settings are provided, use the active settings.
        if (null === $settings) {
            $settings = $this->get_active_settings();
        }

        // Get the slider settings.
        $slider_settings = $this->get_slider_settings($settings);

        // Add the slider settings as attributes.
        $this->add_render_attribute(self::SLIDER_KEY, $slider_settings);

        // Start the slider container.
?>

        <div <?php $this->print_attributes_string('slider'); ?>>
            <div class="swiper-inner">

                <!-- Start the swiper container. -->
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php $this->print_slides($settings); ?>
                    </div>
                </div>

            </div>
        </div>
<?php
    }

    /**
     * Renders the widget by printing the slider HTML.
     *
     * This function retrieves the settings for the widget and calls the
     * `print_slider` method to display the slider HTML.
     *
     * @return void
     */
    protected function render()
    {
        // Get the settings for the widget.
        $settings = $this->get_settings_for_display();

        // Print the slider HTML using the settings.
        $this->print_slider($settings);
    }

    /**
     * Retrieves all categories along with their names and IDs.
     *
     * @return array An associative array containing category IDs as keys and category names as values.
     */
    protected function get_all_categories()
    {
        // Get all categories
        $categories = get_categories(array(
            'orderby' => 'name', // Order categories by name in ascending order.
            'order' => 'ASC',
            'hide_empty' => false, // Set to true to hide categories without posts
        ));

        // Check if any categories were found
        if (empty($categories)) {
            return array(); // Return an empty array if no categories are found.
        }

        // Initialize an empty array to hold the category data
        $category_data = array();

        // Loop through each category and add their data to the array
        foreach ($categories as $category) {
            $category_data[$category->term_id] = $category->name; // Add the category ID as the key and the category name as the value.
        }

        return $category_data; // Return the array containing category IDs and names.
    }

    /**
     * Retrieves all tags along with their names and IDs.
     *
     * @return array An associative array containing tag IDs as keys and tag names as values.
     */
    protected function get_all_tags()
    {
        // Get all tags
        $tags = get_tags(array(
            'orderby' => 'name', // Order tags by name in ascending order.
            'order' => 'ASC',
            'hide_empty' => false, // Set to true to hide tags without posts
        ));

        // Check if any tags were found
        if (empty($tags)) {
            return array(); // Return an empty array if no tags are found.
        }

        // Initialize an empty array to hold the tag data
        $tag_data = array();

        // Loop through each tag and add their data to the array
        foreach ($tags as $tag) {
            $tag_data[$tag->term_id] = $tag->name; // Add the tag ID as the key and the tag name as the value.
        }

        return $tag_data; // Return the array containing tag IDs and names.
    }
}
