<?php

/**
 * Elementor widget for displaying the activities.
 *
 * @since 1.0.0
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

defined('ABSPATH') || exit;

/**
 * Class Togo_Activities_Carousel_Widget
 *
 * Elementor widget for displaying the trip.
 *
 * @since 1.0.0
 */
class Togo_Activities_Carousel_Widget extends Carousel_Base
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
        return 'togo-activities-carousel';
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
        return __('Activities Carousel', 'togo-framework');
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
        return 'eicon-slider-3d';
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
        return array('togo-widget-carousel', 'togo-widget-activities-carousel');
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
            'layout',
            [
                'label' => __('Layout', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'layout-01' => __('Style 1', 'togo-framework'),
                    'layout-02' => __('Style 2', 'togo-framework'),
                ],
                'default' => 'layout-01',
            ]
        );

        // Repeater for activities
        $repeater = new Repeater();
        $repeater->add_control(
            'activity',
            [
                'label' => __('Activity', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => \Togo_Framework\Helper::get_all_terms_by_taxonomy('togo_trip_activities', true),
                'default' => '',
            ]
        );
        $repeater->add_control(
            'thumbnail',
            [
                'label' => __('Thumbnail', 'togo-framework'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => __('Icon', 'togo-framework'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'activities_list',
            [
                'label' => __('Activities', 'togo-framework'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'full',
                'separator' => 'none',
            ]
        );

        $this->end_controls_section();
    }

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
         * Add a responsive control for the slider's padding.
         *
         * This control allows the user to set the padding of the slider.
         */
        $this->add_responsive_control('slider_padding', [
            'label'      => esc_html__('Padding', 'togo'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .activities-swiper-wrapper .togo-swiper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        /**
         * End the content style section.
         *
         * This section is for styling the content of the widget.
         */
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
        // Do nothing if there are no items.
        if (empty($settings['activities_list']) || count($settings['activities_list']) <= 0) {
            return;
        }

        // Get the slider settings.
        // These settings are used to customize the slider behavior.
        $slider_settings = $this->get_slider_settings($settings);

        // Add the slider settings as attributes.
        $this->add_render_attribute('slider', $slider_settings);

        // Start the slider wrapper container.
?>
        <div class="activities-swiper-wrapper <?php echo $settings['layout']; ?>">
            <div <?php echo $this->get_render_attribute_string('slider'); ?>>
                <div class="swiper-wrapper">
                    <?php
                    foreach ($settings['activities_list'] as $activity) {
                        echo '<div class="swiper-slide">';
                        \Togo_Framework\Helper::togo_get_template('loop/widgets/activities-carousel/' . $settings['layout'] . '.php', array(
                            'activity' => $activity,
                            'settings' => $settings
                        ));
                        echo '</div>';
                    } ?>
                </div>
            </div>
        </div>
<?php
    }
}
