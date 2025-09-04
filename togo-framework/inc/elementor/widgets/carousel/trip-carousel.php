<?php

/**
 * Elementor widget for displaying the trip.
 *
 * @since 1.0.0
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

/**
 * Class Togo_Trip_Carousel_Widget
 *
 * Elementor widget for displaying the trip.
 *
 * @since 1.0.0
 */
class Togo_Trip_Carousel_Widget extends Carousel_Base
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
        return 'togo-trip-carousel';
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
        return __('Trip Carousel', 'togo-framework');
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
        return array('togo-el-google-maps', 'togo-widget-carousel');
    }

    /**
     * Register the widget controls.
     *
     * @since 1.0.0
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        $this->add_content_items_section();
        $this->add_content_items_style_section();
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

        $args = array(
            'post_type' => 'togo_trip',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );

        $query = get_posts($args);
        $trip_list = array();

        if ($query) {
            foreach ($query as $trip) {
                $trip_id = $trip->ID;
                $trip_title = $trip->post_title;
                $trip_list[$trip_id] = $trip_title;
            }
        }

        $this->add_control(
            'trips',
            [
                'label' => __('Select Trips', 'togo-framework'),
                'type' => Controls_Manager::SELECT2,
                'options' => $trip_list,
                'multiple' => true,
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add the content items section controls.
     *
     * @since 1.0.0
     */
    protected function add_content_items_section()
    {
        $this->start_controls_section(
            'content_items_section',
            [
                'label' => __('Items', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => '01',
                'options' => [
                    '01' => __('01', 'togo-framework'),
                    '02' => __('02', 'togo-framework'),
                    '03' => __('03', 'togo-framework'),
                    '04' => __('04', 'togo-framework'),
                    '05' => __('05', 'togo-framework'),
                ],
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label' => __('Image Size', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => '600x450',
                'description' => __('Enter the image size in the format width x height (e.g., 600x450).', 'togo-framework'),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add the content items style section controls.
     *
     * @since 1.0.0
     */
    protected function add_content_items_style_section()
    {
        $this->start_controls_section(
            'content_items_style_section',
            [
                'label' => __('Items Style', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Title Typography', 'togo-framework'),
                'selector' => '{{WRAPPER}} .trip-title a',
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
        // Do nothing if there are no items.
        if (empty($settings['trips']) || count($settings['trips']) <= 0) {
            return;
        }

        // Get the slider settings.
        // These settings are used to customize the slider behavior.
        $slider_settings = $this->get_slider_settings($settings);

        // Add the slider settings as attributes.
        $this->add_render_attribute('slider', $slider_settings);

        // Start the slider wrapper container.
?>
        <div class="trip-swiper-wrapper">
            <div <?php echo $this->get_render_attribute_string('slider'); ?>>
                <div class="swiper-wrapper">
                    <?php
                    foreach ($settings['trips'] as $trip) {
                        echo '<div class="swiper-slide">';
                        \Togo_Framework\Helper::togo_get_template('content/trip/trip-grid-' . $settings['layout'] . '.php', array('trip_id' => $trip, 'image_size' => $settings['image_size']));
                        echo '</div>';
                    } ?>
                </div>
            </div>
            <?php echo \Togo_Framework\Template::render_itinerary_popup(); ?>
        </div>
<?php
    }
}
