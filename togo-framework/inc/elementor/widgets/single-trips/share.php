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
class Widget_Share extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-st-share';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip - Share', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-share-arrow';
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
        $this->add_content_section();
        $this->add_style_section();
    }

    public function add_content_section()
    {
        $this->start_controls_section(
            'section_share',
            [
                'label' => __('Share', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'choose_brands',
            [
                'label' => __('Choose Brands', 'togo-framework'),
                'type' => Controls_Manager::SELECT2,
                'options' => [
                    'facebook' => __('Facebook', 'togo-framework'),
                    'x' => __('X', 'togo-framework'),
                    'linkedin' => __('Linkedin', 'togo-framework'),
                    'pinterest' => __('Pinterest', 'togo-framework'),
                    'instagram' => __('Instagram', 'togo-framework'),
                ],
                'multiple' => true,
                'default' => ['facebook', 'x', 'linkedin', 'pinterest', 'instagram'],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    public function add_style_section()
    {
        $this->start_controls_section(
            'section_style_share',
            [
                'label' => __('Share', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'width',
            [
                'label' => __('Width', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-st-share' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'height',
            [
                'label' => __('Height', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-st-share' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'togo-framework'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .togo-st-share' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('icon_tabs');

        $this->start_controls_tab(
            'icon_normal',
            [
                'label' => __('Normal', 'togo-framework'),
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => __('Border Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-share' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-share' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-share svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_hover',
            [
                'label' => __('Hover', 'togo-framework'),
            ]
        );

        $this->add_control(
            'hover_border_color',
            [
                'label' => __('Border Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-share:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_background-color',
            [
                'label' => __('Background Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-share:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_icon_color',
            [
                'label' => __('Icon Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-share:hover svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'popup_title',
            [
                'label' => __('Popup Title', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Share this:', 'togo-framework'),
                'label_block' => true,
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
        $popup_title = $settings['popup_title'] ? trim($settings['popup_title']) : __('Share this:', 'togo-framework');
        $choose_brands = $settings['choose_brands'];
        if (!is_singular('togo_trip')) {
            return;
        }
        $id = get_the_ID();

?>
        <a class="togo-st-share togo-open-modal" href="#share-trip"><?php echo \Togo\Icon::get_svg('share'); ?></a>
        <div class="togo-modal togo-modal-share" id="share-trip">
            <div class="togo-modal-overlay"></div>
            <div class="togo-modal-content">
                <div class="togo-modal-header">
                    <h3 class="togo-modal-title"><?php echo $popup_title; ?></h3>
                    <div class="togo-modal-close"><?php echo \Togo\Icon::get_svg('x'); ?></div>
                </div>
                <div class="togo-modal-body">
                    <?php
                    if ($choose_brands) {
                        echo '<ul>';
                        foreach ($choose_brands as $brand) {
                            if ($brand == 'facebook') {
                                $url = 'https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Ftogothemes.com%2Ftrip%2F' . $id . '%2F';
                            } elseif ($brand == 'x') {
                                $url = 'https://twitter.com/share?url=https%3A%2F%2Ftogothemes.com%2Ftrip%2F' . $id . '%2F';
                            } elseif ($brand == 'linkedin') {
                                $url = 'https://www.linkedin.com/shareArticle?url=https%3A%2F%2Ftogothemes.com%2Ftrip%2F' . $id . '%2F';
                            } elseif ($brand == 'pinterest') {
                                $url = 'https://pinterest.com/pin/create/button/?url=https%3A%2F%2Ftogothemes.com%2Ftrip%2F' . $id . '%2F';
                            } elseif ($brand == 'instagram') {
                                $url = 'https://www.instagram.com/?url=https%3A%2F%2Ftogothemes.com%2Ftrip%2F' . $id . '%2F';
                            }
                            echo '<li><a href="' . $url . '" target="_blank">' . \Togo\Icon::get_svg('brand-' . $brand) . '</a></li>';
                        }
                        echo '</ul>';
                    }
                    ?>
                </div>
            </div>
        </div>
<?php
    }
}
