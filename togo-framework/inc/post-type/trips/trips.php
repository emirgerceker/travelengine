<?php

namespace Togo_Framework\Post_Type;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Addons init
 *
 * @since 1.0.0
 */
class Trips
{

    /**
     * Instance
     *
     * @var $instance
     */
    private static $instance;

    /**
     * Initiator
     *
     * @since 1.0.0
     * @return object
     */
    public static function instance()
    {
        if (! isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        // Hook the post type and taxonomy registration to the 'init' action
        add_filter('uxper_register_post_type', array($this, 'register_post_type'));
        // Add custom slug settings to Permalinks
        add_action('admin_init', [$this, 'add_slug_settings']);
        add_action('current_screen', array($this, 'settings_save'));
        add_action('update_option_togo_trip_slug', [$this, 'flush_rewrite_on_slug_change'], 10, 2);
        // Register taxonomy
        add_filter('uxper_register_taxonomy', array($this, 'register_taxonomy'));
        add_filter('uxper_register_term_meta', array($this, 'register_term_meta'));

        add_action('admin_enqueue_scripts', array($this, 'enqueue_togo_trip_trips'));
    }

    /**
     * Register Post Type
     *
     * @since 1.0.0
     * @return void
     */
    public function register_post_type($post_types)
    {
        $slug = get_option('togo_trip_slug', 'togo_trip');

        $post_types['togo_trip'] = array(
            'label'           => esc_html__('Trips', 'togo-framework'),
            'singular_name'   => esc_html__('Trips', 'togo-framework'),
            'supports'        => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
            'menu_icon'       => 'dashicons-location',
            'can_export'      => true,
            'show_in_rest'    => false,
            'capability_type' => 'trip',
            'map_meta_cap'    => true,
            'menu_position'   => 4,
            'rewrite'         => array(
                'slug' => apply_filters('togo_trip_slug', $slug),
            ),
        );

        return $post_types;
    }

    /**
     * Add custom slug field to Permalink Settings
     *
     * @since 1.0.0
     * @return void
     */
    public function add_slug_settings()
    {
        add_settings_section(
            'togo_trip_slug_section',
            __('Trip Permalink', 'togo-framework'),
            '__return_null',
            'permalink'
        );

        add_settings_field(
            'togo_trip_slug',
            __('Trip base', 'togo-framework'),
            [$this, 'display_slug_field'],
            'permalink',
            'togo_trip_slug_section'
        );

        add_settings_field(
            'togo_trip_destination_slug',
            __('Trip destination base', 'togo-framework'),
            [$this, 'display_destination_slug_field'],
            'permalink',
            'togo_trip_slug_section'
        );

        add_settings_field(
            'togo_trip_activities_slug',
            __('Trip activities base', 'togo-framework'),
            [$this, 'display_activities_slug_field'],
            'permalink',
            'togo_trip_slug_section'
        );

        add_settings_field(
            'togo_trip_types_slug',
            __('Trip types base', 'togo-framework'),
            [$this, 'display_types_slug_field'],
            'permalink',
            'togo_trip_slug_section'
        );

        add_settings_field(
            'togo_trip_durantions_slug',
            __('Trip durantions base', 'togo-framework'),
            [$this, 'display_durantions_slug_field'],
            'permalink',
            'togo_trip_slug_section'
        );

        add_settings_field(
            'togo_trip_tod_slug',
            __('Trip time of day base', 'togo-framework'),
            [$this, 'display_tod_slug_field'],
            'permalink',
            'togo_trip_slug_section'
        );

        add_settings_field(
            'togo_trip_languages_slug',
            __('Trip languages base', 'togo-framework'),
            [$this, 'display_languages_slug_field'],
            'permalink',
            'togo_trip_slug_section'
        );

        register_setting('permalink', 'togo_trip_slug', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'togo_trip',
        ]);

        register_setting('permalink', 'togo_trip_destination_slug', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'togo_trip_destinations',
        ]);

        register_setting('permalink', 'togo_trip_activities_slug', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'togo_trip_activities',
        ]);

        register_setting('permalink', 'togo_trip_types_slug', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'togo_trip_types',
        ]);

        register_setting('permalink', 'togo_trip_durantions_slug', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'togo_trip_durantions',
        ]);

        register_setting('permalink', 'togo_trip_tod_slug', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'togo_trip_tod',
        ]);

        register_setting('permalink', 'togo_trip_languages_slug', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'togo_trip_languages',
        ]);
    }

    /**
     * Display the custom slug field
     *
     * @since 1.0.0
     * @return void
     */
    public function display_slug_field()
    {
        $value = get_option('togo_trip_slug', 'togo_trip');
        echo '<input name="togo_trip_slug" type="text" class="regular-text" value="' . esc_attr($value) . '" />';
        echo '<p class="description">' . __('Set the base slug for the Trips post type.', 'togo-framework') . '</p>';
    }

    /**
     * Display the custom slug field
     *
     * @since 1.0.0
     * @return void
     */
    public function display_destination_slug_field()
    {
        $value = get_option('togo_trip_destination_slug', 'togo_trip_destinations');
        echo '<input name="togo_trip_destination_slug" type="text" class="regular-text" value="' . esc_attr($value) . '" />';
        echo '<p class="description">' . __('Set the base slug for the Trips Destinations taxonomy.', 'togo-framework') . '</p>';
    }

    /**
     * Display the custom slug field
     *  
     * @since 1.0.0
     * @return void
     * */
    public function display_activities_slug_field()
    {
        $value = get_option('togo_trip_activities_slug', 'togo_trip_activities');
        echo '<input name="togo_trip_activities_slug" type="text" class="regular-text" value="' . esc_attr($value) . '" />';
        echo '<p class="description">' . __('Set the base slug for the Trips Activities taxonomy.', 'togo-framework') . '</p>';
    }

    /**
     *  
     * @since 1.0.0
     * @return void
     *  */
    public function display_types_slug_field()
    {
        $value = get_option('togo_trip_types_slug', 'togo_trip_types');
        echo '<input name="togo_trip_types_slug" type="text" class="regular-text" value="' . esc_attr($value) . '" />';
        echo '<p class="description">' . __('Set the base slug for the Trips Types taxonomy.', 'togo-framework') . '</p>';
    }

    /**
     *  
     * @since 1.0.0
     * @return void
     *  */
    public function display_durantions_slug_field()
    {
        $value = get_option('togo_trip_durantions_slug', 'togo_trip_durantions');
        echo '<input name="togo_trip_durantions_slug" type="text" class="regular-text" value="' . esc_attr($value) . '" />';
        echo '<p class="description">' . __('Set the base slug for the Trips Durantions taxonomy.', 'togo-framework') . '</p>';
    }

    /**
     *  
     * @since 1.0.0
     * @return void
     *  */
    public function display_tod_slug_field()
    {
        $value = get_option('togo_trip_tod_slug', 'togo_trip_tod');
        echo '<input name="togo_trip_tod_slug" type="text" class="regular-text" value="' . esc_attr($value) . '" />';
        echo '<p class="description">' . __('Set the base slug for the Trips Time of Day taxonomy.', 'togo-framework') . '</p>';
    }

    /**
     *  
     * @since 1.0.0
     * @return void
     *  */
    public function display_languages_slug_field()
    {
        $value = get_option('togo_trip_languages_slug', 'togo_trip_languages');
        echo '<input name="togo_trip_languages_slug" type="text" class="regular-text" value="' . esc_attr($value) . '" />';
        echo '<p class="description">' . __('Set the base slug for the Trips Languages taxonomy.', 'togo-framework') . '</p>';
    }

    /**
     * Flush rewrite rules when the slug changes
     *
     * @since 1.0.0
     * @return void
     */
    public function flush_rewrite_on_slug_change($old_value, $new_value)
    {
        if ($old_value !== $new_value) {
            flush_rewrite_rules();
        }
    }

    /**
     * Save the settings.
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function settings_save()
    {
        if (
            ! is_admin()
        ) {
            return;
        }

        if (
            ! $screen = get_current_screen()
        ) {
            return;
        }

        if (
            'options-permalink' != $screen->id
        ) {
            return;
        }

        $togo_trip = get_option('togo_trip_slug');
        $togo_trip_destination_slug = get_option('togo_trip_destination_slug');
        $togo_trip_activities_slug = get_option('togo_trip_activities_slug');
        $togo_trip_types_slug = get_option('togo_trip_types_slug');
        $togo_trip_durantions_slug = get_option('togo_trip_durantions_slug');
        $togo_trip_tod_slug = get_option('togo_trip_tod_slug');
        $togo_trip_languages_slug = get_option('togo_trip_languages_slug');

        if (
            isset($_POST['togo_trip_slug'])
        ) {
            $togo_trip = sanitize_text_field(trim($_POST['togo_trip_slug']));
        }

        if (
            isset($_POST['togo_trip_destination_slug'])
        ) {
            $togo_trip_destination_slug = sanitize_text_field(trim($_POST['togo_trip_destination_slug']));
        }

        if (
            isset($_POST['togo_trip_activities_slug'])
        ) {
            $togo_trip_activities_slug = sanitize_text_field(trim($_POST['togo_trip_activities_slug']));
        }

        if (
            isset($_POST['togo_trip_types_slug'])
        ) {
            $togo_trip_types_slug = sanitize_text_field(trim($_POST['togo_trip_types_slug']));
        }

        if (
            isset($_POST['togo_trip_durantions_slug'])
        ) {
            $togo_trip_durantions_slug = sanitize_text_field(trim($_POST['togo_trip_durantions_slug']));
        }

        if (
            isset($_POST['togo_trip_tod_slug'])
        ) {
            $togo_trip_tod_slug = sanitize_text_field(trim($_POST['togo_trip_tod_slug']));
        }

        if (
            isset($_POST['togo_trip_languages_slug'])
        ) {
            $togo_trip_languages_slug = sanitize_text_field(trim($_POST['togo_trip_languages_slug']));
        }

        update_option('togo_trip_slug', $togo_trip);
        update_option('togo_trip_destination_slug', $togo_trip_destination_slug);
        update_option('togo_trip_activities_slug', $togo_trip_activities_slug);
        update_option('togo_trip_types_slug', $togo_trip_types_slug);
        update_option('togo_trip_durantions_slug', $togo_trip_durantions_slug);
        update_option('togo_trip_tod_slug', $togo_trip_tod_slug);
        update_option('togo_trip_languages_slug', $togo_trip_languages_slug);
    }


    /**
     * Register taxonomy
     * @param $taxonomies
     * @return mixed
     */
    public function register_taxonomy($taxonomies)
    {
        $togo_trip_destination_slug = get_option('togo_trip_destination_slug', 'togo_trip_destinations');
        $togo_trip_activities_slug = get_option('togo_trip_activities_slug', 'togo_trip_activities');
        $togo_trip_types_slug = get_option('togo_trip_types_slug', 'togo_trip_types');
        $togo_trip_durantions_slug = get_option('togo_trip_durantions_slug', 'togo_trip_durantions');
        $togo_trip_tod_slug = get_option('togo_trip_tod_slug', 'togo_trip_tod');
        $togo_trip_languages_slug = get_option('togo_trip_languages_slug', 'togo_trip_languages');

        $taxonomies['togo_trip_pricing_categories'] = array(
            'post_type'     => 'togo_trip',
            'hierarchical'  => true,
            'show_in_rest'  => false,
            'label'         => esc_html__('Pricing Categories', 'togo-framework'),
            'singular_name' => esc_html__('Pricing Categories', 'togo-framework'),
            'rewrite'       => array(
                'slug' => apply_filters('togo_trip_pricing_categories_slug', 'togo_trip_pricing_categories'),
            ),
        );

        $taxonomies['togo_trip_destinations'] = array(
            'post_type'     => 'togo_trip',
            'hierarchical'  => true,
            'show_in_rest'  => false,
            'label'         => esc_html__('Destinations', 'togo-framework'),
            'singular_name' => esc_html__('Destinations', 'togo-framework'),
            'rewrite'       => array(
                'slug' => apply_filters('togo_trip_destinations_slug', $togo_trip_destination_slug),
            ),
        );

        $taxonomies['togo_trip_activities'] = array(
            'post_type'     => 'togo_trip',
            'hierarchical'  => true,
            'show_in_rest'  => false,
            'label'         => esc_html__('Activities', 'togo-framework'),
            'singular_name' => esc_html__('Activities', 'togo-framework'),
            'rewrite'       => array(
                'slug' => apply_filters('togo_trip_activities_slug', $togo_trip_activities_slug),
            ),
        );

        $taxonomies['togo_trip_types'] = array(
            'post_type'     => 'togo_trip',
            'hierarchical'  => true,
            'show_in_rest'  => false,
            'label'         => esc_html__('Types', 'togo-framework'),
            'singular_name' => esc_html__('Types', 'togo-framework'),
            'rewrite'       => array(
                'slug' => apply_filters('togo_trip_types_slug', $togo_trip_types_slug),
            ),
        );

        $taxonomies['togo_trip_durations'] = array(
            'post_type'     => 'togo_trip',
            'hierarchical'  => true,
            'show_in_rest'  => false,
            'label'         => esc_html__('Durations', 'togo-framework'),
            'singular_name' => esc_html__('Durations', 'togo-framework'),
            'rewrite'       => array(
                'slug' => apply_filters('togo_trip_durations_slug', $togo_trip_durantions_slug),
            ),
        );

        $taxonomies['togo_trip_tod'] = array(
            'post_type'     => 'togo_trip',
            'hierarchical'  => true,
            'show_in_rest'  => false,
            'label'         => esc_html__('Time of day', 'togo-framework'),
            'singular_name' => esc_html__('Time of day', 'togo-framework'),
            'rewrite'       => array(
                'slug' => apply_filters('togo_trip_tod_slug', $togo_trip_tod_slug),
            ),
        );

        $taxonomies['togo_trip_languages'] = array(
            'post_type'     => 'togo_trip',
            'hierarchical'  => true,
            'show_in_rest'  => false,
            'label'         => esc_html__('Languages', 'togo-framework'),
            'singular_name' => esc_html__('Languages', 'togo-framework'),
            'rewrite'       => array(
                'slug' => apply_filters('togo_trip_languages_slug', $togo_trip_languages_slug),
            ),
        );

        $taxonomies['togo_trip_services'] = array(
            'post_type'     => 'togo_trip',
            'hierarchical'  => true,
            'show_in_rest'  => false,
            'label'         => esc_html__('Services', 'togo-framework'),
            'singular_name' => esc_html__('Services', 'togo-framework'),
            'rewrite'       => array(
                'slug' => apply_filters('togo_trip_services_slug', 'togo_trip_services'),
            ),
        );

        return $taxonomies;
    }

    public function register_term_meta($configs)
    {

        $configs['togo-trip-pricing-categories-settings'] = apply_filters('uxper_register_term_meta_togo_trip_pricing_categories', array(
            'name'     => esc_html__('Taxonomy Setting', 'togo-framework'),
            'layout'   => 'horizontal',
            'taxonomy' => array('togo_trip_pricing_categories'),
            'fields'   => array(
                array(
                    'type'   => 'row',
                    'col'    => '6',
                    'fields' => array(
                        array(
                            'title' => __('Min Age', 'togo-framework'),
                            'id'    => 'togo_trip_pricing_categories_min_age',
                            'type'  => 'text',
                            'input_type' => 'number'
                        ),
                        array(
                            'title' => __('Max Age', 'togo-framework'),
                            'id'    => 'togo_trip_pricing_categories_max_age',
                            'type'  => 'text',
                            'input_type' => 'number'
                        ),
                    )
                ),
            )
        ));

        $configs['togo-trip-destinations-settings'] = apply_filters('uxper_register_term_meta_togo_trip_destinations', array(
            'name'     => esc_html__('Taxonomy Setting', 'togo-framework'),
            'layout'   => 'horizontal',
            'taxonomy' => array('togo_trip_destinations'),
            'fields'   => array(
                array(
                    'id'      => 'togo_trip_destinations_thumbnail',
                    'title'   => esc_html__('Thumbnail', 'togo-framework'),
                    'type'    => 'image',
                    'default' => '',
                ),
                array(
                    'id'      => 'togo_trip_destinations_video',
                    'title'   => esc_html__('Video', 'togo-framework'),
                    'type'    => 'text',
                    'default' => '',
                    'input_type' => 'url',
                ),
                array(
                    'id'      => 'togo_trip_destinations_gallery',
                    'title'   => esc_html__('Gallery', 'togo-framework'),
                    'type'    => 'gallery',
                    'default' => '',
                ),
                array(
                    'id'      => 'togo_trip_destinations_description',
                    'title'   => esc_html__('Description', 'togo-framework'),
                    'type'    => 'textarea',
                    'default' => '',
                ),
                array(
                    'id'      => 'togo_trip_destinations_link_01',
                    'title'   => esc_html__('Link 01', 'togo-framework'),
                    'type'    => 'text',
                    'input_type' => 'url',
                    'default' => '',
                ),
                array(
                    'id'      => 'togo_trip_destinations_link_02',
                    'title'   => esc_html__('Link 02', 'togo-framework'),
                    'type'    => 'text',
                    'input_type' => 'url',
                    'default' => '',
                ),
                array(
                    'id' => "togo_trip_destinations_faqs",
                    'type' => 'panel',
                    'title' => esc_html__('FAQs', 'uxper-booking'),
                    'sort' => true,
                    'toggle_default' => false,
                    'fields' => array(
                        array(
                            'title' => __('Question', 'uxper-booking'),
                            'id'    => 'togo_trip_destinations_faqs_question',
                            'type'  => 'text',
                            'panel_title' => true,
                        ),
                        array(
                            'id' => "togo_trip_destinations_faqs_answer",
                            'title' => esc_html__('Answer', 'uxper-booking'),
                            'type' => 'editor',
                        ),
                    )
                ),
                array(
                    'id' => "togo_trip_destinations_rates",
                    'type' => 'panel',
                    'title' => esc_html__('Rates', 'uxper-booking'),
                    'sort' => true,
                    'toggle_default' => false,
                    'fields' => array(
                        array(
                            'title' => __('Star', 'uxper-booking'),
                            'id'    => 'togo_trip_destinations_rates_star',
                            'type'  => 'text',
                            'input_type' => 'number',
                            'panel_title' => true,
                        ),
                        array(
                            'id' => "togo_trip_destinations_rates_image",
                            'title' => esc_html__('Image', 'uxper-booking'),
                            'type' => 'image',
                        ),
                        array(
                            'id' => "togo_trip_destinations_rates_content",
                            'title' => esc_html__('Content', 'uxper-booking'),
                            'type' => 'textarea',
                        ),
                        array(
                            'id' => "togo_trip_destinations_rates_name",
                            'title' => esc_html__('Name', 'uxper-booking'),
                            'type' => 'text',
                        ),
                        array(
                            'id' => "togo_trip_destinations_rates_location",
                            'title' => esc_html__('Location', 'uxper-booking'),
                            'type' => 'text',
                        ),
                    )
                ),
            )
        ));

        $configs['togo-trip-services-settings'] = apply_filters('uxper_register_term_meta_togo_trip_services', array(
            'name'     => esc_html__('Taxonomy Setting', 'togo-framework'),
            'layout'   => 'horizontal',
            'taxonomy' => array('togo_trip_services'),
            'fields'   => array(
                array(
                    'type'   => 'row',
                    'col'    => '6',
                    'fields' => array(
                        array(
                            'title' => __('Price', 'togo-framework'),
                            'id'    => 'togo_trip_services_price',
                            'type'  => 'text',
                        ),
                        array(
                            'title'   => __('Suffix Price', 'togo-framework'),
                            'id'      => 'togo_trip_services_suffix_price',
                            'type'    => 'select',
                            'options' => array(
                                'person' => esc_html__('Per Person', 'togo-framework'),
                                'hour'   => esc_html__('Per Hour', 'togo-framework'),
                                'package'    => esc_html__('Per Package', 'togo-framework'),
                            ),
                            'default' => 'per_person',
                        ),
                    )
                ),
                array(
                    'id'      => 'togo_trip_services_icon',
                    'title'   => esc_html__('Icon Image', 'togo-framework'),
                    'type'    => 'image',
                    'default' => '',
                ),
            )
        ));

        return apply_filters('togo_framework_register_term_meta', $configs);
    }

    // Enqueue media uploader scripts
    public function enqueue_togo_trip_trips()
    {
        wp_enqueue_media();
        wp_enqueue_script('togo_trip_trips', TOGO_FRAMEWORK_DIR . 'assets/js/trips.js', array('jquery'), null, true);
    }
}
