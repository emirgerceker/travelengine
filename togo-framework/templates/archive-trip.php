<?php

/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */
get_header();
wp_enqueue_script('google-map');
$trip_card_layout = apply_filters('togo_trip_card_layout', \Togo\Helper::setting('trip_card_layout'));
if ($trip_card_layout == 'grid') {
    $column_classes = array('togo-row');
    if ($val = \Togo\Helper::setting('archive_trip_columns_xl')) {
        $column_classes[] = 'togo-row-cols-xl-' . $val;
    }
    if ($val = \Togo\Helper::setting('archive_trip_columns_lg')) {
        $column_classes[] = 'togo-row-cols-lg-' . $val;
    }
    if ($val = \Togo\Helper::setting('archive_trip_columns_md')) {
        $column_classes[] = 'togo-row-cols-md-' . $val;
    }
    if ($val = \Togo\Helper::setting('archive_trip_columns_sm')) {
        $column_classes[] = 'togo-row-cols-sm-' . $val;
    }
    if ($val = \Togo\Helper::setting('archive_trip_columns_xs')) {
        $column_classes[] = 'togo-row-cols-xs-' . $val;
    }
    $column_classes = apply_filters('togo_archive_trip_column_classes', $column_classes);
} else {
    $column_classes = array('togo-row-cols-xl-1', 'togo-row-cols-lg-1', 'togo-row-cols-md-1', 'togo-row-cols-sm-1', 'togo-row-cols-xs-1');
    $column_classes = apply_filters('togo_archive_trip_column_classes', $column_classes);
}
?>

<?php do_action('togo_archive_trip_before_open_content') ?>

<div class="site-content">

    <?php do_action('togo_archive_trip_after_open_content') ?>

    <?php do_action('togo_before_archive_trip_list') ?>

    <div class="trip-list <?php echo implode(' ', $column_classes); ?>">

        <?php
        if (have_posts()) :
            /* Start the Loop */
            while (have_posts()) :
                the_post();

                /*
						* Include the Post-Type-specific template for the content.
						* If you want to override this in a child theme, then include a file
						* called content-___.php (where ___ is the Post Type name) and that will be used instead.
						*/
                \Togo_Framework\Helper::togo_get_template('content-trip.php');

            endwhile;
        else :
            \Togo_Framework\Helper::togo_get_template('content-none.php');
        endif;
        ?>

    </div>

    <?php do_action('togo_after_archive_trip_list') ?>

    <?php do_action('togo_archive_trip_before_close_content') ?>
</div>

<?php do_action('togo_archive_trip_after_close_content') ?>

<?php

get_footer();
