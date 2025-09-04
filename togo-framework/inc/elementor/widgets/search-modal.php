<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;
class Togo_Search_Modal_Widget extends Base
{

    public function get_name()
    {
        return 'togo-search-modal';
    }

    public function get_title()
    {
        return __('Search Modal', 'togo-framework');
    }

    public function get_icon_part()
    {
        return 'eicon-search';
    }

    public function get_script_depends()
    {
        return array('togo-widget-search-modal');
    }

    protected function _register_controls()
    {
        $this->add_content_section();
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

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Position', 'togo-framework'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Left', 'togo-framework'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => esc_html__('Right', 'togo-framework'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .search-modal-inner' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'start' => 'transform: translateX(-100%); left: 0; right: auto;',
                    'end' => 'transform: translateX(100%); left: auto; right: 0;',
                ],
                'default' => 'start',
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => esc_html__('Heading', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Search', 'togo-framework'),
            ]
        );

        $this->add_control(
            'placeholder',
            [
                'label' => esc_html__('Placeholder', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Search...', 'togo-framework'),
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Post Type', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => 'post',
                'options' => [
                    'post' => esc_html__('Post', 'togo-framework'),
                    'product' => esc_html__('Product', 'togo-framework'),
                ],
            ]
        );

        $this->add_control(
            'minimum_characters',
            [
                'label' => esc_html__('Minimum Characters', 'togo-framework'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 20,
                'description' => esc_html__('Minimum number of characters to start searching', 'togo-framework'),
            ],
        );

        $this->add_control(
            'number_of_items',
            [
                'label' => esc_html__('Number of Items', 'togo-framework'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 20,
                'description' => esc_html__('Number of items to display', 'togo-framework'),
            ],
        );

        $this->add_control(
            'enable_view_all',
            [
                'label' => esc_html__('Enable View All', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'togo-framework'),
                'label_off' => esc_html__('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => esc_html__('Enable view all results', 'togo-framework'),
            ]
        );

        $this->add_control(
            'view_all_text',
            [
                'label' => esc_html__('View All Text', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('View All Results', 'togo-framework'),
                'condition' => [
                    'enable_view_all' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $heading = $settings['heading'];
        $placeholder = $settings['placeholder'] ? $settings['placeholder'] : esc_html__('Search...', 'togo-framework');
        $post_type = $settings['post_type'] ? $settings['post_type'] : 'post';
        $minimum_characters = $settings['minimum_characters'];
        $number_of_items = $settings['number_of_items'];
        $enable_view_all = $settings['enable_view_all'];
        $view_all_text = $settings['view_all_text'];
?>
        <div class="togo-search-wrapper">
            <div class="search-icon">
                <?php echo \Togo\Icon::get_svg('search'); ?>
            </div>
            <div class="search-modal">
                <div class="search-modal-overlay"></div>
                <div class="search-modal-inner">
                    <div class="search-modal-top">
                        <h3 class="search-modal-head"><?php echo esc_html($heading); ?></h3>
                        <div class="search-modal-close">
                            <?php echo \Togo\Icon::get_svg('close'); ?>
                        </div>
                    </div>
                    <div class="search-form-wrapper">
                        <div class="search-form-inner">
                            <form role="search" method="get" class="togo-search-form" action="<?php echo esc_url(home_url('/')); ?>">
                                <input type="search" class="togo-search-field" autocomplete="off" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                                <input type="text" name="post_type" value="<?php echo esc_attr($post_type); ?>" hidden>
                                <input type="number" name="minimum_characters" value="<?php echo esc_attr($minimum_characters); ?>" hidden>
                                <input type="number" name="number_of_items" value="<?php echo esc_attr($number_of_items); ?>" hidden>
                                <?php
                                if ($enable_view_all == 'yes') {
                                    echo '<input type="text" name="view_all_text" value="' . esc_attr($view_all_text) . '" hidden>';
                                }
                                ?>
                            </form>
                            <div class="search-modal-loading"></div>
                        </div>
                        <div class="search-modal-results"></div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
