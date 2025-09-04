<?php

/**
 * Breadcrumb widget.
 *
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor\Single_Trips;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Class Togo_Breadcrumb_Widget.
 *
 * A widget for displaying breadcrumbs.
 *
 * @package Togo_Elementor
 */
class Widget_Gallery extends \Togo_Framework\Elementor\Carousel_Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-st-gallery';
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
        return array('togo-widget-carousel', 'togo-widget-single-trip-gallery');
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip - Gallery', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-gallery-group';
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

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'type',
            [
                'label' => __('Type', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => __('Grid', 'togo-framework'),
                    'carousel' => __('Carousel', 'togo-framework'),
                ]
            ]
        );

        $this->add_control(
            'layout_grid',
            [
                'label' => __('Layout', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => 'layout-01',
                'options' => [
                    'layout-01' => __('Layout 01', 'togo-framework'),
                    'layout-02' => __('Layout 02', 'togo-framework'),
                    'layout-03' => __('Layout 03', 'togo-framework'),
                ],
                'condition' => [
                    'type' => 'grid',
                ]
            ]
        );

        $this->add_control(
            'image-radius',
            [
                'label' => __('Image Radius', 'togo-framework'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .mejs-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .togo-swiper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'image-height',
            [
                'label' => __('Image Height', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-st-gallery-item > img' => 'height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        parent::register_controls();
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
        $trip_video_url = get_post_meta($id, 'trip_video_url', true);
        $trip_video_image = get_post_meta($id, 'trip_video_image', true);
        $trip_gallery_images = get_post_meta($id, 'trip_gallery_images', true);

        if (!is_singular('togo_trip') || empty($trip_gallery_images)) {
            return;
        }
        $trip_gallery_images = explode('|', $trip_gallery_images);
        $sum_images = count($trip_gallery_images);
        $type = $settings['type'];
        $layout_grid = $settings['layout_grid'];
        $i = 0;

        $slider_settings = $this->get_slider_settings($settings);

        $this->add_render_attribute('slider', $slider_settings);


        if ($type == 'grid') {
            echo '<div class="togo-st-gallery grid ' . $layout_grid . '">';
        } else {
?>
            <div <?php $this->print_attributes_string('slider'); ?>>
            <?php
            echo '<div class="swiper-wrapper">';
        }

        if ($trip_video_image['url'] != '' && $trip_video_url != '') {
            $image_name = \Togo_Framework\Helper::get_image_caption_by_url($trip_video_image['url']);
            if ($type == 'grid') {
                echo '<div class="togo-st-gallery-item togo-st-gallery-item-video">';
            } else {
                echo '<div class="togo-st-gallery-item togo-st-gallery-item-video swiper-slide swiper-slide-video">';
            }
            if ($trip_video_image['url']) {
                echo '<img class="lightbox-trigger" alt="' . $image_name . '" data-index="' . $i . '" src="' . esc_url($trip_video_image['url']) . '">';
                $i++;
            }

            echo \Togo\Icon::get_svg('video');
            echo '</div>';
        }

        foreach ($trip_gallery_images as $trip_gallery_image) {
            $image_name = \Togo_Framework\Helper::get_image_caption_by_id($trip_gallery_image);
            if ($type == 'grid') {
                echo '<div class="togo-st-gallery-item">';
            } else {
                echo '<div class="togo-st-gallery-item swiper-slide">';
            }

            echo '<img class="lightbox-trigger" alt="' . $image_name . '" data-index="' . $i . '" src="' . wp_get_attachment_url($trip_gallery_image) . '">';

            echo '</div>';
            $i++;
        }

        if ($type == 'grid') {
            echo '</div>';
        } else {
            echo '</div>';
            echo '</div>';
        }

        if (($layout_grid == 'layout-01' && $sum_images > 5) || ($layout_grid == 'layout-02' && $sum_images > 5) || ($layout_grid == 'layout-03' && $sum_images > 3)) {
            echo '<a href="#" class="togo-st-gallery-show-all">';
            echo \Togo\Icon::get_svg('image');
            echo __('All photos', 'togo-framework');
            echo '</a>';
        }
            ?>
            <div class="togo-lightbox <?php echo $trip_video_image['url'] != '' && $trip_video_url != '' ? 'has-video' : ''; ?>">
                <div class="lightbox-content <?php echo $trip_video_image['url'] != '' && $trip_video_url != '' ? 'with-video' : ''; ?>">
                    <?php
                    if ($trip_video_image['url'] != '' && $trip_video_url != '') {
                        echo '<div class="lightbox-video">';
                        echo \Togo_Framework\Helper::generate_video_embed_html($trip_video_url, $trip_video_image['url']);
                        echo '</div>';
                    }
                    ?>
                    <div class="lightbox-img-wrapper"></div>
                </div>
                <div class="lightbox-actions">
                    <div class="lightbox-caption"></div>
                    <div class="lightbox-progress"><span class="progress">1</span>/<span class="total"><?php echo $i; ?></span></div>
                    <a href="#" class="lightbox-prev">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.75 5.5L8.25 11L13.75 16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    <a href="#" class="lightbox-next">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.25 16.5L13.75 11L8.25 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    <a href="#" class="lightbox-close">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.5 5.50004L5.5 16.5M5.49995 5.5L16.4999 16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </div>
            </div>
    <?php
    }
}
