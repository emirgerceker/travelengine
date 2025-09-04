<?php

/**
 * Search Form widget.
 *
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Class Togo_Search_Form_Widget.
 *
 * A widget for displaying search form.
 *
 * @package Togo_Elementor
 */
class Togo_Search_Form_Widget extends Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-search-form';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Search Form', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-form-vertical';
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

    /**
     * Add the content section for the widget.
     *
     * @return void
     */
    protected function add_content_section()
    {
        /**
         * Content section.
         *
         * @var array The content section.
         */
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'vertical' => __('Vertical', 'togo-framework'),
                    'horizontal' => __('Horizontal', 'togo-framework'),
                ],
                'default' => 'horizontal',
            ]
        );

        $this->add_control(
            'enable_location',
            [
                'label' => __('Enable Location', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'enable_date',
            [
                'label' => __('Enable Date', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'enable_guests',
            [
                'label' => __('Enable Guests', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add the content style section for the widget.
     *
     * @return void
     */
    protected function add_content_style_section()
    {
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'field_height',
            [
                'label' => __('Field Height', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 36,
                ],
                'selectors' => [
                    '{{WRAPPER}} .trip-search-form .form-field input' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .trip-search-form button[type=submit]' => 'flex: 0 0 {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'svg_width',
            [
                'label' => __('SVG Width', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 24,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .trip-search-form .form-field .field-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .trip-search-form button[type=submit] svg' => 'width: {{SIZE}}{{UNIT}};',
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
?>
        <form action="<?php echo get_post_type_archive_link('togo_trip'); ?>" method="get" class="trip-search-form <?php echo esc_attr($settings['layout']); ?>">
            <?php
            $trip_destinations = get_terms(array(
                'taxonomy' => 'togo_trip_destinations',
                'hide_empty' => false, // Set to true to exclude terms with no posts.
            ));
            if (!empty($trip_destinations) && !is_wp_error($trip_destinations) && $settings['enable_location'] === 'yes') {
            ?>
                <div class="form-field field-location">
                    <div class="field-icon"><?php echo \Togo\Icon::get_svg('location'); ?></div>
                    <div class="field-location__input">
                        <input type="text" name="location" placeholder="<?php echo esc_attr__('Where to?', 'togo-framework'); ?>" value="" autocomplete="off">
                    </div>
                    <a href="#" class="field-location__remove">
                        <?php echo \Togo\Icon::get_svg('x'); ?>
                    </a>
                    <div class="field-location__result">
                        <div class="near-me">
                            <div class="near-me__icon"><?php echo \Togo\Icon::get_svg('navigation-one'); ?></div>
                            <span class="near-me__text"><?php echo esc_html__('Near me', 'togo-framework'); ?></span>
                        </div>
                        <div class="location-list">
                            <?php
                            $terms_hierarchy = array();
                            foreach ($trip_destinations as $term) {
                                $terms_hierarchy[$term->parent][] = $term;
                            }

                            // Display terms starting with top-level terms (parent_id = 0)
                            \Togo_Framework\Elementor\Setup::display_terms_hierarchy(0, $terms_hierarchy);
                            ?>
                            <div class="no-result hide"><?php echo esc_html__('No results', 'togo-framework'); ?></div>
                        </div>
                    </div>
                </div>
            <?php }
            ?>
            <?php
            if ($settings['enable_date'] === 'yes') {
            ?>
                <div class="form-field field-dates">
                    <div class="field-icon"><?php echo \Togo\Icon::get_svg('calendar'); ?></div>
                    <div class="field-dates__input">
                        <input type="text" name="dates" placeholder="<?php echo esc_attr__('Select dates', 'togo-framework'); ?>" value="" autocomplete="off">
                    </div>
                    <a href="#" class="field-dates__remove">
                        <?php echo \Togo\Icon::get_svg('x'); ?>
                    </a>
                    <?php echo \Togo_Framework\Template::render_calendar([], true); ?>
                </div>
            <?php } ?>
            <?php
            if ($settings['enable_guests'] === 'yes') {
            ?>
                <div class="form-field field-guests">
                    <div class="field-icon"><?php echo \Togo\Icon::get_svg('users-group'); ?></div>
                    <div class="field-guests__input">
                        <input type="number" min="0" name="guests" placeholder="<?php echo esc_attr__('Number of guests', 'togo-framework'); ?>" value="" autocomplete="off">
                    </div>
                    <a href="#" class="field-guests__remove">
                        <?php echo \Togo\Icon::get_svg('x'); ?>
                    </a>
                </div>
            <?php } ?>
            <button type="submit" class="field-location__button">
                <?php
                if ($settings['layout'] === 'vertical') {
                    echo esc_html__('Search', 'togo-framework');
                } else {
                    echo \Togo\Icon::get_svg('search');
                }
                ?>
            </button>
        </form>
<?php
    }
}
