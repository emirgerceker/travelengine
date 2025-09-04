<?php

namespace Togo_Framework\Elementor\Single_Trips;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;


class Widget_Reviews extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-st-reviews';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip - Reviews', 'togo-framework');
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
    protected function _register_controls()
    {
        $this->add_content_heading_section();
        $this->add_content_reviews_section();
        $this->add_content_heading_style_section();
    }

    protected function add_content_heading_section()
    {
        $this->start_controls_section(
            'content_heading',
            [
                'label' => __('Heading', 'togo-framework'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Customer reviews', 'togo-framework'),
                'label_block' => true,
            ],
        );

        $this->end_controls_section();
    }

    protected function add_content_reviews_section()
    {
        $this->start_controls_section(
            'content_reviews',
            [
                'label' => __('Reviews', 'togo-framework'),
            ]
        );

        $this->add_control(
            'overall_title',
            [
                'label' => __('Overall Title', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Overall rating', 'togo-framework'),
                'label_block' => true,
            ],
        );

        $this->add_control(
            'summary_title',
            [
                'label' => __('Summary Title', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Review summary', 'togo-framework'),
                'label_block' => true,
            ],
        );

        $this->end_controls_section();
    }

    protected function add_content_heading_style_section()
    {
        $this->start_controls_section(
            'content_heading_style',
            [
                'label' => __('Heading', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __('Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-heading' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'label' => __('Typography', 'togo-framework'),
                'selector' => '{{WRAPPER}} .togo-st-heading',
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

        $single_trip_reviews = \Togo\Helper::setting('single_trip_reviews');
        $single_trip_max_star = \Togo\Helper::setting('single_trip_max_star') ? \Togo\Helper::setting('single_trip_max_star') : 5;

        if (!is_singular('togo_trip') || empty($trip_reviews) || !$single_trip_reviews) {
            return;
        }

        $heading = $settings['heading'];
        $overall_title = $settings['overall_title'];
        $summary_title = $settings['summary_title'];

        if (!empty($heading)) {
            echo '<div class="togo-st-heading-wrap">';
            echo '<h2 class="togo-st-heading">';
            echo $heading;
            echo '</h2>';
            echo '</div>';
        }
?>

        <div class="togo-st-reviews" id="togo-st-reviews">
            <?php
            $overall = [];
            $count_trip_reviews = count($trip_reviews);
            ?>
            <div class="reviews">
                <?php
                foreach ($trip_reviews as $review) {
                    $star_int = 0;
                    $review_id = $review->ID;
                    $author_id = $review->post_author;
                    $review_date = $review->post_date;
                    $review_gallery = get_post_meta($review_id, 'trip_reviews_images', true);
                    for ($i = 0; $i < count($single_trip_reviews); $i++) {
                        $trip_review = get_post_meta($review_id, 'trip_reviews_' . $i, true);
                        if ($trip_review) {
                            $star_int += intval($trip_review);
                            $overall[$i][] = $trip_review;
                        }
                    }
                    $star = $star_int / count($single_trip_reviews);
                    if ($star) {
                        $percent = $star / $single_trip_max_star * 100;
                    } else {
                        $percent = 0;
                    }
                    echo '<div class="item">';
                    echo '<div class="info">';
                    if (!empty($author_id)) {
                        $avatar_id = get_user_meta($author_id, 'avatar', true);
                        echo '<div class="avatar">';
                        if ($avatar_id) {
                            echo wp_get_attachment_image($avatar_id, array(48, 48), false, array('class' => 'avatar'));
                        } else {
                            echo get_avatar($author_id, 48);
                        }
                        echo '</div>';
                    }
                    echo '<div class="content">';
                    echo '<div class="name">' . get_the_author_meta('display_name', $author_id) . '</div>';
                    echo '<div class="date">' . sprintf(__('On %s', 'togo-framework'), date('F j, Y', strtotime($review_date))) . '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="rating">';
                    echo \Togo\Icon::get_svg('star');
                    echo \Togo\Icon::get_svg('star');
                    echo \Togo\Icon::get_svg('star');
                    echo \Togo\Icon::get_svg('star');
                    echo \Togo\Icon::get_svg('star');
                    echo '<span class="current-stars" style="width: ' . $percent . '%">';
                    echo \Togo\Icon::get_svg('star');
                    echo \Togo\Icon::get_svg('star');
                    echo \Togo\Icon::get_svg('star');
                    echo \Togo\Icon::get_svg('star');
                    echo \Togo\Icon::get_svg('star');
                    echo '</span>';
                    echo '</div>';
                    echo '<div class="content">';
                    echo '<p>' . $review->post_content . '</p>';
                    echo '</div>';
                    if ($review_gallery) {
                        $review_gallery = explode('|', $review_gallery);
                        echo '<div class="gallery">';
                        foreach ($review_gallery as $image_id) {
                            $image_url = wp_get_attachment_url($image_id);
                            if ($image_url) {
                                echo '<a href="' . esc_url($image_url) . '" class="item" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="review-gallery-' . $review_id . '">';
                                echo '<img src="' . esc_url($image_url) . '" width="100" />';
                                echo '</a>';
                            }
                        }
                        echo '</div>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
            <div class="banner">
                <?php
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
                ?>
                <div class="overall">
                    <?php
                    if (!empty($overall_title)) {
                        echo '<h6>' . $overall_title . '</h6>';
                    }
                    ?>
                    <div class="stars">
                        <?php echo \Togo\Icon::get_svg('star'); ?>
                        <span class="current-stars"><?php echo number_format($overall_rating / count($single_trip_reviews), 1); ?></span>
                        <span class="max-stars"><?php echo '/' . $single_trip_max_star; ?></span>
                    </div>
                    <span class="count"><?php printf(_n('(%s review)', '(%s reviews)', $count_trip_reviews, 'togo-framework'), $count_trip_reviews); ?></span>
                </div>
                <div class="summary">
                    <?php
                    if (!empty($summary_title)) {
                        echo '<h6>' . $summary_title . '</h6>';
                    }
                    ?>
                    <div class="list-reviews">
                        <?php
                        for ($i = 0; $i < count($single_trip_reviews); $i++) {
                            $average = 0;
                            $percent = 0;
                            if (array_key_exists($i, $overall)) {
                                $total = array_sum(array_map('intval', $overall[$i]));
                                if ($total) {
                                    $average = $total / count($overall[$i]);
                                    $percent = $average / $single_trip_max_star * 100;
                                }
                            }
                            echo '<div class="item">';
                            echo '<div class="label">';
                            echo '<span>' . $single_trip_reviews[$i]['text'] . '</span>';
                            echo '<span>' . number_format($average, 1) . '/' . $single_trip_max_star . '</span>';
                            echo '</div>';
                            echo '<div class="progress"><span style="width: ' . $percent . '%"></span></div>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
