<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

class Togo_Destinations_Widget extends Base
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
        return 'togo-mega-destinations';
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
        return __('Mega Destinations', 'togo-framework');
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
        return 'eicon-map-pin';
    }

    public function get_script_depends()
    {
        return ['togo-widget-destinations'];
    }

    /**
     * Register the widget controls.
     *
     * @since 1.0.0
     */
    protected function _register_controls()
    {
        $this->add_top_destination_section();
        $this->add_content_section();
        $this->add_banner_section();
        $this->add_banner_style_section();
    }

    protected function add_top_destination_section()
    {
        $this->start_controls_section(
            'top_destination_section',
            [
                'label' => __('Top Destination', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'top_destination_enabled',
            [
                'label' => __('Enable', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Enable top destination', 'togo-framework'),
            ]
        );

        $destinations = \Togo\Helper::get_taxonomy_terms('togo_trip_destinations', true);

        $this->add_control(
            'top_destinations',
            [
                'label' => __('Top Destinations', 'togo-framework'),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => $destinations,
                'description' => __('Select locations to display', 'togo-framework'),
                'multiple' => true,
            ]
        );

        $this->add_control(
            'top_destination_enable_view_all',
            [
                'label' => __('View All', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'top_destination_view_all_label',
            [
                'label' => __('View All Label', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('View All', 'togo-framework'),
            ]
        );

        $this->add_control(
            'top_destination_view_all_url',
            [
                'label' => __('View All URL', 'togo-framework'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
                'default' => ['url' => 'https://your-link.com'],
                'dynamic' => ['active' => true],
            ]
        );

        $this->end_controls_section();
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

        $destinations = \Togo\Helper::get_taxonomy_terms('togo_trip_destinations');

        $this->add_control(
            'destinations_enabled',
            [
                'label' => __('Enable', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Enable destinations', 'togo-framework'),
            ]
        );

        $this->add_control(
            'destinations',
            [
                'label' => __('Destinations', 'togo-framework'),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => $destinations,
                'description' => __('Select destinations to display', 'togo-framework'),
                'multiple' => true,
            ]
        );

        $this->add_control(
            'max_items',
            [
                'label' => __('Max Items', 'togo-framework'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'description' => __('Maximum number of child items to display', 'togo-framework'),
            ]
        );

        $this->add_control(
            'enable_view_all',
            [
                'label' => __('View All', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function add_banner_section()
    {
        $this->start_controls_section(
            'banner_section',
            [
                'label' => __('Banner', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'banner_enabled',
            [
                'label' => __('Enable', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'banner_image',
            [
                'label' => __('Banner Image', 'togo-framework'),
                'type' => Controls_Manager::MEDIA,
                'selectors' => [
                    '{{WRAPPER}} .destinations-banner .inner' => 'background-image: url({{URL}})',
                ],
            ]
        );

        $this->add_control(
            'banner_title',
            [
                'label' => __('Banner Title', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Togo Travel Booking WordPress', 'togo-framework'),
            ]
        );

        $this->add_control(
            'banner_description',
            [
                'label' => __('Banner Description', 'togo-framework'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('The most complete Tour Management System', 'togo-framework'),
            ]
        );

        $this->add_control(
            'banner_link_text',
            [
                'label' => __('Banner Link Text', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Get it now', 'togo-framework'),
            ]
        );

        $this->add_control(
            'banner_link',
            [
                'label' => __('Banner Link', 'togo-framework'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
                'default' => ['url' => 'https://your-link.com'],
                'dynamic' => ['active' => true],
            ]
        );

        $this->end_controls_section();
    }

    protected function add_banner_style_section()
    {
        $this->start_controls_section(
            'banner_style_section',
            [
                'label' => __('Banner', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'banner_enabled' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'banner_title_color',
            [
                'label' => __('Title Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .destinations-banner-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'banner_description_color',
            [
                'label' => __('Description Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .destinations-banner-desc' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->start_controls_tabs('banner_link_tabs');

        $this->start_controls_tab(
            'banner_link_normal',
            [
                'label' => __('Normal', 'togo-framework'),
            ]
        );

        $this->add_control(
            'banner_link_text_color',
            [
                'label' => __('Text Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'banner_link_background_color',
            [
                'label' => __('Background Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'banner_link_hover',
            [
                'label' => __('Hover', 'togo-framework'),
            ]
        );

        $this->add_control(
            'banner_link_hover_text_color',
            [
                'label' => __('Text Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'banner_link_hover_background_color',
            [
                'label' => __('Background Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

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
        $top_destination_enabled = $settings['top_destination_enabled'];
        $top_destinations = $settings['top_destinations'];
        $top_destination_enable_view_all = $settings['top_destination_enable_view_all'];
        $top_destination_view_all_label = $settings['top_destination_view_all_label'];
        $top_destination_view_all_url = $settings['top_destination_view_all_url'];
        $destinations = $settings['destinations'];
        $max_items = $settings['max_items'];
        $enable_view_all = $settings['enable_view_all'];
        $banner_enabled = $settings['banner_enabled'];
?>
        <div class="togo-mega-destinations">
            <?php
            if ($settings['destinations_enabled'] == 'yes') {
            ?>
                <div class="destinations-nav">
                    <div class="nav">
                        <?php
                        if ($top_destination_enabled == 'yes') {
                            echo '<div class="nav-item is-active"><a href="#">' . esc_html__('Top destinations', 'togo-framework') . \Togo\Icon::get_svg('chevron-right') . '</a></div>';
                        }
                        ?>
                        <?php
                        if (!empty($destinations)) {
                            foreach ($destinations as $key => $destination) {
                                if ($top_destination_enabled != 'yes' && $key == 0) {
                                    $active = 'is-active';
                                } else {
                                    $active = '';
                                }
                                $term = get_term_by('slug', $destination, 'togo_trip_destinations');

                                if (!$term) continue;
                                echo '<div class="nav-item ' . $active . '">';
                                echo '<a href="#">' . $term->name . \Togo\Icon::get_svg('chevron-right') . '</a>';
                                echo '</div>';

                            }
                        }
                        ?>
                    </div>
                    <?php
                    if ($top_destination_enable_view_all == 'yes') {
                        echo '<div class="view-all">';
                        echo '<a href="' . $top_destination_view_all_url['url'] . '" class="togo-button underline">' . $top_destination_view_all_label . '</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            <?php } ?>
            <div class="destinations-content">
                <?php
                if ($top_destination_enabled == 'yes') {
                ?>
                    <div class="destinations-box is-active">
                        <h6 class="title"><?php echo esc_html__('Top destinations', 'togo-framework'); ?></h6>
                        <div class="children-list">
                            <?php
                            if (!empty($top_destinations)) {
                                foreach ($top_destinations as $top_destination) {
                                    $term = get_term_by('slug', $top_destination, 'togo_trip_destinations');

                                    if (!$term) continue;
                                    $image_url = get_term_meta($term->term_id, 'togo_trip_destinations_thumbnail', true);
                                    echo '<div class="children-item">';
                                    echo '<a href="' . get_term_link($term->term_id, 'togo_trip_destinations') . '">';
                                    if (!empty($image_url)) {
                                        echo '<img src="' . $image_url['url'] . '" alt="' . $term->name . '">';
                                    }
                                    echo '<span>' . $term->name . '</span>';
                                    echo '</a>';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
                ?>
                <?php
                if (!empty($destinations)) {
                    foreach ($destinations as $key => $destination) {
                        if ($top_destination_enabled != 'yes' && $key == 0) {
                            $active = 'is-active';
                        } else {
                            $active = '';
                        }
                        $term = get_term_by('slug', $destination, 'togo_trip_destinations');
                        if (!$term) continue;
                        $childrens = \Togo\Helper::get_child_terms_by_parent_id($term->term_id, 'togo_trip_destinations');
                        echo '<div class="destinations-box ' . $active . '">';
                        echo '<h6 class="title">' . sprintf(esc_html__('Top destinations in %s', 'togo-framework'), $term->name) . '</h6>';
                        if (!empty($childrens)) {
                            echo '<div class="children-list">';
                            foreach ($childrens as $key => $child) {
                                if ($key >= $max_items) break;
                                $child_term = get_term_by('id', $child, 'togo_trip_destinations');
                                if (!$child_term) continue;
                                $image_url = get_term_meta($child_term->term_id, 'togo_trip_destinations_thumbnail', true);
                                echo '<div class="children-item">';
                                echo '<a href="' . get_term_link($child_term->term_id, 'togo_trip_destinations') . '">';
                                if (!empty($image_url)) {
                                    echo '<img src="' . $image_url['url'] . '" alt="' . $child_term->name . '">';
                                }
                                echo '<span>' . $child_term->name . '</span>';
                                echo '</a>';
                                echo '</div>';
                            }
                            echo '</div>';
                        } else {
                            echo '<p class="no-destinations">' . esc_html__('No destinations found', 'togo-framework') . '</p>';
                        }
                        if ($enable_view_all == 'yes' && !empty($childrens)) {
                            echo '<div class="view-all">';
                            echo '<a href="' . get_term_link($term->term_id, 'togo_trip_destinations') . '" class="togo-button underline">' . esc_html__('View all', 'togo-framework') . '</a>';
                            echo '</div>';
                        }
                        echo '</div>';
                    }
                }
                ?>
            </div>
            <?php
            if ($banner_enabled == 'yes') {
                $this->render_banner_section($settings);
            }
            ?>
        </div>
    <?php
    }

    protected function render_banner_section($settings)
    {
        $banner_title = $settings['banner_title'];
        $banner_description = $settings['banner_description'];
        $banner_link_text = $settings['banner_link_text'];
        $banner_link = $settings['banner_link'];
    ?>
        <div class="destinations-banner">
            <div class="inner">
                <?php
                if (!empty($banner_title)) {
                    echo '<h3 class="destinations-banner-title">' . $banner_title . '</h3>';
                }
                if (!empty($banner_description)) {
                    echo '<p class="destinations-banner-desc">' . $banner_description . '</p>';
                }
                if (!empty($banner_link)) {
                    $is_external = $banner_link['is_external'] ? $banner_link['target'] : '_self';
                    echo '<a href="' . $banner_link['url'] . '" class="togo-button full-filled" target="' . $is_external . '">' . $banner_link_text . '</a>';
                }
                ?>
            </div>
        </div>
<?php
    }
}
