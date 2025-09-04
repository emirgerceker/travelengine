<?php

namespace Togo_Framework;

if (!defined('ABSPATH')) {
    exit;
}

class Import
{
    /**
     * Singleton instance
     *
     * @var self
     */
    private static $instance;

    /**
     * Get singleton instance
     *
     * @return self
     */
    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor - hook into One Click Demo Import
     */
    public function __construct()
    {
        add_filter('ocdi/import_files', [$this, 'togo_import_files']);
        add_action('ocdi/before_content_import', [$this, 'togo_before_content_import']);
        add_action('ocdi/after_import', [$this, 'togo_after_import_setup']);
        add_action('togo_regenerate_thumbnails', [$this, 'regenerate_thumbnails_handler']);
    }

    /**
     * Define demo import files and metadata
     *
     * @return array
     */
    public function togo_import_files()
    {
        return [
            [
                'import_file_name'           => 'Togo',
                'import_file_url'            => TOGO_FRAMEWORK_DIR . 'assets/import/01/content.xml',
                'import_widget_file_url'     => TOGO_FRAMEWORK_DIR . 'assets/import/01/widgets.json',
                'import_customizer_file_url' => TOGO_FRAMEWORK_DIR . 'assets/import/01/customizer.dat',
                'import_preview_image_url'   => TOGO_FRAMEWORK_DIR . 'assets/import/01/preview.png',
                'import_notice'              => __('After you import this demo, you will have to setup the slider separately.', 'togo-framework'),
                'preview_url'                => 'https://togo.uxper.co/',
            ],
        ];
    }

    /**
     * Disable generating image sizes to speed up import
     */
    public function togo_before_content_import()
    {
        add_filter('intermediate_image_sizes_advanced', '__return_empty_array');
    }

    /**
     * Tasks to run after import finishes
     */
    public function togo_after_import_setup()
    {
        $this->set_pages();
        $this->set_menus();
        $this->set_elementor_settings();
        $this->set_default_kit();
        $this->remove_filter();
        $this->schedule_thumbnail_regeneration();
        $this->delete_hello_world_post();
        $this->elementor_cpt_support();
    }

    /**
     * Set homepage and blog page
     */
    public function set_pages()
    {
        $front_page = get_page_by_title('Home 01');
        $blog_page = get_page_by_title('a Blog page');

        if ($front_page && $blog_page) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $front_page->ID);
            update_option('page_for_posts', $blog_page->ID);
        }
    }

    /**
     * Assign menus to theme locations
     */
    public function set_menus()
    {
        $menus = [
            'main_menu'       => get_term_by('name', 'Main Menu', 'nav_menu'),
            'mobile_menu'     => get_term_by('name', 'Mobile Menu', 'nav_menu'),
            'my_account_menu' => get_term_by('name', 'My Account Menu', 'nav_menu'),
            'landing_menu'    => get_term_by('name', 'Landing Menu', 'nav_menu'),
        ];

        $menu_locations = [];

        foreach ($menus as $location => $term) {
            if ($term && !is_wp_error($term)) {
                $menu_locations[$location] = $term->term_id;
            }
        }

        if (!empty($menu_locations)) {
            set_theme_mod('nav_menu_locations', $menu_locations);
        }
    }

    /**
     * Import and apply Elementor global settings from JSON
     */
    public function set_elementor_settings()
    {
        $elementor_settings = TOGO_FRAMEWORK_PATH . 'assets/import/01/elementor.txt';

        if (file_exists($elementor_settings)) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
            global $wp_filesystem;

            $file_content = $wp_filesystem->get_contents($elementor_settings);

            if ($file_content !== false) {
                $settings = json_decode($file_content, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($settings)) {
                    foreach ($settings as $option => $value) {
                        update_option($option, $value);
                    }
                } else {
                    error_log('Togo Framework: Invalid JSON format in elementor.txt');
                }
            } else {
                error_log('Togo Framework: Failed to read elementor.txt');
            }
        } else {
            error_log('Togo Framework: File not found: ' . $elementor_settings);
        }
    }

    /**
     * Set Elementor default kit (skipping ID = 5 if exists)
     */
    public function set_default_kit()
    {
        global $wpdb;

        $kit_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT ID FROM $wpdb->posts WHERE post_type = %s AND post_status = %s AND post_title = %s",
                'elementor_library',
                'publish',
                'Default Kit'
            )
        );

        $kit_id = null;
        if (!empty($kit_ids)) {
            foreach ($kit_ids as $id) {
                if ((int) $id === 5) {
                    continue;
                }
                $kit_id = $id;
                break;
            }
        }

        if ($kit_id) {
            update_option('elementor_active_kit', $kit_id);
        }

        if (class_exists('\Elementor\Plugin')) {
            \Elementor\Plugin::$instance->files_manager->clear_cache();
            \Elementor\Plugin::$instance->posts_css_manager->clear_cache();

            if (method_exists(\Elementor\Plugin::$instance->kits_manager, 'refresh_kit_data')) {
                \Elementor\Plugin::$instance->kits_manager->refresh_kit_data();
            }
        }
    }

    /**
     * Remove temporary filters after import
     */
    public function remove_filter()
    {
        remove_filter('intermediate_image_sizes_advanced', '__return_empty_array');
    }

    /**
     * Schedule thumbnail regeneration after import
     */
    public function schedule_thumbnail_regeneration()
    {
        if (!wp_next_scheduled('togo_regenerate_thumbnails')) {
            wp_schedule_single_event(time() + 10, 'togo_regenerate_thumbnails');
        }
    }

    /**
     * Regenerate image thumbnails
     */
    public function regenerate_thumbnails_handler()
    {
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $attachments = get_posts([
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'numberposts'    => -1,
        ]);

        foreach ($attachments as $attachment) {
            $fullsizepath = get_attached_file($attachment->ID);
            if ($fullsizepath && file_exists($fullsizepath)) {
                $metadata = wp_generate_attachment_metadata($attachment->ID, $fullsizepath);
                if (!empty($metadata)) {
                    wp_update_attachment_metadata($attachment->ID, $metadata);
                }
            }
        }
    }

    /**
     * Delete the default "Hello World" post
     */
    public function delete_hello_world_post()
    {
        $post = get_page_by_title('Hello World', OBJECT, 'post');

        if ($post && $post->post_status !== 'trash') {
            wp_delete_post($post->ID, true);
        }
    }

    public function elementor_cpt_support()
    {
        // Enable post types for Elementor
        $cpt_support = get_option('elementor_cpt_support');

        // Initialize as an empty array if the option is not set
        if (!is_array($cpt_support)) {
            $cpt_support = [];
        }

        // Add the required custom post types
        $custom_post_types = ['post', 'page', 'togo_mega_menu', 'togo_trip', 'togo_top_bar', 'togo_header', 'togo_footer'];

        foreach ($custom_post_types as $cpt) {
            if (!in_array($cpt, $cpt_support)) {
                $cpt_support[] = $cpt;
            }
        }

        // Update the Elementor supported post types option
        update_option('elementor_cpt_support', $cpt_support);

        // Optional: Flush rewrite rules if needed
        flush_rewrite_rules();
    }
}
