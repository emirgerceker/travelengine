<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

defined('ABSPATH') || exit;

class Togo_Language_Currency_Widget extends Base
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
        return 'togo-language-currency';
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
        return __('Language & Currency', 'togo-framework');
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
        return 'eicon-animated-headline';
    }

    /**
     * Register the widget controls.
     *
     * @since 1.0.0
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        $this->add_language_section();
        $this->add_currency_section();
        $this->add_content_style_section();
        $this->add_sub_content_style_section();
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
            'label',
            [
                'label' => __('Label', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('EN / USD', 'togo-framework'),
            ]
        );

        $this->add_control(
            'enabled_language',
            [
                'label' => __('Enable Language', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => '1',
                'default' => '1',
                'description' => __('Multilingual plugins need to be enabled (eg WPML, Polylang....)', 'togo-framework'),
            ],
        );

        $this->add_control(
            'enabled_currency',
            [
                'label' => __('Enable Currency', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => '1',
                'default' => '1',
                'description' => __('Plugin: FOX - Currency Switcher Professional for WooCommerce need to be installed and activated', 'togo-framework'),
            ],
        );

        $this->end_controls_section();
    }

    protected function add_language_section()
    {
        $this->start_controls_section(
            'language_style_section',
            [
                'label' => __('Language', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'enabled_language' => '1',
                ],
            ]
        );

        $this->add_control(
            'language_list',
            [
                'label' => __('Language List', 'togo-framework'),
                'type' => Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'label',
                        'label' => __('Label', 'togo-framework'),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                    ],
                    [
                        'name' => 'url',
                        'label' => __('URL', 'togo-framework'),
                        'type' => Controls_Manager::URL,
                        'label_block' => true,
                    ]
                ],
                'default' => [
                    [
                        'label' => esc_html__('English (EN)', 'togo-framework'),
                    ],
                ],
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function add_currency_section()
    {
        $this->start_controls_section(
            'currency_style_section',
            [
                'label' => __('Currency', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'enabled_currency' => '1',
                ],
            ]
        );

        $this->add_control(
            'currency_list',
            [
                'label' => __('Currency List', 'togo-framework'),
                'type' => Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'label',
                        'label' => __('Label', 'togo-framework'),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                    ],
                    [
                        'name' => 'url',
                        'label' => __('URL', 'togo-framework'),
                        'type' => Controls_Manager::URL,
                        'label_block' => true,
                    ]
                ],
                'default' => [
                    [
                        'label' => 'USD',
                    ],
                ],
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function add_content_style_section()
    {
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lc-wapper .lc-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lc-wapper .lc-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'padding',
            [
                'label' => __('Padding', 'togo-framework'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .lc-wapper .lc-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'radius',
            [
                'label' => __('Border Radius', 'togo-framework'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .lc-wapper .lc-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => __('Border', 'togo-framework'),
                'selector' => '{{WRAPPER}} .lc-wapper .lc-button',
            ]
        );

        $this->end_controls_section();
    }

    protected function add_sub_content_style_section()
    {
        $this->start_controls_section(
            'sub_content_style_section',
            [
                'label' => __('Sub Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'sub_content_position',
            [
                'label' => __('Position', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top_left' => __('Top Left', 'togo-framework'),
                    'top_right' => __('Top Right', 'togo-framework'),
                    'bottom_left' => __('Bottom Left', 'togo-framework'),
                    'bottom_right' => __('Bottom Right', 'togo-framework'),
                ],
                'prefix_class' => 'sub-content-',
                'default' => 'bottom_right',
            ]
        );

        $this->add_control(
            'sub_content_radius',
            [
                'label' => __('Border Radius', 'togo-framework'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .lc-wapper .lc-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
        $label = $settings['label'];
        $language_list = $settings['language_list'];
        $currency_list = $settings['currency_list'];
?>
        <div class="lc-wapper">
            <div class="lc-button">
                <span><?php echo $label; ?></span>
                <?php echo \Togo\Icon::get_svg('chevron-down'); ?>
            </div>
            <div class="lc-content">
                <?php if (!empty($language_list)) : ?>
                    <div class="lc-item">
                        <h4 class="lc-title"><?php echo esc_html__('Language', 'togo-framework'); ?></h4>
                        <ul class="lc-list">
                            <?php foreach ($language_list as $language) : ?>
                                <li>
                                    <a href="<?php echo $language['url']['url']; ?>">
                                        <?php echo $language['label']; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if (!empty($currency_list)) : ?>
                    <div class="lc-item">
                        <h4 class="lc-title"><?php echo esc_html__('Currencies', 'togo-framework'); ?></h4>
                        <ul class="lc-list">
                            <?php foreach ($currency_list as $currency) : ?>
                                <li>
                                    <a href="<?php echo $currency['url']['url']; ?>">
                                        <?php echo $currency['label']; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>

<?php
    }
}
