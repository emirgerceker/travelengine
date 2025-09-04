<?php

namespace Togo_Framework\Trip;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Posts
{
    protected static $instance = null;

    public static function instance()
    {
        if (
            is_null(self::$instance)
        ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        add_action('togo_archive_trip_before_open_content', array($this, 'archive_trip_post_title'));
        add_action('togo_after_open_trip_list_header', array($this, 'render_top_filter'));
        add_action('togo_archive_trip_before_open_content', array($this, 'open_wrapper_with_maps'));
        add_action('togo_archive_trip_after_close_content', array($this, 'close_wrapper_with_maps'), 10);
        add_action('togo_archive_trip_after_close_content', array($this, 'render_maps'), 5);
        add_action('togo_archive_trip_after_open_content', array($this, 'render_left_filter'));
        add_action('togo_archive_trip_after_open_content', array($this, 'open_trip_wrapper'));
        add_action('togo_archive_trip_before_close_content', array($this, 'close_trip_wrapper'));
        add_action('pre_get_posts', array($this, 'modify_trip_query'));
        add_action('togo_before_archive_trip_list', array($this, 'top_archive_trip_list'));
        add_action('togo_after_archive_trip_list', array($this, 'pagination'));
        add_action('togo_after_archive_trip_list', array($this, 'display_tour_pagination_info'));
        add_action('togo_archive_trip_after_close_content', array($this, 'render_itinerary_popup'));
    }

    // Function to recursively display terms
    public function trip_display_terms_hierarchy($parent_id, $terms_hierarchy)
    {
        if (isset($terms_hierarchy[$parent_id])) {
            echo '<ul>';
            foreach ($terms_hierarchy[$parent_id] as $term) {
                echo '<li><a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                $this->trip_display_terms_hierarchy($term->term_id, $terms_hierarchy); // Recursive call for children
                echo '</li>';
            }
            echo '</ul>';
        }
    }

    public function archive_trip_post_title()
    {
        $archive_trip_use_template_elementor = \Togo\Helper::setting('archive_trip_use_template_elementor');
        $archive_trip_enable_page_title = \Togo\Helper::setting('archive_trip_enable_page_title');
        $archive_trip_enable_breadcrumb = \Togo\Helper::setting('archive_trip_enable_breadcrumb');
        $archive_trip_post_title_description = \Togo\Helper::setting('archive_trip_post_title_description');
        $archive_trip_post_title_text = \Togo\Helper::setting('archive_trip_post_title_text');
        $archive_trip_post_title_image = \Togo\Helper::setting('archive_trip_post_title_image');
        $archive_trip_post_title_image_is_background = \Togo\Helper::setting('archive_trip_post_title_image_is_background');
        if (empty($archive_trip_enable_page_title) || ($archive_trip_use_template_elementor == 'yes' && is_tax('togo_trip_destinations'))) {
            return;
        }
        if (is_tax('togo_trip_destinations') || is_tax('togo_trip_activities') || is_tax('togo_trip_types') || is_tax('togo_trip_durations') || is_tax('togo_trip_tod') || is_tax('togo_trip_languages')) {
            $archive_trip_post_title_text = get_queried_object()->name . ' ' . esc_html('tours', 'togo-framework');
        }
?>
        <div class="page-title <?php if ($archive_trip_post_title_image_is_background) {
                                    echo 'has-background';
                                } ?>" <?php if ($archive_trip_post_title_image_is_background && !empty($archive_trip_post_title_image)) {
                                            echo 'style="background-image: url(' . esc_url($archive_trip_post_title_image) . ')"';
                                        } ?>>
            <div class="container-fluid">
                <div class="page-title_inner">
                    <div class="page-title_content">
                        <?php
                        if (!empty($archive_trip_enable_breadcrumb)) {
                            echo \Togo_Breadcrumb::breadcrumb();
                        }
                        ?>
                        <?php if (! empty($archive_trip_post_title_text)) { ?>
                            <h1 class="page-title_heading"><?php echo esc_html($archive_trip_post_title_text); ?></h1>
                        <?php } ?>
                        <?php if (! empty($archive_trip_post_title_description)) { ?>
                            <div class="page-title_description"><?php echo esc_html($archive_trip_post_title_description); ?></div>
                        <?php } ?>
                        <?php
                        $archive_trip_top_filter_order = \Togo\Helper::setting('archive_trip_top_filter_order') ? \Togo\Helper::setting('archive_trip_top_filter_order') : array();
                        if ($archive_trip_top_filter_order) {
                        ?>
                            <form action="<?php echo esc_url(\Togo_Framework\Helper::get_current_page_url()); ?>" method="get" class="trip-search-form">
                                <?php
                                $trip_destinations = get_terms(array(
                                    'taxonomy' => 'togo_trip_destinations',
                                    'hide_empty' => false, // Set to true to exclude terms with no posts.
                                ));
                                $location = isset($_GET['location']) ? sanitize_text_field($_GET['location']) : '';
                                $dates = isset($_GET['dates']) ? sanitize_text_field($_GET['dates']) : '';
                                $guests = isset($_GET['guests']) ? sanitize_text_field($_GET['guests']) : '';
                                foreach ($archive_trip_top_filter_order as $item) {
                                    if ($item == 'location') {
                                        if (!empty($trip_destinations) && !is_wp_error($trip_destinations)) {
                                ?>
                                            <div class="form-field field-location">
                                                <div class="field-icon"><?php echo \Togo\Icon::get_svg('location'); ?></div>
                                                <div class="field-location__input">
                                                    <label for="location"><?php echo esc_html__('Where', 'togo-framework'); ?></label>
                                                    <input type="text" name="location" id="location" placeholder="<?php echo esc_attr__('Where to?', 'togo-framework'); ?>" value="<?php echo esc_attr($location); ?>">
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
                                                        $this->trip_display_terms_hierarchy(0, $terms_hierarchy);
                                                        ?>
                                                        <div class="no-result hide"><?php echo esc_html__('No results', 'togo-framework'); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                    }
                                    if ($item == 'date') {
                                        ?>
                                        <div class="form-field field-dates">
                                            <div class="field-icon"><?php echo \Togo\Icon::get_svg('calendar'); ?></div>
                                            <div class="field-dates__input">
                                                <label for="dates"><?php echo esc_html__('Date', 'togo-framework'); ?></label>
                                                <input type="text" name="dates" id="dates" placeholder="<?php echo esc_attr__('Select dates', 'togo-framework'); ?>" value="<?php echo esc_attr($dates); ?>">
                                            </div>
                                            <a href="#" class="field-dates__remove">
                                                <?php echo \Togo\Icon::get_svg('x'); ?>
                                            </a>
                                            <?php echo \Togo_Framework\Template::render_calendar([], true); ?>
                                        </div>
                                    <?php
                                    }
                                    if ($item == 'guest') {
                                    ?>
                                        <div class="form-field field-guests">
                                            <div class="field-icon"><?php echo \Togo\Icon::get_svg('users-group'); ?></div>
                                            <div class="field-guests__input">
                                                <label for="guests"><?php echo esc_html__('Who', 'togo-framework'); ?></label>
                                                <input type="number" min="0" name="guests" id="guests" placeholder="<?php echo esc_attr__('Number of guests', 'togo-framework'); ?>" value="<?php echo esc_attr($guests); ?>">
                                            </div>
                                            <a href="#" class="field-guests__remove">
                                                <?php echo \Togo\Icon::get_svg('x'); ?>
                                            </a>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                                <button type="submit" class="field-location__button"><?php echo \Togo\Icon::get_svg('search'); ?></button>
                            </form>
                        <?php } ?>
                    </div>
                    <?php if (empty($archive_trip_post_title_image_is_background) && !empty($archive_trip_post_title_image)) {
                        echo '<div class="page-title_image">';
                        echo '<img src="' . esc_url($archive_trip_post_title_image) . '" alt="' . esc_attr($archive_trip_post_title_text) . '">';
                        echo '</div>';
                    } ?>
                </div>
            </div>
        </div>
<?php
    }

    public function render_left_filter()
    {
        $archive_trip_filter_layout = apply_filters('togo_archive_trip_filter_layout', \Togo\Helper::setting('archive_trip_filter_layout'));
        $archive_trip_use_template_elementor = \Togo\Helper::setting('archive_trip_use_template_elementor');
        if ($archive_trip_use_template_elementor === 'yes' && is_tax('togo_trip_destinations') || is_tax('togo_trip_activities') || is_tax('togo_trip_types') || is_tax('togo_trip_durations') || is_tax('togo_trip_tod') || is_tax('togo_trip_languages')) {
            $archive_trip_filter_layout = 'top_filter';
        }
        $max_price = \Togo_Framework\Helper::get_max_price_all_trips();
        $max_price = intval($max_price);
        $archive_trip_filter_default = \Togo\Helper::setting('archive_trip_filter_default');
        $archive_trip_filter_open_first = \Togo\Helper::setting('archive_trip_filter_open_first');
        $archive_trip_filter_order = \Togo\Helper::setting('archive_trip_filter_order');
        if ($archive_trip_filter_default == 'close') {
            $icon = \Togo\Icon::get_svg('chevron-down');
        } else {
            $icon = \Togo\Icon::get_svg('chevron-up');
        }
        if (empty($archive_trip_filter_order) || $archive_trip_filter_layout != 'left_filter') {
            return;
        }
        echo '<form method="GET" action="' . esc_url(\Togo_Framework\Helper::get_current_page_url()) . '" class="togo-trip-filter layout-left ' . $archive_trip_filter_default . '">';
        $get_min_price = isset($_GET['min_price']) ? intval($_GET['min_price']) : 0;
        $get_max_price = isset($_GET['max_price']) ? intval($_GET['max_price']) : $max_price;
        foreach ($archive_trip_filter_order as $item) {
            if ($item == 'price') {
                echo '<div class="filter-item filter-price ' . $archive_trip_filter_open_first . '">';
                echo '<div class="filter-item__top">';
                echo '<h4>' . esc_html__('Price range', 'togo-framework') . '</h4>';
                if ($archive_trip_filter_default == 'close') {
                    if ($archive_trip_filter_open_first == 'yes') {
                        echo \Togo\Icon::get_svg('chevron-up');
                    } else {
                        echo \Togo\Icon::get_svg('chevron-down');
                    }
                } else {
                    echo \Togo\Icon::get_svg('chevron-up');
                }
                echo '</div>';
                echo '<div class="filter-item__content">';
                echo '<div class="range-slider">';
                echo '<span class="full-range"></span>';
                echo '<span class="incl-range"></span>';
                echo '<input name="min_price" value="' . $get_min_price . '" min="0" max="' . $max_price . '" step="1" type="range">';
                echo '<input name="max_price" value="' . $get_max_price . '" min="0" max="' . $max_price . '" step="1" type="range">';
                echo '</div>';
                echo '<div class="range-preview">';
                echo '<div class="min-price">';
                echo '<span>' . esc_html__('Min. price', 'togo-framework') . '</span>';
                echo '<span class="show-min-price">' . \Togo_Framework\Helper::togo_format_price($get_min_price) . '</span>';
                echo '</div>';
                echo '<div class="max-price">';
                echo '<span>' . esc_html__('Max. price', 'togo-framework') . '</span>';
                echo '<span class="show-max-price">' . \Togo_Framework\Helper::togo_format_price($get_max_price) . '</span>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            } else {
                echo '<div class="filter-item">';
                echo '<div class="filter-item__top">';
                $args = array(
                    'taxonomy' => 'togo_trip_' . $item,
                    'hide_empty' => false,
                );
                if ($item == 'tod') {
                    echo '<h4>' . esc_html__('Time of Day', 'togo-framework') . '</h4>';
                    $args['orderby'] = 'name';
                    $args['order'] = 'DESC';
                } else {
                    echo '<h4>' . esc_html__('Tour', 'togo-framework') . ' ' . esc_html(ucfirst($item)) . '</h4>';
                }
                echo $icon;
                echo '</div>';
                echo '<div class="filter-item__content">';
                $terms = get_terms($args);
                if (!empty($terms) && !is_wp_error($terms)) {
                    $types = isset($_GET[$item]) ? $_GET[$item] : array();
                    foreach ($terms as $key => $term) {
                        if ($key >= 5) {
                            echo '<div class="filter-checkbox hide">';
                        } else {
                            echo '<div class="filter-checkbox">';
                        }
                        echo '<label for="' . $item . '_' . $term->slug . '">';
                        echo '<input type="checkbox" name="' . $item . '[]" id="' . $item . '_' . $term->slug . '" value="' . $term->term_id . '" ' . (in_array($term->term_id, $types) ? 'checked' : '') . '>';
                        echo \Togo\Icon::get_svg('square', 'square');
                        echo \Togo\Icon::get_svg('check-square', 'check-square');
                        echo '<span class="label">' . $term->name . '</span>';
                        echo '</label>';
                        echo '</div>';
                    }
                    if (count($terms) > 5) {
                        echo '<div class="filter-type show-more">';
                        echo '<a href="#">' . esc_html__('Show more', 'togo-framework') . '</a>';
                        echo \Togo\Icon::get_svg('chevron-down');
                        echo '</div>';
                    }
                }
                echo '</div>';
                echo '</div>';
            }
        }
        if (isset($_GET['location']) && $_GET['location'] != '') {
            echo '<input type="hidden" name="location" value="' . $_GET['location'] . '">';
        }
        if (isset($_GET['dates']) && $_GET['dates'] != '') {
            echo '<input type="hidden" name="dates" value="' . $_GET['dates'] . '">';
        }
        echo '</form>';
    }

    public function open_trip_wrapper()
    {
        echo '<div class="trip-wrapper">';
    }

    public function close_trip_wrapper()
    {
        echo '</div>';
    }

    public function render_top_filter()
    {
        $archive_trip_filter_layout = apply_filters('togo_archive_trip_filter_layout', \Togo\Helper::setting('archive_trip_filter_layout'));
        $archive_trip_use_template_elementor = \Togo\Helper::setting('archive_trip_use_template_elementor');
        if ($archive_trip_use_template_elementor === 'yes' && is_tax('togo_trip_destinations') || is_tax('togo_trip_activities') || is_tax('togo_trip_types') || is_tax('togo_trip_durations') || is_tax('togo_trip_tod') || is_tax('togo_trip_languages')) {
            $archive_trip_filter_layout = 'top_filter';
        }
        $max_price = \Togo_Framework\Helper::get_max_price_all_trips();
        $max_price = intval($max_price);
        $archive_trip_filter_default = \Togo\Helper::setting('archive_trip_filter_default');
        $archive_trip_filter_open_first = \Togo\Helper::setting('archive_trip_filter_open_first');
        $archive_trip_filter_order = \Togo\Helper::setting('archive_trip_filter_order');
        $archive_trip_number_items_preview = \Togo\Helper::setting('archive_trip_number_items_preview');
        $get_min_price = isset($_GET['min_price']) ? intval($_GET['min_price']) : 0;
        $get_max_price = isset($_GET['max_price']) ? intval($_GET['max_price']) : $max_price;
        if ($archive_trip_filter_default == 'close') {
            $icon = \Togo\Icon::get_svg('chevron-down');
        } else {
            $icon = \Togo\Icon::get_svg('chevron-up');
        }

        if (empty($archive_trip_filter_order)) {
            return;
        }
        if ($archive_trip_number_items_preview > 0) {
            echo '<form method="GET" action="' . esc_url(\Togo_Framework\Helper::get_current_page_url()) . '" class="togo-trip-filter layout-top">';

            if (isset($_GET['min_price']) && $_GET['max_price']) {
                $price_active = 'active';
            } else {
                $price_active = '';
            }
            foreach ($archive_trip_filter_order as $k => $item) {
                if ($k >= $archive_trip_number_items_preview) {
                    break;
                }
                if (isset($_GET[$item]) && $_GET[$item]) {
                    $item_active = 'active';
                } else {
                    $item_active = '';
                }
                if ($item == 'price') {
                    echo '<div class="filter-item filter-price ' . $price_active . ' togo-select">';
                    echo '<div class="filter-item__select togo-select__label">';
                    echo '<span>' . esc_html__('Price', 'togo-framework') . '</span>';
                    echo \Togo\Icon::get_svg('chevron-down');
                    echo '</div>';
                    echo '<div class="filter-item__content togo-select__content">';
                    echo '<div class="range-slider">';
                    echo '<span class="full-range"></span>';
                    echo '<span class="incl-range"></span>';
                    echo '<input name="min_price" value="' . $get_min_price . '" min="0" max="' . $max_price . '" step="1" type="range">';
                    echo '<input name="max_price" value="' . $get_max_price . '" min="0" max="' . $max_price . '" step="1" type="range">';
                    echo '</div>';
                    echo '<div class="range-preview">';
                    echo '<div class="min-price">';
                    echo '<span>' . esc_html__('Min. price', 'togo-framework') . '</span>';
                    echo '<span class="show-min-price">' . \Togo_Framework\Helper::togo_format_price($get_min_price) . '</span>';
                    echo '</div>';
                    echo '<div class="max-price">';
                    echo '<span>' . esc_html__('Max. price', 'togo-framework') . '</span>';
                    echo '<span class="show-max-price">' . \Togo_Framework\Helper::togo_format_price($get_max_price) . '</span>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<div class="filter-item togo-select ' . $item_active . '">';
                    echo '<div class="filter-item__select togo-select__label">';
                    $args = array(
                        'taxonomy' => 'togo_trip_' . $item,
                        'hide_empty' => false,
                    );
                    if ($item == 'tod') {
                        echo '<span>' . esc_html__('Time of Day', 'togo-framework') . '</span>';
                        $args['orderby'] = 'name';
                        $args['order'] = 'DESC';
                    } else {
                        echo '<span>' . esc_html(ucfirst($item)) . '</span>';
                    }
                    echo \Togo\Icon::get_svg('chevron-down');
                    echo '</div>';
                    echo '<div class="filter-item__content togo-select__content">';
                    $terms = get_terms($args);
                    if (!empty($terms) && !is_wp_error($terms)) {
                        $types = isset($_GET[$item]) ? $_GET[$item] : array();
                        foreach ($terms as $key => $term) {
                            if ($key >= 5) {
                                echo '<div class="filter-checkbox hide">';
                            } else {
                                echo '<div class="filter-checkbox">';
                            }
                            echo '<label for="' . $item . '_' . $term->term_id . '">';
                            echo '<input type="checkbox" name="' . $item . '[]" id="' . $item . '_' . $term->term_id . '" value="' . $term->term_id . '" ' . (in_array($term->term_id, $types) ? 'checked' : '') . '>';
                            echo \Togo\Icon::get_svg('square', 'square');
                            echo \Togo\Icon::get_svg('check-square', 'check-square');
                            echo '<span class="label">' . $term->name . '</span>';
                            echo '</label>';
                            echo '</div>';
                        }
                        if (count($terms) > 5) {
                            echo '<div class="filter-type show-more">';
                            echo '<a href="#">' . esc_html__('Show more', 'togo-framework') . '</a>';
                            echo \Togo\Icon::get_svg('chevron-down');
                            echo '</div>';
                        }
                    }
                    echo '</div>';
                    echo '</div>';
                }
            }
            if (isset($_GET['location']) && $_GET['location'] != '') {
                echo '<input type="hidden" name="location" value="' . $_GET['location'] . '">';
            }
            if (isset($_GET['dates']) && $_GET['dates'] != '') {
                echo '<input type="hidden" name="dates" value="' . $_GET['dates'] . '">';
            }
            echo '</form>';
        }
        if (isset($_GET) && $_GET) {
            $filter_canvas_active = 'active';
        } else {
            $filter_canvas_active = '';
        }
        echo '<div class="open-filter-canvas ' . $filter_canvas_active . '">';
        echo \Togo\Icon::get_svg('filter');
        echo '<span>' . esc_html__('Filter', 'togo-framework') . '</span>';
        echo '</div>';
        echo '<div class="filter-canvas-wrapper">';
        echo '<div class="filter-canvas-overlay"></div>';
        echo '<form method="GET" action="' . esc_url(\Togo_Framework\Helper::get_current_page_url()) . '" class="togo-trip-filter layout-canvas ' . $archive_trip_filter_default . '">';
        foreach ($archive_trip_filter_order as $item) {
            if ($item == 'price') {
                echo '<div class="filter-item filter-price ' . $archive_trip_filter_open_first . '">';
                echo '<div class="filter-item__top">';
                echo '<h4>' . esc_html__('Price range', 'togo-framework') . '</h4>';
                if ($archive_trip_filter_default == 'close') {
                    if ($archive_trip_filter_open_first == 'yes') {
                        echo \Togo\Icon::get_svg('chevron-up');
                    } else {
                        echo \Togo\Icon::get_svg('chevron-down');
                    }
                } else {
                    echo \Togo\Icon::get_svg('chevron-up');
                }
                echo '</div>';
                echo '<div class="filter-item__content">';
                echo '<div class="range-slider">';
                echo '<span class="full-range"></span>';
                echo '<span class="incl-range"></span>';
                echo '<input name="min_price" value="' . $get_min_price . '" min="0" max="' . $max_price . '" step="1" type="range">';
                echo '<input name="max_price" value="' . $get_max_price . '" min="0" max="' . $max_price . '" step="1" type="range">';
                echo '</div>';
                echo '<div class="range-preview">';
                echo '<div class="min-price">';
                echo '<span>' . esc_html__('Min. price', 'togo-framework') . '</span>';
                echo '<span class="show-min-price">' . \Togo_Framework\Helper::togo_format_price($get_min_price) . '</span>';
                echo '</div>';
                echo '<div class="max-price">';
                echo '<span>' . esc_html__('Max. price', 'togo-framework') . '</span>';
                echo '<span class="show-max-price">' . \Togo_Framework\Helper::togo_format_price($get_max_price) . '</span>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            } else {
                echo '<div class="filter-item">';
                echo '<div class="filter-item__top">';
                $args = array(
                    'taxonomy' => 'togo_trip_' . $item,
                    'hide_empty' => false,
                );
                if ($item == 'tod') {
                    echo '<h4>' . esc_html__('Time of Day', 'togo-framework') . '</h4>';
                    $args['orderby'] = 'name';
                    $args['order'] = 'DESC';
                } else {
                    echo '<h4>' . esc_html__('Tour', 'togo-framework') . ' ' . esc_html(ucfirst($item)) . '</h4>';
                }
                echo $icon;
                echo '</div>';
                echo '<div class="filter-item__content">';
                $terms = get_terms($args);
                if (!empty($terms) && !is_wp_error($terms)) {
                    $types = isset($_GET[$item]) ? $_GET[$item] : array();
                    foreach ($terms as $key => $term) {
                        if ($key >= 5) {
                            echo '<div class="filter-checkbox hide">';
                        } else {
                            echo '<div class="filter-checkbox">';
                        }
                        echo '<label for="' . $item . '_' . $term->term_id . '">';
                        echo '<input type="checkbox" name="' . $item . '[]" id="' . $item . '_' . $term->term_id . '" value="' . $term->term_id . '" ' . (in_array($term->term_id, $types) ? 'checked' : '') . '>';
                        echo \Togo\Icon::get_svg('square', 'square');
                        echo \Togo\Icon::get_svg('check-square', 'check-square');
                        echo '<span class="label">' . $term->name . '</span>';
                        echo '</label>';
                        echo '</div>';
                    }
                    if (count($terms) > 5) {
                        echo '<div class="filter-type show-more">';
                        echo '<a href="#">' . esc_html__('Show more', 'togo-framework') . '</a>';
                        echo \Togo\Icon::get_svg('chevron-down');
                        echo '</div>';
                    }
                }
                echo '</div>';
                echo '</div>';
            }
        }
        if (isset($_GET['location']) && $_GET['location'] != '') {
            echo '<input type="hidden" name="location" value="' . $_GET['location'] . '">';
        }
        if (isset($_GET['dates']) && $_GET['dates'] != '') {
            echo '<input type="hidden" name="dates" value="' . $_GET['dates'] . '">';
        }
        echo '</form>';
        echo '</div>';
    }

    public function open_wrapper_with_maps()
    {
        $archive_trip_enable_maps = apply_filters('togo_archive_trip_enable_maps', \Togo\Helper::setting('archive_trip_enable_maps'));
        if (empty($archive_trip_enable_maps) || $archive_trip_enable_maps == 'no') {
            return;
        }
        echo '<div class="trip-wrapper-content with-maps">';
    }

    public function close_wrapper_with_maps()
    {
        $archive_trip_enable_maps = apply_filters('togo_archive_trip_enable_maps', \Togo\Helper::setting('archive_trip_enable_maps'));
        if (empty($archive_trip_enable_maps) || $archive_trip_enable_maps == 'no') {
            return;
        }
        echo '</div>';
    }

    public function render_maps()
    {
        echo \Togo_Framework\Template::render_maps();
    }

    public function modify_trip_query($query)
    {
        // Ensure we're not in the admin area and it's the main query
        if (! is_admin() && $query->is_main_query()) {
            // Check if the query is for the 'trip' custom post type and taxonomy togo_trip_destinations
            if (isset($query->query_vars['post_type']) && $query->query_vars['post_type'] === 'togo_trip' || isset($query->query_vars['togo_trip_destinations']) || isset($query->query_vars['togo_trip_activities']) || isset($query->query_vars['togo_trip_types']) || isset($query->query_vars['togo_trip_durations']) || isset($query->query_vars['togo_trip_tod']) || isset($query->query_vars['togo_trip_languages'])) {
                $types = isset($_GET['types']) ? $_GET['types'] : array();
                $activities = isset($_GET['activities']) ? $_GET['activities'] : array();
                $durations = isset($_GET['durations']) ? $_GET['durations'] : array();
                $tod = isset($_GET['tod']) ? $_GET['tod'] : array();
                $languages = isset($_GET['languages']) ? $_GET['languages'] : array();
                $min_price = isset($_GET['min_price']) ? intval($_GET['min_price']) : 0;
                $max_price = isset($_GET['max_price']) ? intval($_GET['max_price']) : 0;
                $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : 'date';
                $location = isset($_GET['location']) ? $_GET['location'] : '';
                $dates = isset($_GET['dates']) ? explode(',', $_GET['dates']) : array();
                $guests = isset($_GET['guests']) ? intval($_GET['guests']) : 0;

                if (!empty($dates) && $dates[0] != '') {
                    $date_query = array('relation' => 'OR');
                    if (count($dates) === 2) {
                        $dates_between = \Togo_Framework\Helper::get_dates_between($dates[0], $dates[1]);
                        foreach ($dates_between as $key => $date) {
                            $date_query[] = array(
                                'key' => 'trip_dates_availability',
                                'value' => serialize(
                                    $date
                                ),
                                'compare' => 'LIKE',
                            );
                        }
                    } else {
                        $date_query[] = array(
                            'key' => 'trip_dates_availability',
                            'value' => serialize(
                                $dates[0]
                            ),
                            'compare' => 'LIKE',
                        );
                    }

                    $meta_query[] = $date_query;
                }

                if ($guests > 0) {
                    $meta_query[] = array(
                        'key' => 'trip_maximum_guests',
                        'value' => $guests,
                        'type' => 'numeric',
                        'compare' => '>=',
                    );
                }

                if ($min_price > 0 || $max_price > 0) {
                    $meta_query[] = array(
                        'key' => 'togo_trip_price',
                        'value' => array($min_price, $max_price),
                        'type' => 'numeric',
                        'compare' => 'BETWEEN',
                    );
                }

                $tax_query = array();

                if (!empty($location)) {
                    $tax_query[] = array(
                        'taxonomy' => 'togo_trip_destinations',
                        'field' => 'slug',
                        'terms' => $location
                    );
                }

                if (!empty($types)) {
                    $tax_query[] = array(
                        'taxonomy' => 'togo_trip_types',
                        'field' => 'term_id',
                        'terms' => $types,
                    );
                }

                if (!empty($activities)) {
                    $tax_query[] = array(
                        'taxonomy' => 'togo_trip_activities',
                        'field' => 'term_id',
                        'terms' => $activities,
                    );
                }

                if (!empty($durations)) {
                    $tax_query[] = array(
                        'taxonomy' => 'togo_trip_durations',
                        'field' => 'term_id',
                        'terms' => $durations,
                    );
                }

                if (!empty($tod)) {
                    $tax_query[] = array(
                        'taxonomy' => 'togo_trip_tod',
                        'field' => 'term_id',
                        'terms' => $tod,
                    );
                }

                if (!empty($languages)) {
                    $tax_query[] = array(
                        'taxonomy' => 'togo_trip_languages',
                        'field' => 'term_id',
                        'terms' => $languages,
                    );
                }

                if ($orderby === 'price-asc') {
                    $query->set('meta_key', 'togo_trip_price');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', 'ASC');
                } elseif ($orderby === 'price-desc') {
                    $query->set('meta_key', 'togo_trip_price');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', 'DESC');
                } elseif ($orderby === 'title-asc') {
                    $query->set('orderby', 'title');
                    $query->set('order', 'ASC');
                } elseif ($orderby === 'title-desc') {
                    $query->set('orderby', 'title');
                    $query->set('order', 'DESC');
                }

                if (!empty($tax_query)) {
                    $query->set('tax_query', $tax_query);
                }

                if (!empty($meta_query)) {
                    $query->set('meta_query', $meta_query);
                }

                // Hook into 'pre_get_posts' to update the total posts after query
                add_action('wp', function () use ($query) {
                    if ($query->is_main_query() && $query->query_vars['post_type'] === 'togo_trip' || isset($query->query_vars['togo_trip_destinations']) || isset($query->query_vars['togo_trip_activities']) || isset($query->query_vars['togo_trip_types']) || isset($query->query_vars['togo_trip_durations']) || isset($query->query_vars['togo_trip_tod']) || isset($query->query_vars['togo_trip_languages'])) {
                        $total_posts = $query->found_posts;
                        update_option('togo_total_posts', $total_posts);
                        // Get post ID from $query
                        $ids = array();
                        if ($query->have_posts()) {
                            while ($query->have_posts()) {
                                $query->the_post();
                                $ids[] = get_the_ID();
                            }
                        }
                        update_option('togo_trip_ids', $ids);
                    }
                });
            }
        }
    }

    public function top_archive_trip_list()
    {
        global $wp_query;
        // Get the current URL with query string
        $current_url = \Togo_Framework\Helper::get_current_page_url();
        $current_url_without_query = \Togo_Framework\Helper::get_current_page_url(false);

        // Count posts for the 'togo_trip' post type
        $total_posts = $wp_query->found_posts;

        // Determine the label for "tour" or "tours"
        $tour_label = ($total_posts === 1) ? esc_html__('tour', 'togo-framework') : esc_html__('tours', 'togo-framework');

        if (isset($_GET['orderby']) && $_GET['orderby'] != '') {
            $orderby_active = 'active';
        } else {
            $orderby_active = '';
        }

        // If there are trips, display the header
        echo '<div class="trip-list-header">';
        do_action('togo_after_open_trip_list_header');
        echo '<span class="trip-list-header__count">' . sprintf(esc_html__('%d %s found', 'togo-framework'), $total_posts, $tour_label) . '</span>';

        // Display the "Clear Filter" link if query parameters are present
        if (!empty($_GET)) {
            echo '<a href="' . esc_url($current_url_without_query) . '" class="trip-list-header__clear-filter">' . esc_html__('Clear filter', 'togo-framework') . '</a>';
        }

        echo '<div class="trip-list-header__sort togo-select ' . $orderby_active . '">';
        echo '<div class="trip-list-header__sort-label togo-select__label">';
        echo '<span class="trip-list-header__sort-text">' . esc_html__('Sort by', 'togo-framework') . '</span>';
        echo \Togo\Icon::get_svg('chevron-down');
        echo '</div>';
        // Sorting options list
        echo '<ul class="trip-list-header__sort-list togo-select__content">';
        $sorting_options = [
            'date'        => esc_html__('Newest', 'togo-framework'),
            'price-asc'   => esc_html__('Price: Low to High', 'togo-framework'),
            'price-desc'  => esc_html__('Price: High to Low', 'togo-framework'),
            'title-asc'   => esc_html__('Title: A to Z', 'togo-framework'),
            'title-desc'  => esc_html__('Title: Z to A', 'togo-framework'),
        ];

        foreach ($sorting_options as $key => $label) {
            $active_class = (isset($_GET['orderby']) && $_GET['orderby'] === $key) ? ' class="active"' : '';
            echo '<li' . $active_class . '>';
            echo '<a href="' . esc_url(add_query_arg('orderby', $key, $current_url)) . '">';
            if ($active_class) {
                echo \Togo\Icon::get_svg('check', 'check-square');
            }
            echo $label;
            echo '</a>';
            echo '</li>';
        }

        echo '</ul>'; // Close trip-list-header__sort-list
        echo '</div>'; // Close trip-list-header__sort

        echo '</div>'; // Close trip-list-header
    }

    public function pagination()
    {
        $archive_trip_pagination_align = \Togo\Helper::setting('archive_trip_pagination_align');
        echo \Togo_Framework\Template::pagination($archive_trip_pagination_align);
    }

    public function display_tour_pagination_info()
    {
        $archive_trip_pagination_align = \Togo\Helper::setting('archive_trip_pagination_align');
        $archive_trip_pagination_show_info = \Togo\Helper::setting('archive_trip_pagination_show_info');
        if (empty($archive_trip_pagination_show_info)) {
            return;
        }
        echo \Togo_Framework\Template::display_tour_pagination_info($archive_trip_pagination_align,);
    }

    public function render_itinerary_popup()
    {
        echo \Togo_Framework\Template::render_itinerary_popup();
    }
}
