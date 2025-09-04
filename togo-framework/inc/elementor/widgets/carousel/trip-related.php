<?php

/**
 * Elementor widget for displaying the trip.
 *
 * @since 1.0.0
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Class Togo_Trip_Related_Carousel_Widget
 *
 * Elementor widget for displaying the trip.
 *
 * @since 1.0.0
 */
class Togo_Trip_Related_Carousel_Widget extends Carousel_Base
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
        return 'togo-trip-related-carousel';
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
        return __('Trip Related Carousel', 'togo-framework');
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
            'related_by',
            [
                'label' => __('Related By', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => 'togo_trip_activities',
                'options' => array(
                    'togo_trip_activities' => __('Activity', 'togo-framework'),
                    'togo_trip_destinations' => __('Destination', 'togo-framework'),
                    'togo_trip_types' => __('Type', 'togo-framework'),
                    'togo_trip_durations' => __('Duration', 'togo-framework'),
                    'togo_trip_tod' => __('Time of Day', 'togo-framework'),
                    'togo_trip_languages' => __('Language', 'togo-framework'),
                    'togo_trip_services' => __('Service', 'togo-framework'),
                ),
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'togo-framework'),
                'type' => Controls_Manager::NUMBER,
                'default' => 8,
                'min' => 1,
                'max' => 20,
                'step' => 1,
            ]
        );

        $this->add_control(
            'random_order',
            [
                'label' => __('Random Order', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
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
     * Render the widget output.
     *
     * @since 1.0.0
     *
     * @return void
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if (!is_singular('togo_trip')) {
            return;
        }

        // Do nothing if there are no items.
        $id = get_the_ID();

        $related_by = $settings['related_by'] ?? 'togo_trip_activities';
        $posts_per_page = $settings['posts_per_page'] ?? 8;

        $terms = wp_get_post_terms($id, $related_by, array('fields' => 'ids'));

        if (empty($terms) || is_wp_error($terms)) {
            // If there are no terms or an error occurred, we cannot fetch related posts.
            return;
        }

        // If random order is enabled, we will modify the query to fetch posts in random order.
        if ('yes' === $settings['random_order']) {
            $orderby = 'rand';
        } else {
            $orderby = 'date';
        }

        $related_posts = new \WP_Query(array(
            'post_type' => 'togo_trip',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page,
            'post__not_in' => array($id),
            'orderby' => $orderby,
            'order' => 'DESC',
            'ignore_sticky_posts' => true,
            'tax_query' => array(
                array(
                    'taxonomy' => $related_by,
                    'field'    => 'term_id',
                    'terms'    => $terms,
                ),
            ),
        ));

        // Get the slider settings.
        // These settings are used to customize the slider behavior.
        $slider_settings = $this->get_slider_settings($settings);

        // Add the slider settings as attributes.
        $this->add_render_attribute('slider', $slider_settings);

        // Start the slider wrapper container.
?>
        <div class="trip-swiper-wrapper">
            <?php if ($related_posts->have_posts()) { ?>
                <div <?php echo $this->get_render_attribute_string('slider'); ?>>
                    <div class="swiper-wrapper">
                        <?php
                        while ($related_posts->have_posts()) {
                            $related_posts->the_post();
                            echo '<div class="swiper-slide">';
                            \Togo_Framework\Helper::togo_get_template('content/trip/trip-grid-' . $settings['layout'] . '.php', array('trip_id' => get_the_ID(), 'image_size' => $settings['image_size']));
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
                <?php echo \Togo_Framework\Template::render_itinerary_popup();
                ?>
            <?php } else { ?>
                <div class="no-trips-found">
                    <?php esc_html_e('No related trips found.', 'togo-framework'); ?>
                </div>
            <?php } ?>
        </div>
<?php
    }
}
