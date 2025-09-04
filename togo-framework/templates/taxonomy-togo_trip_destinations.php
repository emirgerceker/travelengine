<?php

/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

get_header();

use \Elementor\Plugin;

// Get trip destinations template
$trip_destinations = Togo\Theme::get_list_templates(false, 'elementor_library', 'trip-destinations');

// Get archive_trip_use_template_elementor setting
$archive_trip_use_template_elementor = \Togo\Helper::setting('archive_trip_use_template_elementor');

// Get the ID of the first trip destination template
$trip_destinations_id = key($trip_destinations);

// Check if trip destinations template should not be used
if (empty($trip_destinations) || !is_array($trip_destinations) || $archive_trip_use_template_elementor === 'no') {

    // Determine trip card layout
    $trip_card_layout = apply_filters('togo_trip_card_layout', \Togo\Helper::setting('trip_card_layout'));
    $column_classes = ($trip_card_layout === 'grid') ? ['togo-row'] : [''];

    if ($trip_card_layout === 'grid') {
        $settings = [
            'archive_trip_columns_xl' => 'togo-row-cols-xl-',
            'archive_trip_columns_lg' => 'togo-row-cols-lg-',
            'archive_trip_columns_md' => 'togo-row-cols-md-',
            'archive_trip_columns_sm' => 'togo-row-cols-sm-',
            'archive_trip_columns_xs' => 'togo-row-cols-xs-',
        ];

        foreach ($settings as $key => $class_prefix) {
            $setting_value = \Togo\Helper::setting($key);
            if ($setting_value) {
                $column_classes[] = $class_prefix . $setting_value;
            }
        }
    }
?>

    <?php do_action('togo_archive_trip_before_open_content'); ?>

    <div class="site-content">

        <?php do_action('togo_archive_trip_after_open_content'); ?>

        <?php do_action('togo_before_archive_trip_list'); ?>

        <div class="trip-list <?php echo implode(' ', $column_classes); ?>">

            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php \Togo_Framework\Helper::togo_get_template('content-trip.php'); ?>
                <?php endwhile; ?>
            <?php else : ?>
                <?php \Togo_Framework\Helper::togo_get_template('content-none.php'); ?>
            <?php endif; ?>

        </div>

        <?php do_action('togo_after_archive_trip_list'); ?>

        <?php do_action('togo_archive_trip_before_close_content'); ?>

    </div>

    <?php do_action('togo_archive_trip_after_close_content'); ?>

<?php
}

// Display Elementor template layout if applicable
if (defined('ELEMENTOR_VERSION') && Plugin::$instance->db->is_built_with_elementor($trip_destinations_id)) {
    echo Plugin::$instance->frontend->get_builder_content($trip_destinations_id);
}

get_footer();
