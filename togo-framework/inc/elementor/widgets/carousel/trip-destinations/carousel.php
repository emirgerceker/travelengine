<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Trip_Destinations_Carousel extends Carousel_Base
{

    /**
     * Retrieves the widget name.
     *
     * @since 1.0.0
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        // Widget name used in the Elementor editor.
        return 'togo-trip-destinations-carousel';
    }

    /**
     * Retrieves the widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        // The title of the widget, displayed in the Elementor editor.
        return __('Trip Destinations - Carousel', 'togo-framework');
    }

    /**
     * Retrieves the widget icon.
     *
     * @since 1.0.0
     *
     * @return string The widget icon.
     */
    public function get_icon_part()
    {
        // The icon of the widget, displayed in the Elementor editor.
        return 'eicon-carousel-loop';
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
        return array('togo-widget-carousel', 'togo-widget-trip-destinations-carousel');
    }

    /**
     * Retrieves the categories of the widget.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array The categories of the widget.
     */
    public function get_categories()
    {
        // The categories of the widget, displayed in the Elementor editor.
        return array('trip-destinations');
    }

    /**
     * Registers controls for the widget.
     *
     * This protected method is called when the widget is initialized.
     * It registers the controls for the widget.
     *
     * @since 1.0.0
     */
    protected function _register_controls()
    {
        $this->add_content_style_section();
        parent::register_controls();
    }

    protected function add_content_style_section()
    {
        $this->start_controls_section(
            'content_style',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label' => __('Image Size', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => '980x1024',
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Renders the widget by printing the slider HTML.
     *
     * This protected method is called when the widget is initialized.
     * It retrieves the settings for the widget and calls the `print_slider` method
     * to display the slider HTML.
     *
     * @return void
     */
    protected function render()
    {
        // Get the settings for the widget.
        // These settings are used to customize the appearance and behavior of the widget.
        $settings = $this->get_settings_for_display();

        if (!is_tax('togo_trip_destinations')) {
            return;
        }

        // Get the current taxonomy term
        $term = get_queried_object();

        // Get the term ID
        $term_id = $term->term_id;

        $gallery = get_term_meta($term_id, 'togo_trip_destinations_gallery', true);

        if (empty($gallery)) {
            return;
        }

        $galleries = explode('|', $gallery);

        // Get the slider settings.
        // These settings are used to customize the slider behavior.
        $slider_settings = $this->get_slider_settings($settings);

        // Add the slider settings as attributes.
        $this->add_render_attribute('slider', $slider_settings);

        $image_size = $settings['image_size'] ? $settings['image_size'] : '980x1024';
?>
        <div class="topbar-swiper-wrapper">
            <div <?php $this->print_attributes_string('slider'); ?>>
                <div class="swiper-wrapper">
                    <?php
                    foreach ($galleries as $gallery) {
                        $image_url = \Togo\Helper::togo_image_resize($gallery, $image_size);
                        if (empty($image_url)) {
                            continue;
                        }
                        echo '<div class="swiper-slide">';
                        echo '<img src="' . $image_url . '" alt="">';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
<?php
    }
}
