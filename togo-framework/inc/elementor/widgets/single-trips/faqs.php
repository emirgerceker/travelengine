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
class Widget_Faqs extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-st-faqs';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip - Faqs', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-help-o';
    }

    public function get_categories()
    {
        return ['single-trips'];
    }

    public function get_script_depends()
    {
        // The script dependencies for the widget.
        // In this case, we are returning an array with a single element, the name
        // of the script dependency.
        return array('togo-widget-single-trip-faqs');
    }

    /**
     * Register the controls for the widget.
     *
     * @return void
     */
    protected function _register_controls()
    {
        $this->add_content_heading_section();
        $this->add_content_heading_style_section();
        $this->add_content_accordion_style_section();
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
                'default' => __('Frequently asked questions', 'togo-framework'),
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

    protected function add_content_accordion_style_section()
    {
        $this->start_controls_section(
            'content_accordion_style',
            [
                'label' => __('Accordion', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'row_gap',
            [
                'label' => __('Row Gap', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-st-faqs' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __('Radius', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-st-faqs-item' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'padding',
            [
                'label' => __('Padding', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-st-faqs-question' => 'padding: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .togo-st-faqs-answer' => 'padding-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .togo-st-faqs-answer' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'accordion_border_color',
            [
                'label' => __('Border Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-faqs-item' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .togo-st-faqs-answer' => 'border-top-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'accordion_background_color',
            [
                'label' => __('Background Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-faqs-item' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'question_color',
            [
                'label' => __('Question Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-faqs-question-title' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'question_hover_color',
            [
                'label' => __('Question Hover Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-faqs-question:hover .togo-st-faqs-question-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .togo-st-faqs-question:hover .togo-svg-icon' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'answer_color',
            [
                'label' => __('Answer Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-faqs-answer' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-faqs-question .togo-svg-icon' => 'color: {{VALUE}};',
                ]
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
        if (!is_singular('togo_trip')) {
            return;
        }
        $trip_faqs = get_post_meta($id, 'trip_faqs', true);

        if (empty($trip_faqs) || $trip_faqs[0]['trip_faqs_question'] == '') {
            return;
        }
        $heading = $settings['heading'];
        if (!empty($heading)) {
            echo '<div class="togo-st-heading-wrap">';
            echo '<h2 class="togo-st-heading">';
            echo $heading;
            echo '</h2>';
            echo '</div>';
        }
        echo '<div class="togo-st-faqs">';
        foreach ($trip_faqs as $key => $value) {
            $question = $value['trip_faqs_question'];
            $answer = $value['trip_faqs_answer'];
            echo '<div class="togo-st-faqs-item">';
            echo '<div class="togo-st-faqs-question">';
            echo '<h4 class="togo-st-faqs-question-title">' . $question . '</h4>';
            echo \Togo\Icon::get_svg('chevron-down');
            echo '</div>';
            echo '<div class="togo-st-faqs-answer">';
            echo nl2br($answer);
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
}
