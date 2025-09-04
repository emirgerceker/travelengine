<?php

/**
 * Breadcrumb widget.
 *
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor\Trip_Destinations;

defined('ABSPATH') || exit;

/**
 * Class Togo_Breadcrumb_Widget.
 *
 * A widget for displaying breadcrumbs.
 *
 * @package Togo_Elementor
 */
class Widget_FAQs extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-td-faqs';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip Destinations - FAQs', 'togo-framework');
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
        return ['trip-destinations'];
    }

    public function get_script_depends()
    {
        // The script dependencies for the widget.
        // In this case, we are returning an array with a single element, the name
        // of the script dependency.
        return array('togo-widget-trip-destinations-faqs');
    }

    /**
     * Register the controls for the widget.
     *
     * @return void
     */
    protected function _register_controls()
    {
        $this->add_content_section();
    }

    public function add_content_section()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'togo-framework'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('FAQ’s about {term_name}', 'togo-framework'),
                'description' => __('{term_name} will be replaced with the term name', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'heading_desc',
            [
                'label' => __('Description', 'togo-framework'),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => __('Have more questions? Contact us.', 'togo-framework'),
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
        if (!is_tax('togo_trip_destinations')) {
            return;
        }

        // Get the current taxonomy term
        $term = get_queried_object();

        // Get the term name
        $term_id = $term->term_id;

        $faqs = get_term_meta($term_id, 'togo_trip_destinations_faqs', true);

        if (empty($faqs[0]['togo_trip_destinations_faqs_question']) && empty($faqs[0]['togo_trip_destinations_faqs_answer'])) {
            return;
        }

        $heading = $settings['heading'];
        $heading_desc = $settings['heading_desc'];
        // replace {term_name} with the term name
        $heading = str_replace('{term_name}', $term->name, $heading);
        $heading_desc = str_replace('{term_name}', $term->name, $heading_desc);
?>
        <div class="togo-td-faqs-wrap">
            <div class="heading">
                <h4><?php echo esc_html($heading); ?></h4>
                <div class="heading-desc"><?php echo $heading_desc; ?></div>
            </div>
            <div class="togo-td-faqs">
                <?php
                foreach ($faqs as $faq) {
                    echo '<div class="togo-td-faqs-item">';
                    echo '<div class="togo-td-faqs-question"><h6 class="togo-st-faqs-question-title">' . $faq['togo_trip_destinations_faqs_question'] . '</h6>' . \Togo\Icon::get_svg('chevron-down') . '</div>';
                    echo '<div class="togo-td-faqs-answer">' . nl2br($faq['togo_trip_destinations_faqs_answer']) . '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
<?php
    }
}
