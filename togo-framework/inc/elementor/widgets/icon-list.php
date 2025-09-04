<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

class Togo_Icon_List_Widget extends Base
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
        return 'togo-icon-list';
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
        return __('Icon List', 'togo-framework');
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
        return 'eicon-bullet-list';
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
            'items',
            [
                'label' => __('Items', 'togo-framework'),
                'type' => Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'icon',
                        'label' => __('Icon', 'togo-framework'),
                        'type' => Controls_Manager::ICONS,
                    ],
                    [
                        'name' => 'title',
                        'label' => __('Title', 'togo-framework'),
                        'type' => Controls_Manager::TEXT,
                        'default' => __('Title', 'togo-framework'),
                        'label_block' => true,
                    ],
                    [
                        'name' => 'link',
                        'label' => __('Link', 'togo-framework'),
                        'type' => Controls_Manager::URL,
                        'label_block' => true,
                        'default' => [
                            'url' => '#',
                        ],
                    ],
                ],
                'title_field' => '{{{ title }}}',
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
            'alignment',
            [
                'label' => __('Alignment', 'togo-framework'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'vertical' => [
                        'title' => __('Vertical', 'togo-framework'),
                        'icon' => 'eicon-navigation-vertical',
                    ],
                    'horizontal' => [
                        'title' => __('Horizontal', 'togo-framework'),
                        'icon' => 'eicon-navigation-horizontal',
                    ]
                ],
                'default' => 'horizontal',
                'toggle' => false,
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => __('Gap', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-icon-list' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label' => __('Space Between', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-icon-list-item + .togo-icon-list-item' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_gap',
            [
                'label' => __('Icon Gap', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-icon-list-item-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-icon-list-item-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => __('Typography', 'togo-framework'),
                'selector' => '{{WRAPPER}} .togo-icon-list-item-title',
            ]
        );

        $this->start_controls_tabs('item_tabs');

        $this->start_controls_tab(
            'item_tabs_normal',
            [
                'label' => __('Normal', 'togo-framework'),
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-icon-list-item-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-icon-list-item-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'item_tabs_hover',
            [
                'label' => __('Hover', 'togo-framework'),
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => __('Icon Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-icon-list-item-link:hover .togo-icon-list-item-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => __('Title Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-icon-list-item-link:hover .togo-icon-list-item-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tabs();

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
        $items = $settings['items'];
        if (empty($items)) {
            return;
        }
        $alignment = $settings['alignment'];
?>
        <div class="togo-icon-list togo-icon-list-<?php echo esc_attr($alignment); ?>">
            <?php
            foreach ($items as $item) {
                $icon = $item['icon'] ? trim($item['icon']['value']) : '';
                $icon_name = str_replace('togo-svg ', '', $icon);
            ?>
                <div class="togo-icon-list-item">
                    <?php if (!empty($item['link']['url'])): ?>
                        <a href="<?php echo $item['link']['url']; ?>" class="togo-icon-list-item-link">
                        <?php endif; ?>
                        <?php if (!empty($icon_name)): ?>
                            <span class="togo-icon-list-item-icon">
                                <?php echo \Togo\Icon::get_svg($icon_name); ?>
                            </span>
                        <?php endif; ?>
                        <span class="togo-icon-list-item-title">
                            <?php echo $item['title']; ?>
                        </span>
                        <?php if (!empty($item['link']['url'])): ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php
            }
            ?>
        </div>
<?php
    }
}
