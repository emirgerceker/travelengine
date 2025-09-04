<?php

namespace Togo_Framework;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Helper
 */
class Helper
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
     * Instantiate the object.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct()
    {
        add_action('save_post', array($this, 'handle_trip_save_post'), 10, 3);
    }

    /**
     * Get meta boxes config
     *
     * @since 1.0.0
     *
     * @return array
     */
    public static function &togo_get_meta_boxes_config()
    {
        if (!isset($GLOBALS['togo_trip_meta_box_config'])) {
            $GLOBALS['togo_trip_meta_box_config'] = apply_filters('togo_trip_meta_box_config', array());
        }
        return $GLOBALS['togo_trip_meta_box_config'];
    }

    /**
     * Set config layout
     *
     * @since 1.0.0
     *
     * @param string $value
     *
     * @return void
     */
    public static function togo_set_config_layout($value)
    {
        $GLOBALS['togo_config_layout'] = $value;
    }

    public static function togo_get_field_class_name($field_type)
    {
        $type = str_replace('_', ' ', $field_type);
        $type = ucwords($type);
        $type = str_replace(' ', '_', $type);

        return 'Uxper_Field_' . $type;
    }

    public static function togo_clean($var)
    {
        if (is_array($var)) {
            return array_map('togo_clean', $var);
        } else {
            return is_scalar($var) ? sanitize_text_field($var) : $var;
        }
    }

    public static function togo_get_config_field_keys($fields, $parent_type = '', $section = '')
    {
        $field_keys = array();
        foreach ($fields as $field) {
            if (!isset($field['type'])) {
                continue;
            }

            switch ($field['type']) {
                case 'repeater':
                    if (!isset($field['id'])) {
                        break;
                    }
                    if (($parent_type === 'repeater') || !isset($field['fields'])) {
                        break;
                    }
                    $field_keys[$field['id']] = array(
                        'type' => $field['type'],
                        'clone' => false,
                        'section' => $section,
                        'default' => isset($field['default']) ? $field['default'] : '',
                    );
                    $field_keys = array_merge($field_keys, self::togo_get_config_field_keys($field['fields'], $field['type'], $section));
                    break;
                case 'row':
                case 'group':
                    if (($parent_type === 'repeater') || !isset($field['fields'])) {
                        break;
                    }
                    $field_keys = array_merge($field_keys, self::togo_get_config_field_keys($field['fields'], $field['type'], $section));
                    break;
                default:
                    if (!isset($field['id'])) {
                        break;
                    }
                    $class_field = self::togo_get_field_class_name($field['type']);
                    $field_obj = new $class_field($field, $parent_type);

                    $field_keys[$field['id']] = array(
                        'type' => $field['type'],
                        'clone' => (isset($field['clone']) && $field['clone']) || ($parent_type === 'repeater'),
                        'section' => $section,
                        'default' => $field_obj->get_default(),
                    );
                    break;
            }
        }

        return $field_keys;
    }

    public static function togo_is_edit_page($new_edit = null)
    {
        global $pagenow;
        //make sure we are on the backend
        if (!is_admin()) return false;


        if ($new_edit == "edit")
            return in_array($pagenow, array('post.php',));
        elseif ($new_edit == "new") //check for new post page
            return in_array($pagenow, array('post-new.php'));
        else //check for either new or edit
            return in_array($pagenow, array('post.php', 'post-new.php'));
    }

    public static function get_pricing_categories()
    {
        $pricing_category = array();
        $pricing_categories = get_terms(array(
            'taxonomy'   => 'togo_trip_pricing_categories',
            'hide_empty' => false,
            'orderby'    => 'date',
            'order'      => 'ASC',
        ));

        if (! empty($pricing_categories) && ! is_wp_error($pricing_categories)) {
            foreach ($pricing_categories as $value) {
                $pricing_category[$value->slug] = $value->name;
            }
        }

        return $pricing_category;
    }

    public static function get_all_days()
    {

        $days = array(
            'monday' => __('Monday', 'togo-trip'),
            'tuesday' => __('Tuesday', 'togo-trip'),
            'wednesday' => __('Wednesday', 'togo-trip'),
            'thursday' => __('Thursday', 'togo-trip'),
            'friday' => __('Friday', 'togo-trip'),
            'saturday' => __('Saturday', 'togo-trip'),
            'sunday' => __('Sunday', 'togo-trip'),
        );

        return $days;
    }

    public static function get_day_by_date($date)
    {
        $dayOfWeek = date('l', strtotime($date));

        return $dayOfWeek;
    }

    public static function generate_video_embed_html($url, $poster = '')
    {
        $video_width  = 1024;
        $video_height = 768;
        $video_html = '';

        // If URL is valid: show oEmbed HTML
        if (filter_var($url, FILTER_VALIDATE_URL)) {

            $atts = array(
                'width'  => $video_width,
                'height' => $video_height,
            );

            // Check if the URL can be embedded using oEmbed
            if ($oembed = @wp_oembed_get($url, $atts)) {
                $video_html = $oembed;
            } else {
                // Prepare shortcode attributes
                $atts = array(
                    'src'    => $url,
                    'width'  => $video_width,
                    'height' => $video_height,
                );

                // Add poster if provided
                if (!empty($poster)) {
                    $atts['poster'] = $poster;
                }

                $video_html = wp_video_shortcode($atts);
            }
        }

        return $video_html;
    }

    public static function get_image_caption_by_id($image_id)
    {
        // Get the image post object
        $image_post = get_post($image_id);

        // Check if the post exists and is an attachment
        if ($image_post && 'attachment' === $image_post->post_type) {
            // Return the caption
            return $image_post->post_excerpt;
        }

        return false; // Return false if the image does not exist
    }

    public static function get_image_caption_by_url($image_url)
    {
        // Get the attachment ID from the image URL
        $attachment_id = attachment_url_to_postid($image_url);

        // Check if the attachment ID is valid
        if ($attachment_id) {
            // Get the image post object
            $image_post = get_post($attachment_id);

            // Return the caption if the post exists and is an attachment
            if ($image_post && 'attachment' === $image_post->post_type) {
                return $image_post->post_excerpt; // Caption is stored in post_excerpt
            }
        }

        return false; // Return false if the caption is not found
    }

    public static function get_terms_by_post_id($post_id, $taxonomy)
    {
        // Get all terms for the specified taxonomy associated with the post
        $terms = wp_get_post_terms($post_id, $taxonomy);

        // Check if terms were found and return them
        if (!is_wp_error($terms) && !empty($terms)) {
            return $terms;
        }

        return [];
    }

    public static function check_price_of_trip($trip_id)
    {
        $tour_package = get_post_meta($trip_id, 'tour_package', true);
        if (!empty($tour_package)) {
            $price_type = null;
            $min_regular_price = null;
            $min_sale_price    = null;

            // Get all pricing category slugs for the trip
            $pricing_terms = wp_get_post_terms($trip_id, 'togo_trip_pricing_categories');
            $pricing_slugs = [];
            if (!empty($pricing_terms) && !is_wp_error($pricing_terms)) {
                foreach ($pricing_terms as $term) {
                    $pricing_slugs[] = $term->slug;
                }
            }

            foreach ($tour_package as $package) {
                if (isset($package['schedules']) && is_array($package['schedules'])) {
                    foreach ($package['schedules'] as $schedule) {
                        $price_type = $schedule['price_type'];

                        if ($price_type == 'per_person') {
                            foreach ($pricing_slugs as $slug) {
                                $sale_key    = 'sale_price[' . $slug . ']';
                                $regular_key = 'regular_price[' . $slug . ']';

                                if (isset($schedule[$sale_key]) && is_array($schedule[$sale_key])) {
                                    foreach ($schedule[$sale_key] as $key => $price) {
                                        $numeric_price = intval($price);
                                        if ($numeric_price > 0 && (is_null($min_regular_price) || $numeric_price < $min_regular_price)) {
                                            $min_regular_price = isset($schedule[$regular_key][$key]) ? intval($schedule[$regular_key][$key]) : 0;
                                            $min_sale_price    = $numeric_price;
                                        }
                                    }
                                }

                                if ((is_null($min_regular_price) || $min_sale_price == 0) && isset($schedule[$regular_key]) && is_array($schedule[$regular_key])) {
                                    foreach ($schedule[$regular_key] as $key => $price) {
                                        $numeric_price = intval($price);
                                        if ($numeric_price > 0 && (is_null($min_regular_price) || $numeric_price < $min_regular_price)) {
                                            $min_regular_price = $numeric_price;
                                            $min_sale_price    = isset($schedule[$sale_key][$key]) ? intval($schedule[$sale_key][$key]) : 0;
                                        }
                                    }
                                }
                            }
                        } else {
                            if (isset($schedule['per_group_regular_price']) && is_array($schedule['per_group_regular_price'])) {
                                foreach ($schedule['per_group_regular_price'] as $key => $price) {
                                    $numeric_price = intval($price);
                                    if ($numeric_price > 0 && (is_null($min_regular_price) || $numeric_price < $min_regular_price)) {
                                        $min_regular_price = $numeric_price;
                                        $min_sale_price    = isset($schedule['per_group_sale_price'][$key]) ? intval($schedule['per_group_sale_price'][$key]) : 0;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return [
                'price_type'    => $price_type,
                'regular_price' => $min_regular_price,
                'sale_price'    => $min_sale_price,
            ];
        }
    }

    public static function check_date_availability($trip_id)
    {
        $tour_package = get_post_meta($trip_id, 'tour_package', true);
        $dates = array();
        // Mapping weekday names to numeric values (1 = Monday, ..., 7 = Sunday)
        $weekday_map = [
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
            'Sunday' => 7
        ];

        if (!empty($tour_package)) {
            foreach ($tour_package as $package) {
                if (isset($package['schedules']) && is_array($package['schedules'])) {
                    foreach ($package['schedules'] as $schedule) {
                        $start_date = new \DateTime($schedule['start_date']);
                        if ($schedule['end_date'] == 'no_end_date') {
                            // Add one year to the start date
                            $end_date = (clone $start_date)->modify('+1 year');
                        } else {
                            $end_date = new \DateTime($schedule['end_date']);
                        }
                        $end_date->setTime(23, 59, 59);
                        $day_in_week = $schedule['trip_days'];

                        // Convert weekday names to their numeric representations
                        $weekdays_numeric = array_map(function ($day) use ($weekday_map) {
                            return $weekday_map[ucfirst(strtolower($day))] ?? null; // Make sure the name is correctly formatted
                        }, $day_in_week);

                        // Remove any invalid weekday values (in case of incorrect names)
                        $weekdays_numeric = array_filter($weekdays_numeric);


                        $days = [];

                        // Iterate through the date range
                        while ($start_date <= $end_date) {
                            // Check if the current day is in the specified weekdays
                            if (in_array($start_date->format('N'), $weekdays_numeric)) {
                                $days[] = $start_date->format('Y-m-d');
                            }
                            $start_date->modify('+1 day'); // Move to the next day
                        }
                        $dates[] = $days;
                    }
                }
            }
        }

        $dates = array_merge(...$dates);
        $dates = array_unique($dates);
        sort($dates);

        return $dates;
    }

    public function handle_trip_save_post($post_id, $post, $update)
    {
        // Ensure this runs only for the 'togo_trip' post type
        if ($post->post_type !== 'togo_trip') {
            return;
        }

        // Check if this is an autosave or a revision
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user capabilities (ensure user can edit the post)
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $dates = self::check_date_availability($post_id);

        update_post_meta($post_id, 'trip_dates_availability', $dates);
    }

    public static function get_dates_between($startDate, $endDate)
    {
        $dates = [];
        $currentDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        while ($currentDate <= $endDate) {
            $dates[] = date('Y-m-d', $currentDate);
            $currentDate = strtotime('+1 day', $currentDate);
        }

        return $dates;
    }


    public static function check_price_on_calendar($trip_id)
    {
        $tour_package = get_post_meta($trip_id, 'tour_package', true);
        if (empty($tour_package)) {
            return;
        }

        $calendar_price = [];

        foreach ($tour_package as $package) {
            if (isset($package['schedules']) && is_array($package['schedules'])) {
                foreach ($package['schedules'] as $schedule) {
                    if ($schedule['price_type'] == 'per_person') {
                        $min_price = 0;
                        $pricing_terms = wp_get_post_terms($trip_id, 'togo_trip_pricing_categories');
                        if (!is_wp_error($pricing_terms) && !empty($pricing_terms)) {
                            foreach ($pricing_terms as $term) {
                                $slug       = $term->slug;
                                $sale_key   = 'sale_price[' . $slug . ']';
                                $regular_key = 'regular_price[' . $slug . ']';

                                if (isset($schedule[$sale_key]) && is_array($schedule[$sale_key])) {
                                    $filtered = array_filter($schedule[$sale_key], function ($value) {
                                        return $value !== '' && $value !== null;
                                    });
                                    if (!empty($filtered)) {
                                        $min_term_price = min($filtered);
                                        if ($min_price == 0 || $min_term_price < $min_price) {
                                            $min_price = $min_term_price;
                                        }
                                    }
                                }

                                if ($min_price == 0 && isset($schedule[$regular_key]) && is_array($schedule[$regular_key])) {
                                    $filtered = array_filter($schedule[$regular_key], function ($value) {
                                        return $value !== '' && $value !== null;
                                    });
                                    if (!empty($filtered)) {
                                        $min_term_price = min($filtered);
                                        if ($min_price == 0 || $min_term_price < $min_price) {
                                            $min_price = $min_term_price;
                                        }
                                    }
                                }
                            }
                        }

                        $calendar_price[] = [
                            'start_date'   => $schedule['start_date'],
                            'end_date'     => $schedule['end_date'],
                            'price'        => $min_price,
                            'format_price' => self::togo_format_price($min_price),
                            'trip_days'    => $schedule['trip_days'],
                        ];
                    } else {
                        $min_price = 0;
                        $filtered_sale_price_array = array_filter($schedule['per_group_sale_price'], function ($value) {
                            return $value !== '' && $value !== null;
                        });
                        $filtered_regular_price_array = array_filter($schedule['per_group_regular_price'], function ($value) {
                            return $value !== '' && $value !== null;
                        });
                        if (!empty($filtered_sale_price_array)) {
                            $min_price = min($filtered_sale_price_array);
                        }
                        if ($min_price == 0 && !empty($filtered_regular_price_array)) {
                            $min_price = min($filtered_regular_price_array);
                        }
                        $calendar_price[] = [
                            'start_date' => $schedule['start_date'],
                            'end_date' => $schedule['end_date'],
                            'price' => $min_price,
                            'format_price' => self::togo_format_price($min_price),
                            'trip_days' => $schedule['trip_days'],
                        ];
                    }
                }
            }
        }

        return $calendar_price;
    }

    public static function get_price_of_trip($trip_id, $html = true, $suffix = true)
    {
        $price = self::check_price_of_trip($trip_id);
        if (empty($price)) {
            return;
        }

        $price_type = $price['price_type'];
        $regular_price = $price['regular_price'];
        $sale_price = $price['sale_price'];
        if ($sale_price > 0) {
            update_post_meta($trip_id, 'togo_trip_price', $sale_price);
        } else {
            update_post_meta($trip_id, 'togo_trip_price', $regular_price);
        }
        if ($html) {
            $html = '<div class="trip-price">';
            $html .= '<span class="prefix">' . esc_html__('from', 'togo-framework') . '</span>';
            if ($sale_price > 0) {
                $html .= '<span class="regular-price price"><del>' . self::togo_format_price($regular_price) . '</del></span> <span class="sale-price price"><ins>' . self::togo_format_price($sale_price) . '</ins></span>';
            } else {
                $html .= '<span class="regular-price price">' . self::togo_format_price($regular_price) . '</span>';
            }
            if ($suffix === true) {
                if ($price_type == 'per_person') {
                    $html .= '<span class="suffix">' . ' /' . esc_html__('person', 'togo-framework') . '</span>';
                } else {
                    $html .= '<span class="suffix">' . ' /' . esc_html__('group', 'togo-framework') . '</span>';
                }
            }
            $html .= '</div>';
            return $html;
        } else {
            $result = [
                'regular_price' => $regular_price,
                'sale_price' => $sale_price,
                'price_type' => $price_type,
            ];
            return $result;
        }
    }

    public static function get_percentage_discount_price($trip_id, $html = true)
    {
        $price = self::check_price_of_trip($trip_id);
        if (empty($price)) {
            return;
        }

        $regular_price = $price['regular_price'];
        $sale_price = $price['sale_price'];

        if ($sale_price > 0) {
            if ($html) {
                return '<div class="trip-discount">' . esc_html__('-' . round((($regular_price - $sale_price) / $regular_price) * 100) . '%', 'togo') . '</div>';
            } else {
                return round((($regular_price - $sale_price) / $regular_price) * 100);
            }
        }
    }

    public static function get_all_currency()
    {
        return [
            'AED' => __('United Arab Emirates dirham (د.إ) — AED', 'togo'),
            'AFN' => __('Afghan afghani (؋) — AFN', 'togo'),
            'ALL' => __('Albanian lek (Lek) — ALL', 'togo'),
            'AMD' => __('Armenian dram (֏) — AMD', 'togo'),
            'ARS' => __('Argentine peso ($) — ARS', 'togo'),
            'AUD' => __('Australian dollar ($) — AUD', 'togo'),
            'AWG' => __('Aruban florin (ƒ) — AWG', 'togo'),
            'AZN' => __('Azerbaijani manat (₼) — AZN', 'togo'),
            'BAM' => __('Bosnia and Herzegovina convertible mark (KM) — BAM', 'togo'),
            'BDT' => __('Bangladeshi taka (৳) — BDT', 'togo'),
            'BGN' => __('Bulgarian lev (лв) — BGN', 'togo'),
            'BHD' => __('Bahraini dinar (د.ب) — BHD', 'togo'),
            'BIF' => __('Burundian franc (Fr) — BIF', 'togo'),
            'BMD' => __('Bermudian dollar ($) — BMD', 'togo'),
            'BND' => __('Brunei dollar ($) — BND', 'togo'),
            'BOB' => __('Bolivian boliviano (Bs) — BOB', 'togo'),
            'BRL' => __('Brazilian real (R$) — BRL', 'togo'),
            'BSD' => __('Bahamian dollar ($) — BSD', 'togo'),
            'BTN' => __('Bhutanese ngultrum (Nu) — BTN', 'togo'),
            'BWP' => __('Botswana pula (P) — BWP', 'togo'),
            'BYN' => __('Belarusian ruble (Br) — BYN', 'togo'),
            'BZD' => __('Belize dollar ($) — BZD', 'togo'),
            'CAD' => __('Canadian dollar ($) — CAD', 'togo'),
            'CDF' => __('Congolese franc (Fr) — CDF', 'togo'),
            'CHF' => __('Swiss franc (CHF) — CHF', 'togo'),
            'CLP' => __('Chilean peso ($) — CLP', 'togo'),
            'CNY' => __('Chinese yuan (¥) — CNY', 'togo'),
            'COP' => __('Colombian peso ($) — COP', 'togo'),
            'CRC' => __('Costa Rican colón (₡) — CRC', 'togo'),
            'CUC' => __('Cuban convertible peso (CUC$) — CUC', 'togo'),
            'CUP' => __('Cuban peso (₱) — CUP', 'togo'),
            'CVS' => __('Cape Verdean escudo ($) — CVS', 'togo'),
            'CZK' => __('Czech koruna (Kč) — CZK', 'togo'),
            'DJF' => __('Djiboutian franc (Fdj) — DJF', 'togo'),
            'DKK' => __('Danish krone (kr) — DKK', 'togo'),
            'DOP' => __('Dominican peso ($) — DOP', 'togo'),
            'DZD' => __('Algerian dinar (دج) — DZD', 'togo'),
            'EGP' => __('Egyptian pound (£) — EGP', 'togo'),
            'ERN' => __('Eritrean nakfa (Nfk) — ERN', 'togo'),
            'ESP' => __('Spanish peseta (₧) — ESP', 'togo'),
            'ETB' => __('Ethiopian birr (ታብ) — ETB', 'togo'),
            'EUR' => __('Euro (€) — EUR', 'togo'),
            'FJD' => __('Fijian dollar ($) — FJD', 'togo'),
            'FKP' => __('Falkland Islands pound (£) — FKP', 'togo'),
            'GBP' => __('British pound sterling (£) — GBP', 'togo'),
            'GEL' => __('Georgian lari (₾) — GEL', 'togo'),
            'GHS' => __('Ghanaian cedi (₵) — GHS', 'togo'),
            'GIP' => __('Gibraltar pound (£) — GIP', 'togo'),
            'GMD' => __('Gambian dalasi (D) — GMD', 'togo'),
            'GNF' => __('Guinean franc (Fr) — GNF', 'togo'),
            'GTQ' => __('Guatemalan quetzal (Q) — GTQ', 'togo'),
            'GYD' => __('Guyanese dollar ($) — GYD', 'togo'),
            'HKD' => __('Hong Kong dollar ($) — HKD', 'togo'),
            'HNL' => __('Honduran lempira (L) — HNL', 'togo'),
            'HRK' => __('Croatian kuna (kn) — HRK', 'togo'),
            'HTG' => __('Haitian gourde (G) — HTG', 'togo'),
            'HUF' => __('Hungarian forint (Ft) — HUF', 'togo'),
            'IDR' => __('Indonesian rupiah (Rp) — IDR', 'togo'),
            'ILS' => __('Israeli new shekel (₪) — ILS', 'togo'),
            'INR' => __('Indian rupee (₹) — INR', 'togo'),
            'IQD' => __('Iraqi dinar (ع.د) — IQD', 'togo'),
            'IRR' => __('Iranian rial (﷼) — IRR', 'togo'),
            'ISK' => __('Icelandic króna (kr) — ISK', 'togo'),
            'JMD' => __('Jamaican dollar ($) — JMD', 'togo'),
            'JOD' => __('Jordanian dinar (د.ا) — JOD', 'togo'),
            'JPY' => __('Japanese yen (¥) — JPY', 'togo'),
            'KES' => __('Kenyan shilling (Sh) — KES', 'togo'),
            'KGS' => __('Kyrgyzstani som (лв) — KGS', 'togo'),
            'KHR' => __('Cambodian riel (៛) — KHR', 'togo'),
            'KMF' => __('Comorian franc (Fr) — KMF', 'togo'),
            'KRW' => __('South Korean won (₩) — KRW', 'togo'),
            'KWD' => __('Kuwaiti dinar (د.ك) — KWD', 'togo'),
            'KYD' => __('Cayman Islands dollar ($) — KYD', 'togo'),
            'KZT' => __('Kazakhstani tenge (₸) — KZT', 'togo'),
            'LAK' => __('Laotian kip (₭) — LAK', 'togo'),
            'LBP' => __('Lebanese pound (ل.ل) — LBP', 'togo'),
            'LKR' => __('Sri Lankan rupee (රු) — LKR', 'togo'),
            'LRD' => __('Liberian dollar ($) — LRD', 'togo'),
            'LSL' => __('Lesotho loti (M) — LSL', 'togo'),
            'LTL' => __('Lithuanian litas (Lt) — LTL', 'togo'),
            'LVL' => __('Latvian lats (Ls) — LVL', 'togo'),
            'LYD' => __('Libyan dinar (د.ل) — LYD', 'togo'),
            'MAD' => __('Moroccan dirham (د.م.) — MAD', 'togo'),
            'MDL' => __('Moldovan leu (Lei) — MDL', 'togo'),
            'MGA' => __('Malagasy ariary (Ar) — MGA', 'togo'),
            'MKD' => __('Macedonian denar (ден) — MKD', 'togo'),
            'MMK' => __('Myanmar kyat (Ks) — MMK', 'togo'),
            'MNT' => __('Mongolian tögrög (₮) — MNT', 'togo'),
            'MOP' => __('Macanese pataca (P) — MOP', 'togo'),
            'MUR' => __('Mauritian rupee (₨) — MUR', 'togo'),
            'MVR' => __('Maldivian rufiyaa (Rf) — MVR', 'togo'),
            'MWK' => __('Malawian kwacha (K) — MWK', 'togo'),
            'MXN' => __('Mexican peso ($) — MXN', 'togo'),
            'MYR' => __('Malaysian ringgit (RM) — MYR', 'togo'),
            'MZN' => __('Mozambican metical (MT) — MZN', 'togo'),
            'NAD' => __('Namibian dollar ($) — NAD', 'togo'),
            'NGN' => __('Nigerian naira (₦) — NGN', 'togo'),
            'NIO' => __('Nicaraguan córdoba (C$) — NIO', 'togo'),
            'NOK' => __('Norwegian krone (kr) — NOK', 'togo'),
            'NPR' => __('Nepalese rupee (₨) — NPR', 'togo'),
            'NZD' => __('New Zealand dollar ($) — NZD', 'togo'),
            'OMR' => __('Omani rial (ر.ع.) — OMR', 'togo'),
            'PAB' => __('Panamanian balboa (B/. ) — PAB', 'togo'),
            'PEN' => __('Peruvian nuevo sol (S/.) — PEN', 'togo'),
            'PGK' => __('Papua New Guinean kina (K) — PGK', 'togo'),
            'PHP' => __('Philippine peso (₱) — PHP', 'togo'),
            'PKR' => __('Pakistani rupee (₨) — PKR', 'togo'),
            'PLN' => __('Polish złoty (zł) — PLN', 'togo'),
            'PYG' => __('Paraguayan guarani (Gs) — PYG', 'togo'),
            'QAR' => __('Qatari riyal (ر.ق) — QAR', 'togo'),
            'RON' => __('Romanian leu (lei) — RON', 'togo'),
            'RSD' => __('Serbian dinar (дин) — RSD', 'togo'),
            'RUB' => __('Russian ruble (₽) — RUB', 'togo'),
            'RWF' => __('Rwandan franc (Fr) — RWF', 'togo'),
            'SAR' => __('Saudi riyal (ر.س) — SAR', 'togo'),
            'SBD' => __('Solomon Islands dollar ($) — SBD', 'togo'),
            'SCR' => __('Seychellois rupee (₨) — SCR', 'togo'),
            'SEK' => __('Swedish krona (kr) — SEK', 'togo'),
            'SGD' => __('Singapore dollar ($) — SGD', 'togo'),
            'SHP' => __('Saint Helena pound (£) — SHP', 'togo'),
            'SLL' => __('Sierra Leonean leone (Le) — SLL', 'togo'),
            'SOS' => __('Somali shilling (Sh) — SOS', 'togo'),
            'SRD' => __('Surinamese dollar (SR$) — SRD', 'togo'),
            'SSP' => __('South Sudanese pound (SS£) — SSP', 'togo'),
            'STN' => __('São Tomé and Príncipe dobra (Db) — STN', 'togo'),
            'SYP' => __('Syrian pound (£) — SYP', 'togo'),
            'SZL' => __('Swazi lilangeni (E) — SZL', 'togo'),
            'THB' => __('Thai baht (฿) — THB', 'togo'),
            'TJS' => __('Tajikistani somoni (ЅМ) — TJS', 'togo'),
            'TMT' => __('Turkmenistani manat (m) — TMT', 'togo'),
            'TND' => __('Tunisian dinar (د.ت) — TND', 'togo'),
            'TOP' => __('Tongan paʻanga (T$) — TOP', 'togo'),
            'TRY' => __('Turkish lira (₺) — TRY', 'togo'),
            'TTD' => __('Trinidad and Tobago dollar (TT$) — TTD', 'togo'),
            'TWD' => __('New Taiwan dollar (NT$) — TWD', 'togo'),
            'TZS' => __('Tanzanian shilling (Sh) — TZS', 'togo'),
            'UAH' => __('Ukrainian hryvnia (₴) — UAH', 'togo'),
            'UGX' => __('Ugandan shilling (Sh) — UGX', 'togo'),
            'USD' => __('United States dollar ($) — USD', 'togo'),
            'UYU' => __('Uruguayan peso ($) — UYU', 'togo'),
            'UZS' => __('Uzbekistani som (сўм) — UZS', 'togo'),
            'VEF' => __('Venezuelan bolívar (Bs.F) — VEF', 'togo'),
            'VND' => __('Vietnamese đồng (₫) — VND', 'togo'),
            'VUV' => __('Vanuatu vatu (Vt) — VUV', 'togo'),
            'WST' => __('Samoan tala (T) — WST', 'togo'),
            'XAF' => __('Central African CFA franc (Fr) — XAF', 'togo'),
            'XCD' => __('East Caribbean dollar ($) — XCD', 'togo'),
            'XOF' => __('West African CFA franc (Fr) — XOF', 'togo'),
            'XPF' => __('CFP franc (Fr) — XPF', 'togo'),
            'YER' => __('Yemeni rial (﷼) — YER', 'togo'),
            'ZAR' => __('South African rand (R) — ZAR', 'togo'),
            'ZMK' => __('Zambian kwacha (K) — ZMK', 'togo'),
            'ZWL' => __('Zimbabwean dollar (Z$) — ZWL', 'togo')
        ];
    }

    public static function get_currency_symbol($currency_code)
    {
        // Define the currency array
        $currencies = self::get_all_currency();

        // Check if the currency code exists in the array
        if (isset($currencies[$currency_code])) {
            // Extract and return only the symbol part
            preg_match('/\((.*?)\)/', $currencies[$currency_code], $matches);
            return $matches[1] ?? null; // Return the symbol if matched, otherwise null
        }

        // Return null if the currency code is not found
        return null;
    }

    public static function togo_format_price($price)
    {
        if ($price === null || $price === '' || !is_numeric($price)) {
            return;
        }
        if (class_exists('WooCommerce')) {
            $currency = get_woocommerce_currency();
            $symbol = get_woocommerce_currency_symbol();
            $currency_position = get_option('woocommerce_currency_pos', 'left');
            $currency_thousand_separator = get_option('woocommerce_thousand_sep', ',');
            $currency_decimal_separator = get_option('woocommerce_decimal_sep', '.');
            $currency_number_of_decimals = get_option('woocommerce_price_num_decimals', 0);
        } else {
            $currency = \Togo\Helper::setting('currency', 'USD');
            $symbol = self::get_currency_symbol($currency);
            $currency_position = \Togo\Helper::setting('currency_position', 'left');
            $currency_thousand_separator = \Togo\Helper::setting('currency_thousand_separator', ',');
            $currency_decimal_separator = \Togo\Helper::setting('currency_decimal_separator', '.');
            $currency_number_of_decimals = \Togo\Helper::setting('currency_number_of_decimals') ? \Togo\Helper::setting('currency_number_of_decimals') : 0;
        }
        if ($currency_position == 'right') {
            return number_format($price, $currency_number_of_decimals, $currency_decimal_separator, $currency_thousand_separator) . $symbol;
        } elseif ($currency_position == 'right_space') {
            return number_format($price, $currency_number_of_decimals, $currency_decimal_separator, $currency_thousand_separator) . ' ' . $symbol;
        } elseif ($currency_position == 'left') {
            return $symbol . number_format($price, $currency_number_of_decimals, $currency_decimal_separator, $currency_thousand_separator);
        } elseif ($currency_position == 'left_space') {
            return $symbol . ' ' . number_format($price, $currency_number_of_decimals, $currency_decimal_separator, $currency_thousand_separator);
        }
    }

    public static function convert24To12($time24)
    {
        // Validate the input format (HH:MM:SS or HH:MM)
        if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](?::[0-5][0-9])?$/', $time24)) {
            return "Invalid time format";
        }

        // Convert to 12-hour format
        $time12 = date("g:i A", strtotime($time24));
        return $time12;
    }

    /**
     * Create transient for woo booking from product Id and woo session ID
     * @param int room_id
     * @return void
     */
    public static function set_transient_woo_booking($product_id, $value)
    {
        $sessionID = self::get_unique_wc_session();
        $enable_holding_time = \Togo\Helper::setting('enable_holding_time');
        $holding_time = \Togo\Helper::setting('holding_time');
        if ($enable_holding_time) {
            $time = $holding_time;
        } else {
            $time = HOUR_IN_SECONDS * 24 * 365;
        }
        set_transient("togo_trip_reservation_data_{$product_id}_{$sessionID}", $value, $time);
    }

    /**
     * Get transient for woo booking from product Id and woo session ID
     * @param int room_id
     * @return mixed Return the value or false if no transient/no value/expired
     */
    public static function get_transient_woo_booking($product_id)
    {
        $sessionID = self::get_unique_wc_session();
        $transient_woo_booking = get_transient("togo_trip_reservation_data_{$product_id}_{$sessionID}");
        if ($transient_woo_booking) {
            return $transient_woo_booking;
        }
        if (wp_using_ext_object_cache() && !$transient_woo_booking) {
            if (self::get_cache_woo_booking($product_id) !== false) {
                return self::get_cache_woo_booking($product_id);
            }
        }
        return $transient_woo_booking;
    }

    /**
     * Delete transient for woo booking from product Id and woo session ID
     * @param int room_id
     * @return void
     */
    public static function delete_transient_woo_booking($product_id)
    {
        $sessionID = self::get_unique_wc_session();
        delete_transient("togo_trip_reservation_data_{$product_id}_{$sessionID}");
    }

    /**
     * Create cache for woo booking from product ID and woo session ID
     * @param int $product_id
     * @param mixed $value
     * @return void
     */
    public static function set_cache_woo_booking($product_id, $value)
    {
        $sessionID = self::get_unique_wc_session();
        $enable_holding_time = \Togo\Helper::setting('enable_holding_time');
        $holding_time = \Togo\Helper::setting('holding_time');
        if ($enable_holding_time) {
            $time = $holding_time;
        } else {
            $time = HOUR_IN_SECONDS * 24 * 365;
        }

        $cache_key = "togo_trip_reservation_data_{$product_id}_{$sessionID}";
        wp_cache_set($cache_key, [
            'value' => $value,
            'expires' => time() + $time
        ], '', $time);
    }

    /**
     * Get cache for woo booking from product ID and woo session ID
     * @param int $product_id
     * @return mixed|false
     */
    public static function get_cache_woo_booking($product_id)
    {
        $sessionID = self::get_unique_wc_session();
        $cache_key = "togo_trip_reservation_data_{$product_id}_{$sessionID}";
        $data = wp_cache_get($cache_key);

        if (is_array($data) && isset($data['value'], $data['expires'])) {
            if ($data['expires'] >= time()) {
                return $data['value'];
            } else {
                // Expired, delete it
                wp_cache_delete($cache_key);
                return false;
            }
        }

        return false;
    }

    /**
     * Delete cache for woo booking from product ID and woo session ID
     * @param int $product_id
     * @return void
     */
    public static function delete_cache_woo_booking($product_id)
    {
        $sessionID = self::get_unique_wc_session();
        $cache_key = "togo_trip_reservation_data_{$product_id}_{$sessionID}";
        wp_cache_delete($cache_key);
    }

    /**
     * Get WC sessionID for customer
     * @return string
     */
    public static function get_unique_wc_session()
    {
        $sessionID = '';
        
        if (is_null(WC()->session)) {
            WC()->session = new \WC_Session_Handler();
            WC()->session->init();
        }
        
        if (!is_null(WC()->session)) {
            $sessionID = WC()->session->get_customer_unique_id();
            
            if (empty($sessionID)) {
                WC()->session->set_customer_session_cookie(true);
                $sessionID = WC()->session->get_customer_unique_id();
            }
        }

        return $sessionID;
    }

    public static function send_email($send_to, $subject, $messages, $data = null)
    {
        if (empty($send_to) || $send_to == null) {
            $send_to = get_option('admin_email');
        }

        if (empty($subject)) {
            $subject = get_bloginfo('name');
        }

        if (empty($messages)) {
            $messages = esc_html__('This is an email from', 'togo') . ' ' . get_bloginfo('name');
        }

        // Load colors.
        $bg          = get_option('woocommerce_email_background_color');
        $body        = get_option('woocommerce_email_body_background_color');
        $base        = get_option('woocommerce_email_base_color');
        $base_text   = wc_light_or_dark($base, '#202020', '#ffffff');
        $text        = get_option('woocommerce_email_text_color');
        $footer_text = get_option('woocommerce_email_footer_text_color');

        // Pick a contrasting color for links.
        $link_color = wc_hex_is_light($base) ? $base : $base_text;

        if (wc_hex_is_light($body)) {
            $link_color = wc_hex_is_light($base) ? $base_text : $base;
        }

        $bg_darker_10    = wc_hex_darker($bg, 10);
        $body_darker_10  = wc_hex_darker($body, 10);
        $base_lighter_20 = wc_hex_lighter($base, 20);
        $base_lighter_40 = wc_hex_lighter($base, 40);
        $text_lighter_20 = wc_hex_lighter($text, 20);
        $text_lighter_40 = wc_hex_lighter($text, 40);

        // WooCommerce email styles
        $email_styles = '
        <style type="text/css">
            body {
                background-color: ' . $bg . ';
                padding: 0;
                text-align: center;
            }

            #outer_wrapper {
                background-color: ' . $bg . ';
            }

            #wrapper {
                margin: 0 auto;
                padding: 70px 0;
                -webkit-text-size-adjust: none !important;
                width: 100%;
                max-width: 600px;
            }

            #template_container {
                box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1) !important;
                background-color: ' . $body . ';
                border: 1px solid ' . $bg_darker_10 . ';
                border-radius: 3px !important;
            }

            #template_header {
                background-color: ' . $base . ';
                border-radius: 3px 3px 0 0 !important;
                color: ' . $base_text . ';
                border-bottom: 0;
                font-weight: bold;
                line-height: 100%;
                vertical-align: middle;
                font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
            }

            #template_header h1,
            #template_header h1 a {
                color: ' . $base_text . ';
                background-color: inherit;
            }

            #template_header_image img {
                margin-left: 0;
                margin-right: 0;
            }

            #template_footer td {
                padding: 0;
                border-radius: 6px;
            }

            #template_footer #credit {
                border: 0;
                color: ' . $footer_text . ';
                font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
                font-size: 12px;
                line-height: 150%;
                text-align: center;
                padding: 24px 0;
            }

            #template_footer #credit p {
                margin: 0 0 16px;
            }

            #body_content {
                background-color: ' . $body . ';
            }

            #body_content table td {
                padding: 48px 48px 32px;
            }

            #body_content table td td {
                padding: 12px;
            }

            #body_content table td th {
                padding: 12px;
            }

            #body_content td ul.wc-item-meta {
                font-size: small;
                margin: 1em 0 0;
                padding: 0;
                list-style: none;
            }

            #body_content td ul.wc-item-meta li {
                margin: 0.5em 0 0;
                padding: 0;
            }

            #body_content td ul.wc-item-meta li p {
                margin: 0;
            }

            #body_content p {
                margin: 0 0 16px;
            }

            #body_content_inner {
                color: ' . $text_lighter_20 . ';
                font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
                font-size: 14px;
                line-height: 150%;
                text-align: left;
            }

            .td {
                color: ' . $text_lighter_20 . ';
                border: 1px solid ' . $body_darker_10 . ';
                vertical-align: middle;
            }

            .address {
                padding: 12px;
                color: ' . $text_lighter_20 . ';
                border: 1px solid ' . $body_darker_10 . ';
            }

            .additional-fields {
                padding: 12px 12px 0;
                color: ' . $text_lighter_20 . ';
                border: 1px solid ' . $body_darker_10 . ';
                list-style: none outside;
            }

            .additional-fields li {
                margin: 0 0 12px 0;
            }

            .text {
                color: ' . $text . ';
                font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
            }

            .link {
                color: ' . $link_color . ';
            }

            #header_wrapper {
                padding: 36px 48px;
                display: block;
            }

            #template_footer #credit,
            #template_footer #credit a {
                color: ' . $footer_text . ';
            }

            h1 {
                color: ' . $base . ';
                font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
                font-size: 30px;
                font-weight: 300;
                line-height: 150%;
                margin: 0;
                text-align: left;
                text-shadow: 0 1px 0 ' . $base_lighter_20 . ';
            }

            h2 {
                color: ' . $base . ';
                display: block;
                font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
                font-size: 18px;
                font-weight: bold;
                line-height: 130%;
                margin: 0 0 18px;
                text-align: left;
            }

            h3 {
                color: ' . $base . ';
                display: block;
                font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
                font-size: 16px;
                font-weight: bold;
                line-height: 130%;
                margin: 16px 0 8px;
                text-align: left;
            }

            a {
                color: ' . $link_color . ';
                font-weight: normal;
                text-decoration: underline;
            }

            img {
                border: none;
                display: inline-block;
                font-size: 14px;
                font-weight: bold;
                height: auto;
                outline: none;
                text-decoration: none;
                text-transform: capitalize;
                vertical-align: middle;
                margin-left: 10px;
                max-width: 100%;
            }

            /**
             * Media queries are not supported by all email clients, however they do work on modern mobile
             * Gmail clients and can help us achieve better consistency there.
             */
            @media screen and (max-width: 600px) {
                #header_wrapper {
                    padding: 27px 36px !important;
                    font-size: 24px;
                }

                #body_content table > tbody > tr > td {
                    padding: 10px !important;
                }

                #body_content_inner {
                    font-size: 10px !important;
                }
            }
            </style>
        ';

        // Load WooCommerce email header and footer
        ob_start();

        // Add email styles
        echo $email_styles;

        // Header
        wc_get_template('emails/email-header.php', ['email_heading' => $subject]);

        echo $messages;

        // Footer
        wc_get_template('emails/email-footer.php');

        $email_content = ob_get_clean();

        $email_content = str_replace('{site_title}', get_bloginfo('name'), $email_content);
        if (!empty($data)) {
            $email_content = str_replace(array_keys($data), array_values($data), $email_content);
        }

        $headers = array('Content-Type: text/html; charset=UTF-8');


        $result = wp_mail($send_to, $subject, $email_content, $headers);

        return $result;
    }

    public static function get_max_price_all_trips()
    {
        $args = array(
            'post_type' => 'togo_trip',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );

        $query = get_posts($args);

        $max_price = 0;
        if ($query) {
            foreach ($query as $trip) {
                $trip_id = $trip->ID;
                $tour_package = get_post_meta($trip_id, 'tour_package', true);
                $trip_pricing_type = get_post_meta($trip_id, 'trip_pricing_type', true);
                $pricing_categories = wp_get_post_terms($trip_id, 'togo_trip_pricing_categories');
                if ($tour_package) {
                    foreach ($tour_package as $package) {
                        if (!empty($package['schedules'])) {
                            foreach ($package['schedules'] as $schedule) {
                                if ($trip_pricing_type == 'per_person' && !empty($pricing_categories)) {
                                    foreach ($pricing_categories as $key => $pricing_category) {
                                        $price_key = 'regular_price[' . $pricing_category->slug . ']';
                                        if (!empty($schedule[$price_key]) && is_array($schedule[$price_key])) {
                                            $schedule_max = max($schedule[$price_key]);
                                            if ($max_price < $schedule_max) {
                                                $max_price = $schedule_max;
                                            }
                                        }
                                    }
                                } elseif ($trip_pricing_type == 'per_group') {
                                    if (!empty($schedule['per_group_regular_price']) && is_array($schedule['per_group_regular_price'])) {
                                        $group_max = max($schedule['per_group_regular_price']);
                                        if ($max_price < $group_max) {
                                            $max_price = $group_max;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $max_price;
    }

    public static function togo_get_template($template_name, $args = array(), $template_path = '', $default_path = '')
    {
        if (!empty($args) && is_array($args)) {
            extract($args);
        }

        $located = self::togo_locate_template($template_name, $template_path, $default_path);

        if (!file_exists($located)) {
            _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $located), '2.1');
            return;
        }

        // Allow 3rd party plugin filter template file from their plugin.
        $located = apply_filters('togo_get_template', $located, $template_name, $args, $template_path, $default_path);

        do_action('togo_before_template_part', $template_name, $template_path, $located, $args);

        include($located);

        do_action('togo_after_template_part', $template_name, $template_path, $located, $args);
    }

    public static function togo_locate_template($template_name, $template_path = '', $default_path = '')
    {
        if (!$template_path) {
            $template_path = self::template_path();
        }

        if (!$default_path) {
            $default_path = TOGO_FRAMEWORK_PATH . 'templates/';
        }

        // Look within passed path within the theme - this is priority.
        $template = locate_template(
            array(
                trailingslashit($template_path) . $template_name,
                $template_name
            )
        );

        // Get default template/
        if (!$template) {
            $template = $default_path . $template_name;
        }

        // Return what we found.
        return apply_filters('togo_locate_template', $template, $template_name, $template_path);
    }

    public static function template_path()
    {
        return apply_filters('togo_template_path', 'togo-framework/');
    }

    public static function get_current_page_url($with_query = true)
    {
        // Get the protocol
        $protocol = is_ssl() ? 'https://' : 'http://';

        // Get the host
        $host = $_SERVER['HTTP_HOST'];

        // Get the request URI with or without query string
        $uri = $with_query ? $_SERVER['REQUEST_URI'] : explode('?', $_SERVER['REQUEST_URI'], 2)[0];

        // Combine everything into the full URL
        return $protocol . $host . $uri;
    }

    /**
     * Check if the provided array is an empty array.
     *
     * This function determines if the given array is empty or contains only sub-arrays
     * that are either not arrays or contain only empty values. It returns true if the
     * array is considered empty, otherwise false.
     *
     * @param array $array The array to be checked.
     * @return bool True if the array is empty or contains only empty sub-arrays, otherwise false.
     */


    public static function is_empty_array($array)
    {
        if (!is_array($array) || empty($array)) {
            return true; // Main array is empty or not an array
        }

        foreach ($array as $sub_array) {
            if (!is_array($sub_array) || empty(array_filter($sub_array, 'strlen'))) {
                continue; // If all values are empty, continue checking
            }
            return false; // If there is at least one non-empty value, the array is not empty
        }

        return true; // If all sub-arrays are empty, consider the array empty
    }

    public static function get_all_terms_by_taxonomy($taxonomy, $select_option = false, $args = array())
    {
        $terms = get_terms(array_merge(array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
        ), $args));

        if (is_wp_error($terms)) {
            return array();
        }

        if ($select_option) {
            $terms = array('' => __('Select', 'togo')) + wp_list_pluck($terms, 'name', 'slug');
        }

        return $terms;
    }
}
