<?php

namespace Togo_Framework;

if (!defined('ABSPATH')) {
    exit;
}

class Capability
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
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor SP_Loader
     * *******************************************************
     */
    public function __construct()
    {
        $this->create_roles();
    }

    public static function create_roles()
    {
        global $wp_roles;

        if (!class_exists('WP_Roles')) {
            return;
        }

        if (!isset($wp_roles)) {
            $wp_roles = new \WP_Roles();
        }

        $capabilities = self::get_core_capabilities();

        // Assign all capabilities to administrator
        foreach ($capabilities as $cap_group) {
            foreach ($cap_group as $cap) {
                $wp_roles->add_cap('administrator', $cap);
            }
        }
    }

    private static function get_core_capabilities()
    {
        $capabilities = array();

        $capabilities['core'] = array(
            'manage_togo_framework',
        );

        $capability_types = array('trip', 'booking', 'review', 'mega_menu', 'header', 'footer', 'top_bar');

        foreach ($capability_types as $capability_type) {
            $capabilities[$capability_type] = array(
                // Post type
                "edit_{$capability_type}",
                "read_{$capability_type}",
                "delete_{$capability_type}",
                "edit_{$capability_type}s",
                "edit_others_{$capability_type}s",
                "publish_{$capability_type}s",
                "read_private_{$capability_type}s",
                "delete_{$capability_type}s",
                "delete_private_{$capability_type}s",
                "delete_published_{$capability_type}s",
                "delete_others_{$capability_type}s",
                "edit_private_{$capability_type}s",
                "edit_published_{$capability_type}s",

                // Terms
                "manage_{$capability_type}_terms",
                "edit_{$capability_type}_terms",
                "delete_{$capability_type}_terms",
                "assign_{$capability_type}_terms"
            );
        }

        return $capabilities;
    }
}
