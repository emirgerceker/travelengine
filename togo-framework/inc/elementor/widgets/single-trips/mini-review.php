<?php

/**
 * Breadcrumb widget.
 *
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor\Single_Trips;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

/**
 * Class Togo_Breadcrumb_Widget.
 *
 * A widget for displaying breadcrumbs.
 *
 * @package Togo_Elementor
 */
class Widget_Mini_Review extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-st-mini-review';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip - Mini Review', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-rating';
    }

    public function get_categories()
    {
        return ['single-trips'];
    }

    /**
     * Register the controls for the widget.
     *
     * @return void
     */
    protected function _register_controls() {}

    /**
     * Render the widget output.
     *
     * @return void
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $single_trip_reviews = \Togo\Helper::setting('single_trip_reviews');
        if (!is_singular('togo_trip')) {
            return;
        }
        $id = get_the_ID();
        $args = array(
            'post_type' => 'togo_review',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'review_trip_id',
                    'value' => $id,
                    'compare' => '=',
                )
            )
        );
        $trip_reviews = get_posts($args);
        $count_trip_reviews = count($trip_reviews) ? count($trip_reviews) : 0;

        if (empty($trip_reviews)) {
            $overall_rating = 0;
        } else {
            $overall = array();
            foreach ($trip_reviews as $review) {
                $review_id = $review->ID;
                for ($i = 0; $i < count($single_trip_reviews); $i++) {
                    $trip_review = get_post_meta($review_id, 'trip_reviews_' . $i, true);
                    if ($trip_review) {
                        $overall[$i][] = $trip_review;
                    }
                }
            }

            $overall_rating = 0;
            for ($i = 0; $i < count($single_trip_reviews); $i++) {
                $average = 0;
                if (array_key_exists($i, $overall)) {
                    $total = array_sum(array_map('intval', $overall[$i]));
                    if ($total) {
                        $average = $total / count($overall[$i]);
                        $overall_rating += $average;
                    }
                }
            }
        }



        echo '<div class="togo-st-mini-review">';
        echo '<a href="#togo-st-reviews">';
        echo \Togo\Icon::get_svg('star');
        echo '<span class="togo-st-mini-review-score">' . round($overall_rating / count($single_trip_reviews), 1) . '</span>';
        if ($count_trip_reviews == 1 || $count_trip_reviews == 0) {
            echo '<span class="togo-st-mini-review-count">' . sprintf('(%d review)', $count_trip_reviews) . '</span>';
        } else {
            echo '<span class="togo-st-mini-review-count">' . sprintf('(%d reviews)', $count_trip_reviews) . '</span>';
        }
        echo '</a>';
        echo '</div>';
    }
}
