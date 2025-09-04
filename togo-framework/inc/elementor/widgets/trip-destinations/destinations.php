<?php

/**
 * Breadcrumb widget.
 *
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor\Trip_Destinations;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Class Togo_Breadcrumb_Widget.
 *
 * A widget for displaying breadcrumbs.
 *
 * @package Togo_Elementor
 */
class Widget_Destinations extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-td-destinations';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip Destinations - Destinations', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-map-pin';
    }

    public function get_categories()
    {
        return ['trip-destinations'];
    }

    /**
     * Register the controls for the widget.
     *
     * @return void
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        $this->add_content_style_section();
    }

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
            'listing_heading_text',
            [
                'label' => esc_html__('Listing Heading Text', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                'description' => __('{term_name} will be replaced with the term name', 'togo-framework'),
            ]
        );

        $this->add_control(
            'hide_filter',
            [
                'label' => esc_html__('Hide Filter', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'togo-framework'),
                'label_off' => esc_html__('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'no',
                'selectors' => [
                    '{{WRAPPER}} .togo-trip-filter, {{WRAPPER}} .open-filter-canvas, {{WRAPPER}} .trip-list-header__count' => '{{VALUE}}',
                ],
                'selectors_dictionary'    => [
                    'yes' => 'display: none;',
                    'no'  => 'display: block;',
                ],
            ]
        );

        $this->add_control(
            'hide_sort',
            [
                'label' => esc_html__('Hide Sort', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'togo-framework'),
                'label_off' => esc_html__('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'no',
                'selectors' => [
                    '{{WRAPPER}} .trip-list-header__sort' => '{{VALUE}}',
                ],
                'selectors_dictionary'    => [
                    'yes' => 'display: none;',
                    'no'  => 'display: block;',
                ],
            ]
        );

        $this->add_control(
            'hide_pagination_info',
            [
                'label' => esc_html__('Hide Pagination Info', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'togo-framework'),
                'label_off' => esc_html__('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'no',
                'selectors' => [
                    '{{WRAPPER}} .tour-pagination-info' => '{{VALUE}}',
                ],
                'selectors_dictionary'    => [
                    'yes' => 'display: none;',
                    'no'  => 'display: block;',
                ],
            ]
        );

        $this->add_control(
            'hide_map',
            [
                'label' => esc_html__('Hide Map', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'togo-framework'),
                'label_off' => esc_html__('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'no',
                'selectors' => [
                    '{{WRAPPER}} .site-content + .trip-destinations-heading, {{WRAPPER}} .map-container' => '{{VALUE}}',
                ],
                'selectors_dictionary'    => [
                    'yes' => 'display: none;',
                    'no'  => 'display: block;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function add_content_style_section()
    {
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => __('Style', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => __('Padding', 'togo-framework'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output.
     *
     * @return void
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if (!is_tax('togo_trip_destinations')) {
            return;
        }

        // Get the current taxonomy term
        $term = get_queried_object();

        // Get the term name
        $term_id = $term->term_id;

        $trip_card_layout = apply_filters('togo_trip_card_layout', \Togo\Helper::setting('trip_card_layout'));
        if ($trip_card_layout == 'grid') {
            $column_classes = array('togo-row', 'togo-row-cols-xl-3', 'togo-row-cols-lg-3', 'togo-row-cols-md-2', 'togo-row-cols-sm-1', 'togo-row-cols-xs-1');
        } else {
            $column_classes = array('');
        }

        $listing_heading_text = $settings['listing_heading_text'] ? $settings['listing_heading_text'] : '';
        $listing_heading_text = str_replace('{term_name}', $term->name, $listing_heading_text);
?>
        <?php do_action('togo_archive_trip_before_open_content'); ?>

        <?php
        // Count post by term id
        $trip_count = get_posts(array('post_type' => 'togo_trip', 'tax_query' => array(array('taxonomy' => 'togo_trip_destinations', 'field' => 'term_id', 'terms' => $term_id))));
        $trip_count = count($trip_count);
        $trip_count_string = $trip_count . ' ' . _n('tour', 'tours', $trip_count, 'togo-framework');

        // echo string like "35 tours in Tokyo"
        if ($listing_heading_text) {
            echo '<h4 class="trip-destinations-heading">' . $listing_heading_text . '</h4>';
        } else {
            echo '<h4 class="trip-destinations-heading">' . $trip_count_string . ' in ' . $term->name . '</h4>';
        }
        ?>

        <div class="site-content">

            <?php do_action('togo_archive_trip_after_open_content') ?>

            <?php do_action('togo_before_archive_trip_list') ?>

            <div class="trip-list <?php echo implode(' ', $column_classes); ?>">

                <?php
                if (have_posts()) :
                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();

                        /*
						* Include the Post-Type-specific template for the content.
						* If you want to override this in a child theme, then include a file
						* called content-___.php (where ___ is the Post Type name) and that will be used instead.
						*/
                        \Togo_Framework\Helper::togo_get_template('content-trip.php');

                    endwhile;
                else :
                    \Togo_Framework\Helper::togo_get_template('content-none.php');
                endif;
                ?>

            </div>

            <?php do_action('togo_after_archive_trip_list') ?>

            <?php do_action('togo_archive_trip_before_close_content') ?>
        </div>

        <?php do_action('togo_archive_trip_after_close_content'); ?>
<?php
    }
}
