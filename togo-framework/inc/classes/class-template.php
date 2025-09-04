<?php

namespace Togo_Framework;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Template
 */
class Template
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
        add_filter('template_include', array($this, 'template_loader'));
        add_filter('theme_page_templates', array($this, 'register_templates'));
        add_filter('upload_mimes', array($this, 'add_svg_to_upload_mimes'));
    }

    /**
     * Load template
     *
     * @since 1.0.0
     *
     * @param string $template
     * @return string
     */
    public function template_loader($template)
    {
        if (is_embed()) {
            return $template;
        }

        $find = [];
        $file = '';

        // Main archive and taxonomy condition
        if (
            is_post_type_archive('togo_trip') ||
            is_page('togo_trip') ||
            is_tax('togo_trip_activities') ||
            is_tax('togo_trip_types') ||
            is_tax('togo_trip_durations') ||
            is_tax('togo_trip_tod') ||
            is_tax('togo_trip_languages')
        ) {
            $file = 'archive-trip.php';
        } elseif (is_tax('togo_trip_destinations')) {
            $file = 'taxonomy-togo_trip_destinations.php';
        }

        // Check template page
        global $post;
        if ($post) {
            $selected_template = get_post_meta($post->ID, '_wp_page_template', true);

            if ($selected_template === 'my-account.php') {
                $file = 'my-account.php';
            }
        }

        // If there is a file to handle
        if ($file) {
            // Prioritize file in child/parent theme: togo-framework/
            $theme_template = locate_template('togo-framework/' . $file);
            if ($theme_template) {
                return $theme_template;
            }

            // Fallback to plugin
            return TOGO_FRAMEWORK_PATH . 'templates/' . $file;
        }

        return $template;
    }

    public function register_templates($templates)
    {
        $templates['my-account.php'] = 'My Account';
        return $templates;
    }

    public function add_svg_to_upload_mimes($mimes)
    {
        if (current_user_can('manage_options')) {
            $mimes['svg'] = 'image/svg+xml';
        }
        return $mimes;
    }

    public static function render_skeleton($heading)
    {
        if (empty($heading)) {
            $html = '<div class="togo-skeleton">';
            $html .= '<span></span>';
            $html .= '<span></span>';
            $html .= '<span></span>';
            $html .= '</div>';
        } else {
            $html = '<div class="togo-skeleton-wrapper">';
            $html .= '<h5 class="togo-skeleton-title">' . esc_html($heading) . '</h5>';
            $html .= '<div class="togo-skeleton">';
            $html .= '<span></span>';
            $html .= '<span></span>';
            $html .= '<span></span>';
            $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }

    public static function pagination($align = 'left')
    {
        global $wp_query;
        $big = 999999999; // need an unlikely integer
        $paginate_links = paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $wp_query->max_num_pages,
            'prev_text' => __('<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 6L9 12L15 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'),
            'next_text' => __('<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'),
        ));

        if ($paginate_links == null) {
            return;
        }

        $html = '<div class="togo-pagination togo-pagination-' . esc_attr($align) . '">';
        $html .= $paginate_links;
        $html .= '</div>';

        return $html;
    }

    public static function display_tour_pagination_info($align = 'left')
    {
        global $wp_query;

        $posts_per_page = get_option('posts_per_page');

        // Get total posts for the custom post type
        $total_posts = $wp_query->found_posts;

        // If the total is over 50, display "50+"
        $total_display = ($total_posts > 50) ? '50+' : $total_posts;

        if ($total_display == 0) {
            return;
        }

        // Get the current page
        $current_page = max(1, get_query_var('paged'));

        // Calculate start and end posts for the current page
        $start = ($current_page - 1) * $posts_per_page + 1;
        $end = min($start + $posts_per_page - 1, $total_posts);

        // Generate output
        $output = sprintf(
            esc_html__('Showing %d – %d of %s tours found', 'togo-framework'),
            $start,
            $end,
            $total_display
        );

        echo '<div class="tour-pagination-info tour-pagination-info-' . esc_attr($align) . '">' . $output . '</div>';
    }

    public static function render_maps()
    {
        $archive_trip_enable_maps = apply_filters('togo_archive_trip_enable_maps', \Togo\Helper::setting('archive_trip_enable_maps'));
        $trip_ids = get_option('togo_trip_ids');
        if (empty($trip_ids)) {
            return;
        }
        if ($archive_trip_enable_maps == 'no') {
            return;
        }

        // Example marker data (can come from a database or API)
        $markers = [];

        foreach ($trip_ids as $trip_id) {
            $trip_maps_address = get_post_meta($trip_id, 'trip_maps_address', true);
            $lat = 37.7749;
            $lng = -122.4194;
            if (!empty($trip_maps_address)) {
                $location = explode(',', $trip_maps_address['location']);
                $lat = $location[0];
                $lng = $location[1];
            }
            ob_start();
            \Togo_Framework\Helper::togo_get_template('content/trip/trip-grid-01.php', ['trip_id' => $trip_id]);
            $trip_html = ob_get_clean();
            $markers[] = [
                'lat' => $lat,
                'lng' => $lng,
                'title' => $trip_html
            ];
        }

        // Encode the markers as a JSON string
        $marker_data = json_encode($markers);
        wp_enqueue_script('google-map-callback');
        if (is_tax('togo_trip_destinations')) {
            // Get the current taxonomy term
            $term = get_queried_object();
            $archive_trip_use_template_elementor = \Togo\Helper::setting('archive_trip_use_template_elementor');
            if($archive_trip_use_template_elementor == 'yes' && is_tax('togo_trip_destinations')) {
                echo '<h4 class="trip-destinations-heading">' . sprintf(esc_html__('%s tour maps', 'togo-framework'), esc_html($term->name)) . '</h4>';
            }
        }
        echo '<div class="map-container">';
        echo '<a href="#" class="view-full-map">' . \Togo\Icon::get_svg('chevron-left') . '</a>';
        echo '<div id="togo-map" data-marker="' . htmlspecialchars($marker_data, ENT_QUOTES, "UTF-8") . '"></div>';
        echo '</div>';
?>
        <script>
            function initMap() {
                // Get marker data from the `data-marker` attribute
                const markerData = JSON.parse(document.getElementById('togo-map').getAttribute('data-marker'));
                // Set map options
                var options = {
                    center: {
                        lat: parseInt(markerData[0].lat),
                        lng: parseInt(markerData[0].lng)
                    },
                    zoom: 8,
                    disableDefaultUI: true
                };

                // Create a map object
                var map = new google.maps.Map(document.getElementById('togo-map'), options);

                // Create an array to keep track of the markers and popups
                const markers = [];
                const popups = [];

                // Create custom markers with hover popups
                markerData.forEach((marker, index) => {
                    const CustomMarker = function(position, map, title) {
                        this.position = position;
                        this.map = map;

                        // Create the marker element
                        const div = document.createElement('div');
                        div.className = 'custom-marker';
                        div.textContent = (index + 1).toString(); // Number the markers

                        // Create the popup element (supporting HTML content)
                        const popup = document.createElement('div');
                        popup.className = 'marker-popup';
                        popup.innerHTML = title; // Set HTML content

                        this.div = div;
                        this.popup = popup;

                        // Add the marker and popup to the map
                        this.setMap(map);
                    };

                    CustomMarker.prototype = new google.maps.OverlayView();

                    CustomMarker.prototype.onAdd = function() {
                        const panes = this.getPanes();
                        panes.overlayMouseTarget.appendChild(this.div);
                        panes.overlayMouseTarget.appendChild(this.popup);
                    };

                    CustomMarker.prototype.draw = function() {
                        const point = this.getProjection().fromLatLngToDivPixel(this.position);
                        if (point) {
                            this.div.style.left = point.x + 'px';
                            this.div.style.top = point.y + 'px';

                            // Point height
                            const pointHeight = this.div.offsetHeight;
                            const pointHeightRequired = pointHeight * 2;
                            const pointHeightIndex = pointHeight * (index + 1);

                            // Popup width and height
                            const popupWidth = this.popup.offsetWidth;
                            const popupHeight = this.popup.offsetHeight;

                            // Position the popup slightly above the marker
                            this.popup.style.left = (point.x - popupWidth / 2) + 'px';
                            this.popup.style.top = (point.y - popupHeight - pointHeightRequired + pointHeightIndex) + 'px'; // Adjust the offset as needed
                        }
                    };

                    CustomMarker.prototype.onRemove = function() {
                        if (this.div) {
                            this.div.parentNode.removeChild(this.div);
                            this.div = null;
                        }
                        if (this.popup) {
                            this.popup.parentNode.removeChild(this.popup);
                            this.popup = null;
                        }
                    };

                    // Create a new custom marker
                    const customMarker = new CustomMarker(
                        new google.maps.LatLng(marker.lat, marker.lng),
                        map,
                        marker.title
                    );

                    // Store the marker and its popup in arrays for later reference
                    markers.push(customMarker);
                    popups.push(customMarker.popup);

                    // Click event to show the popup and close any previously opened popup
                    customMarker.div.addEventListener('click', () => {
                        // Close all other popups
                        popups.forEach(popup => {
                            popup.style.display = 'none';
                            markers.forEach(marker => {
                                marker.div.classList.remove('clicked');
                            })
                        });

                        map.panTo(new google.maps.LatLng(marker.lat, marker.lng));

                        // Show the clicked marker's popup
                        customMarker.popup.style.display = 'block';
                        customMarker.div.classList.add('clicked');
                    });
                });

                // Close popup when clicking outside the map or popups
                document.addEventListener('click', (event) => {
                    const isClickInsideMap = document.getElementById('togo-map').contains(event.target);
                    const isClickInsidePopup = event.target.classList.contains('marker-popup') || event.target.closest('.marker-popup');

                    // If the click is outside the map or the popups, hide all popups
                    if (!isClickInsideMap && !isClickInsidePopup) {
                        popups.forEach(popup => {
                            popup.style.display = 'none';
                            markers.forEach(marker => {
                                marker.div.classList.remove('clicked');
                            })
                        });
                    }
                });

                // Link list items to map markers for hover effect
                const listItems = document.querySelectorAll('.trip-list .type-trip');
                listItems.forEach((item, index) => {
                    const marker = markerData[index];

                    // Ensure the popup exists before adding event listeners
                    if (popups[index]) {
                        item.addEventListener('mouseover', () => {
                            // Close all other popups
                            popups.forEach(popup => {
                                popup.style.display = 'none';
                                markers.forEach(marker => {
                                    marker.div.classList.remove('clicked');
                                })
                            });

                            // Show popup for the corresponding marker
                            map.panTo(new google.maps.LatLng(marker.lat, marker.lng));
                            map.setZoom(10);

                            // Show the popup on hover
                            popups[index].style.display = 'block';
                            markers[index].div.classList.add('clicked');
                        });

                        item.addEventListener('mouseout', () => {
                            // Hide the popup when mouse leaves the list item
                            popups[index].style.display = 'none';
                            markers[index].div.classList.remove('clicked');
                        });
                    }
                });
            }
        </script>
    <?php
    }

    public static function render_itinerary_popup()
    {
    ?>
        <div class="itinerary-popup"></div>
    <?php
    }

    public static function render_trip_wishlist($trip_id)
    {
        $trip_card_enable_wishlist = \Togo\Helper::setting('trip_card_enable_wishlist');
        if (empty($trip_id)) return;
        if ($trip_card_enable_wishlist == 'no') return;
    ?>
        <div class="trip-wishlist">
            <?php
            if (!is_user_logged_in()) {
            ?>
                <div class="togo-tooltip">
                    <?php echo \Togo\Icon::get_svg('heart'); ?>
                    <div class="togo-tooltip-content">
                        <p><?php echo esc_html__('Login to use', 'togo-framework'); ?></p>
                    </div>
                </div>
            <?php
            } else {
                $togo_wishlist = get_user_meta(get_current_user_id(), 'togo_wishlist', true);
                if (empty($togo_wishlist)) {
                    $togo_wishlist = array();
                }
                $is_wishlist = in_array($trip_id, $togo_wishlist);
                if ($is_wishlist) {
                    $class_added = 'added';
                    $tooltip_text = esc_html__('Added to wishlist', 'togo-framework');
                } else {
                    $class_added = '';
                    $tooltip_text = esc_html__('Add to wishlist', 'togo-framework');
                }
            ?>
                <div class="togo-tooltip">
                    <a href="#" class="add-to-wishlist <?php echo $class_added; ?>" data-trip-id="<?php echo $trip_id; ?>">
                        <?php echo \Togo\Icon::get_svg('heart'); ?>
                    </a>
                    <div class="togo-tooltip-content">
                        <p><?php echo $tooltip_text; ?></p>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    <?php
    }

    public static function render_trip_slider_thumbnails($trip_id)
    {
        if (empty($trip_id)) return;
        $trip_video_url = get_post_meta($trip_id, 'trip_video_url', true);
        $trip_video_image = get_post_meta($trip_id, 'trip_video_image', true);
        $trip_gallery_images = get_post_meta($trip_id, 'trip_gallery_images', true);
        $trip_card_image_size = \Togo\Helper::setting('trip_card_image_size');
        if ($trip_card_image_size) {
            $trip_card_image_size = explode('x', $trip_card_image_size);
            $width = isset($trip_card_image_size[0]) ? intval($trip_card_image_size[0]) : 600;
            $height = isset($trip_card_image_size[1]) ? intval($trip_card_image_size[1]) : 450;
        } else {
            $width = 600;
            $height = 450;
        }
        $trip_gallery_images = explode('|', $trip_gallery_images);
        if (!empty($trip_video_url)) {
            echo '<div class="trip-video">';
            $trip_video_image_id = !empty($trip_video_image) ? $trip_video_image['id'] : get_post_thumbnail_id($trip_id);
            if (!empty($trip_video_image_id)) {
                $video_image = \Togo_Image::get_attachment_url_by_id(array('id' => intval($trip_video_image_id), 'size' => 'custom', 'width' => $width, 'height' => $height, 'details' => false));
                echo '<div class="trip-video-thumbnail">';
                echo '<img src="' . esc_url($video_image) . '" alt="">';
                echo '<div class="trip-video-play">';
                echo \Togo\Icon::get_svg('play');
                echo '</div>';
                echo '</div>';
            }
            // Check is vimeo or youtube
            if (strpos($trip_video_url, 'vimeo') !== false) {
                // Show Vimeo video
                $video_id = '';
                if (preg_match('/vimeo\.com\/([0-9]+)/', $trip_video_url, $matches)) {
                    $video_id = $matches[1];
                }
                if (!empty($video_id)) {
                    echo '<iframe src="https://player.vimeo.com/video/' . esc_attr($video_id) . '?background=1&autoplay=0&muted=1&loop=1" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
                }
            } elseif (strpos($trip_video_url, 'youtube') !== false) {
                $video_id = '';
                if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $trip_video_url, $matches)) {
                    $video_id = $matches[1];
                } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $trip_video_url, $matches)) {
                    $video_id = $matches[1];
                }
                if (!empty($video_id)) {
                    echo '<iframe src="https://www.youtube.com/embed/' . esc_attr($video_id) . '?autoplay=1&mute=1&enablejsapi=1&controls=0&modestbranding=1&rel=0&loop=1&playlist=' . esc_attr($video_id) . '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
                }
            } else {
                // Show HTML5 video
                echo '<video loop>';
                echo '<source src="' . esc_url($trip_video_url) . '" type="video/mp4">';
                echo esc_html__('Your browser does not support the video tag.', 'togo-framework');
                echo '</video>';
            }
            echo '</div>';
        } elseif (!empty($trip_gallery_images) && !empty($trip_gallery_images[0])) {
            echo '<div class="trip-gallery">';
            echo '<div class="trip-gallery-slider swiper pagination-absolute" data-lg-items="1" data-md-items="1" data-sm-items="1" data-lg-gutter="0" data-md-gutter="0" data-sm-gutter="0" data-autoplay="0" data-nav="true" data-pagination="true">';
            echo '<div class="swiper-wrapper">';
            foreach ($trip_gallery_images as $trip_gallery_image) {
                $image = \Togo_Image::get_attachment_url_by_id(array('id' => intval($trip_gallery_image), 'size' => 'custom', 'width' => $width, 'height' => $height, 'details' => false));
                echo '<div class="swiper-slide">';
                echo '<a href="' . get_the_permalink($trip_id) . '">';
                echo '<img src="' . $image . '" alt="' . get_the_title($trip_id) . '">';
                echo '</a>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
        } elseif (has_post_thumbnail($trip_id)) {
            // If there is a featured image, display it
            $featured_image = \Togo_Image::get_attachment_url_by_id(array('id' => get_post_thumbnail_id($trip_id), 'size' => 'custom', 'width' => $width, 'height' => $height, 'details' => false));
            echo '<div class="trip-thumbnail">';
            echo '<a href="' . get_permalink($trip_id) . '">';
            echo '<img src="' . esc_url($featured_image) . '" alt="' . esc_attr(get_the_title($trip_id)) . '">';
            echo '</a>';
            echo '</div>';
        }
    }

    public static function render_trip_thumbnails($trip_id, $image_size = '600x450')
    {
        if (empty($trip_id)) return;
        $trip_video_url = get_post_meta($trip_id, 'trip_video_url', true);
        $trip_video_image = get_post_meta($trip_id, 'trip_video_image', true);
        $trip_gallery_images = get_post_meta($trip_id, 'trip_gallery_images', true);
        $trip_card_image_size = $image_size ? $image_size : \Togo\Helper::setting('trip_card_image_size');
        if ($trip_card_image_size) {
            $trip_card_image_size = explode('x', $trip_card_image_size);
            $width = isset($trip_card_image_size[0]) ? intval($trip_card_image_size[0]) : 600;
            $height = isset($trip_card_image_size[1]) ? intval($trip_card_image_size[1]) : 450;
        } else {
            $width = 600;
            $height = 450;
        }
        $trip_gallery_images = explode('|', $trip_gallery_images);
        if (!empty($trip_video_url)) {
            echo '<div class="trip-video">';
            $trip_video_image_id = !empty($trip_video_image) ? $trip_video_image['id'] : get_post_thumbnail_id($trip_id);
            if (!empty($trip_video_image_id)) {
                $video_image = \Togo_Image::get_attachment_url_by_id(array('id' => intval($trip_video_image_id), 'size' => 'custom', 'width' => $width, 'height' => $height, 'details' => false));
                echo '<div class="trip-video-thumbnail">';
                echo '<img src="' . esc_url($video_image) . '" alt="">';
                echo '<div class="trip-video-play">';
                echo \Togo\Icon::get_svg('play');
                echo '</div>';
                echo '</div>';
            }
            // Check is vimeo or youtube
            if (strpos($trip_video_url, 'vimeo') !== false) {
                // Show Vimeo video
                $video_id = '';
                if (preg_match('/vimeo\.com\/([0-9]+)/', $trip_video_url, $matches)) {
                    $video_id = $matches[1];
                }
                if (!empty($video_id)) {
                    echo '<iframe src="https://player.vimeo.com/video/' . esc_attr($video_id) . '?background=1&autoplay=0&muted=1&loop=1" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
                }
            } elseif (strpos($trip_video_url, 'youtube') !== false) {
                $video_id = '';
                if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $trip_video_url, $matches)) {
                    $video_id = $matches[1];
                } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $trip_video_url, $matches)) {
                    $video_id = $matches[1];
                }
                if (!empty($video_id)) {
                    echo '<iframe src="https://www.youtube.com/embed/' . esc_attr($video_id) . '?autoplay=1&mute=1&enablejsapi=1&controls=0&modestbranding=1&rel=0&loop=1&playlist=' . esc_attr($video_id) . '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
                }
            } else {
                // Show HTML5 video
                echo '<video loop>';
                echo '<source src="' . esc_url($trip_video_url) . '" type="video/mp4">';
                echo esc_html__('Your browser does not support the video tag.', 'togo-framework');
                echo '</video>';
            }
            echo '</div>';
        } elseif (has_post_thumbnail($trip_id)) {
            // If there is a featured image, display it
            $featured_image = \Togo_Image::get_attachment_url_by_id(array('id' => get_post_thumbnail_id($trip_id), 'size' => 'custom', 'width' => $width, 'height' => $height, 'details' => false));
            echo '<div class="trip-thumbnail">';
            echo '<a href="' . get_permalink($trip_id) . '">';
            echo '<img src="' . esc_url($featured_image) . '" alt="' . esc_attr(get_the_title($trip_id)) . '">';
            echo '</a>';
            echo '</div>';
        }
    }

    public static function render_trip_meta($trip_id)
    {
        $trip_card_enable_rating = \Togo\Helper::setting('trip_card_enable_rating');
        $trip_card_enable_location = \Togo\Helper::setting('trip_card_enable_location');
        if (empty($trip_id)) return;
        if ($trip_card_enable_rating == 'no' && $trip_card_enable_location == 'no') return;
        echo '<div class="trip-meta">';
        echo self::render_trip_short_review($trip_id);
        echo self::render_trip_location($trip_id);
        echo '</div>';
    }

    public static function render_trip_short_review($trip_id)
    {
        $trip_card_enable_rating = \Togo\Helper::setting('trip_card_enable_rating');
        if (empty($trip_id)) return;
        if ($trip_card_enable_rating == 'no') return;
        $single_trip_reviews = \Togo\Helper::setting('single_trip_reviews');
        $id = $trip_id;
        $args = array(
            'post_type' => 'togo_review',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'review_trip_id',
                    'value' => $id,
                    'compare' => '=',
                )
            )
        );
        $trip_reviews = get_posts($args);
        $count_trip_reviews = count($trip_reviews) ? count($trip_reviews) : 0;

        if (empty($trip_reviews)) {
            $overall_rating = 0;
        } else {
            $overall = array();
            foreach ($trip_reviews as $review) {
                $review_id = $review->ID;
                for ($i = 0; $i < count($single_trip_reviews); $i++) {
                    $trip_review = get_post_meta($review_id, 'trip_reviews_' . $i, true);
                    if ($trip_review) {
                        $overall[$i][] = $trip_review;
                    }
                }
            }

            $overall_rating = 0;
            for ($i = 0; $i < count($single_trip_reviews); $i++) {
                $average = 0;
                if (array_key_exists($i, $overall)) {
                    $total = array_sum(array_map('intval', $overall[$i]));
                    if ($total) {
                        $average = $total / count($overall[$i]);
                        $overall_rating += $average;
                    }
                }
            }
        }
        echo '<div class="trip-review">';
        echo \Togo\Icon::get_svg('star');
        echo '<span class="trip-review-score">' . round($overall_rating / count($single_trip_reviews), 1) . '</span>';
        echo '<span class="trip-review-count">' . sprintf('(%d)', $count_trip_reviews) . '</span>';
        echo '</div>';
    }

    public static function render_trip_location($trip_id)
    {
        $trip_card_enable_location = \Togo\Helper::setting('trip_card_enable_location');
        if (empty($trip_id)) return;
        if ($trip_card_enable_location == 'no') return;
        $terms_destinations = wp_get_post_terms($trip_id, 'togo_trip_destinations');
        if (!empty($terms_destinations) && !is_wp_error($terms_destinations)) {
            echo '<div class="trip-location">';
            echo '<a href="' . get_term_link($terms_destinations[0]) . '">' . $terms_destinations[0]->name . '</a>';
            echo '</div>';
        }
    }

    public static function render_trip_location_with_icon($trip_id)
    {
        $trip_card_enable_location = \Togo\Helper::setting('trip_card_enable_location');
        if (empty($trip_id)) return;
        if ($trip_card_enable_location == 'no') return;
        $terms_destinations = wp_get_post_terms($trip_id, 'togo_trip_destinations');
        if (!empty($terms_destinations) && !is_wp_error($terms_destinations)) {
            echo '<div class="trip-location">';
            echo '<div class="togo-tooltip">';
            echo \Togo\Icon::get_svg('location');
            echo '<div class="togo-tooltip-content">';
            echo '<p>' . esc_html__('Location', 'togo-framework') . '</p>';
            echo '</div>';
            echo '</div>';
            echo '<a href="' . get_term_link($terms_destinations[0]) . '">' . $terms_destinations[0]->name . '</a>';
            echo '</div>';
        }
    }

    public static function render_trip_view_detail($trip_id)
    {
        if (empty($trip_id)) return;
        echo '<div class="trip-view-detail">';
        echo '<a href="' . get_permalink($trip_id) . '" class="togo-button full-filled">' . esc_html__('View tour', 'togo-framework') . '</a>';
        echo '</div>';
    }

    public static function render_trip_title($trip_id)
    {
        if (empty($trip_id)) return;
        echo '<h3 class="trip-title">';
        echo '<a href="' . get_permalink($trip_id) . '" rel="bookmark">' . get_the_title($trip_id) . '</a>';
        echo '</h3>';
    }

    public static function render_trip_information($trip_id, $items = array())
    {
        $trip_card_enable_location = \Togo\Helper::setting('trip_card_enable_location');
        $trip_card_enable_duration = \Togo\Helper::setting('trip_card_enable_duration');
        $trip_card_enable_guests = \Togo\Helper::setting('trip_card_enable_guests');
        $trip_card_enable_tour_type = apply_filters('togo_trip_card_enable_tour_type', \Togo\Helper::setting('trip_card_enable_tour_type'));
        if (empty($trip_id)) return;
        if ($items) {
            echo '<div class="trip-info">';
            foreach ($items as $item) {
                if ($item == 'location' && $trip_card_enable_location == 'yes') {
                    self::render_trip_location_with_icon($trip_id);
                } elseif ($item == 'duration' && $trip_card_enable_duration == 'yes') {
                    self::render_trip_duration($trip_id);
                } elseif ($item == 'guests' && $trip_card_enable_guests == 'yes') {
                    self::render_trip_guests($trip_id);
                } elseif ($item == 'types' && $trip_card_enable_tour_type == 'yes') {
                    self::render_trip_types($trip_id);
                }
            }
            echo '</div>';
        }
    }

    public static function render_trip_description($trip_id, $word_count = 20)
    {
        $trip_card_enable_description = apply_filters('togo_trip_card_enable_description', \Togo\Helper::setting('trip_card_enable_description'));
        $trip_card_layout = apply_filters('togo_trip_card_layout', \Togo\Helper::setting('trip_card_layout'));
        if (empty($trip_id)) return;
        if ($trip_card_enable_description == 'no') return;
        if (get_the_excerpt($trip_id) == '' || get_the_excerpt($trip_id) == 'Home') return;
        echo '<div class="trip-description">';
        echo '<p>' . wp_trim_words(get_the_excerpt($trip_id), $word_count, '...') . '</p>';
        echo '</div>';
    }

    public static function render_trip_duration($trip_id)
    {
        $trip_card_enable_duration = \Togo\Helper::setting('trip_card_enable_duration');
        if (empty($trip_id)) return;
        if ($trip_card_enable_duration == 'no') return;
        $terms_durations = wp_get_post_terms($trip_id, 'togo_trip_durations');
        if (!empty($terms_durations) && !is_wp_error($terms_durations)) {
            echo '<div class="trip-duration">';
            echo '<div class="togo-tooltip">';
            echo \Togo\Icon::get_svg('clock-circle');
            echo '<div class="togo-tooltip-content">';
            echo '<p>' . esc_html__('Duration', 'togo-framework') . '</p>';
            echo '</div>';
            echo '</div>';
            echo '<span class="trip-duration-text">' . $terms_durations[0]->name . '</span>';
            echo '</div>';
        }
    }

    public static function render_trip_guests($trip_id)
    {
        $trip_card_enable_guests = \Togo\Helper::setting('trip_card_enable_guests');
        if (empty($trip_id)) return;
        if ($trip_card_enable_guests == 'no') return;
        $trip_min_guest = get_post_meta($trip_id, 'trip_minimum_guests', true) ? get_post_meta($trip_id, 'trip_minimum_guests', true) : 0;
        $trip_max_guest = get_post_meta($trip_id, 'trip_maximum_guests', true);

        if (!empty($trip_max_guest)) {
            echo '<div class="trip-guests">';
            echo '<div class="togo-tooltip">';
            echo \Togo\Icon::get_svg('users-group');
            echo '<div class="togo-tooltip-content">';
            echo '<p>' . esc_html__('Group size', 'togo-framework') . '</p>';
            echo '</div>';
            echo '</div>';
            echo '<span class="trip-guest-text">' . $trip_min_guest . ' - ' . $trip_max_guest . '</span>';
            echo '</div>';
        }
    }

    public static function render_trip_types($trip_id)
    {
        $trip_card_enable_tour_type = apply_filters('togo_trip_card_enable_tour_type', \Togo\Helper::setting('trip_card_enable_tour_type'));
        $trip_card_layout = apply_filters('togo_trip_card_layout', \Togo\Helper::setting('trip_card_layout'));
        if (empty($trip_id)) return;
        if ($trip_card_layout == 'grid') return;
        if ($trip_card_enable_tour_type == 'no') return;
        $terms_types = wp_get_post_terms($trip_id, 'togo_trip_types');
        if (!empty($terms_types) && !is_wp_error($terms_types)) {
            echo '<div class="trip-types">';
            echo '<div class="togo-tooltip">';
            echo \Togo\Icon::get_svg('box');
            echo '<div class="togo-tooltip-content">';
            echo '<p>' . esc_html__('Tour type', 'togo-framework') . '</p>';
            echo '</div>';
            echo '</div>';
            echo '<div class="trip-types-list">';
            foreach ($terms_types as $term) {
                echo '<a href="' . get_term_link($term) . '">' . $term->name . '</a>';
            }
            echo '</div>';
            echo '</div>';
        }
    }

    public static function render_trip_price($trip_id, $html = true, $suffix = true)
    {
        if (empty($trip_id)) return;
        echo \Togo_Framework\Helper::get_price_of_trip($trip_id, $html, $suffix);
    }

    public static function render_trip_map_icon($trip_id)
    {
        $trip_card_enable_map = \Togo\Helper::setting('trip_card_enable_map');
        if (empty($trip_id)) return;
        if ($trip_card_enable_map == 'no') return;
        $trip_itinerary = get_post_meta($trip_id, 'trip_itinerary', true);
        if (!empty($trip_itinerary) && $trip_itinerary[0]['trip_itinerary_title'] != '') {
            echo '<div class="trip-map">';
            echo '<div class="togo-tooltip">';
            echo '<a href="#" class="show-map" data-trip-id="' . $trip_id . '">' . \Togo\Icon::get_svg('map') . '</a>';
            echo '<div class="togo-tooltip-content">';
            echo '<p>' . esc_html__('View maps', 'togo-framework') . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }

    public static function render_trip_button($trip_id)
    {
        $trip_card_enable_button = \Togo\Helper::setting('trip_card_enable_button');
        $trip_card_layout = apply_filters('togo_trip_card_layout', \Togo\Helper::setting('trip_card_layout'));
        if (empty($trip_id)) return;
        if ($trip_card_layout == 'grid') return;
        if ($trip_card_enable_button == 'no') return;
        echo '<div class="trip-button">';
        echo '<a href="' . get_permalink($trip_id) . '" class="togo-button full-filled">' . esc_html__('View tour', 'togo-framework') . '</a>';
        echo '</div>';
    }

    public static function render_trip_map_button($trip_id)
    {
        $trip_card_enable_map = \Togo\Helper::setting('trip_card_enable_map');
        if (empty($trip_id)) return;
        if ($trip_card_enable_map == 'no') return;
        $trip_itinerary = get_post_meta($trip_id, 'trip_itinerary', true);
        if (!empty($trip_itinerary) && $trip_itinerary[0]['trip_itinerary_title'] != '') {
            echo '<div class="trip-map">';
            echo '<a href="#" class="show-map togo-button line" data-trip-id="' . $trip_id . '">' . \Togo\Icon::get_svg('map') . '<span>' . esc_html__('View maps', 'togo-framework') . '</span>' . '</a>';
            echo '</div>';
        }
    }

    public static function render_calendar($data_dates = [], $full_date = false)
    {
    ?>
        <div
            class="calendar-wrapper <?php echo $full_date ? 'full-date' : ''; ?>"
            data-dates='<?php echo json_encode($data_dates); ?>'
            data-months-name='<?php echo json_encode([
                                    esc_html__("January", 'togo-framework'),
                                    esc_html__("February", 'togo-framework'),
                                    esc_html__("March", 'togo-framework'),
                                    esc_html__("April", 'togo-framework'),
                                    esc_html__("May", 'togo-framework'),
                                    esc_html__("June", 'togo-framework'),
                                    esc_html__("July", 'togo-framework'),
                                    esc_html__("August", 'togo-framework'),
                                    esc_html__("September", 'togo-framework'),
                                    esc_html__("October", 'togo-framework'),
                                    esc_html__("November", 'togo-framework'),
                                    esc_html__("December", 'togo-framework'),
                                ]); ?>'>
            <div class="calendar-inner">
                <div class="calendar-container">
                    <div class="calendar" id="calendar-prev">
                        <div class="calendar-header">
                            <button class="prev-month"><?php echo \Togo\Icon::get_svg('chevron-left'); ?></button>
                            <h6 id="month-year-prev">1</h6>
                            <button class="next-month"><?php echo \Togo\Icon::get_svg('chevron-right'); ?></button>
                            <span></span>
                        </div>
                        <div class="calendar-days">
                            <div class="day-name"><?php echo esc_html__('Mon', 'togo'); ?></div>
                            <div class="day-name"><?php echo esc_html__('Tue', 'togo'); ?></div>
                            <div class="day-name"><?php echo esc_html__('Wed', 'togo'); ?></div>
                            <div class="day-name"><?php echo esc_html__('Thu', 'togo'); ?></div>
                            <div class="day-name"><?php echo esc_html__('Fri', 'togo'); ?></div>
                            <div class="day-name"><?php echo esc_html__('Sat', 'togo'); ?></div>
                            <div class="day-name"><?php echo esc_html__('Sun', 'togo'); ?></div>
                        </div>
                        <div class="calendar-dates" id="calendar-dates-prev">
                            <!-- Previous month dates will be populated here -->
                        </div>
                    </div>

                    <div class="calendar" id="calendar-next">
                        <div class="calendar-header">
                            <span></span>
                            <h6 id="month-year-next">2</h6>
                            <button class="next-month"><?php echo \Togo\Icon::get_svg('chevron-right'); ?></button>
                        </div>
                        <div class="calendar-days">
                            <div class="day-name"><?php echo esc_html__('Mon', 'togo'); ?></div>
                            <div class="day-name"><?php echo esc_html__('Tue', 'togo'); ?></div>
                            <div class="day-name"><?php echo esc_html__('Wed', 'togo'); ?></div>
                            <div class="day-name"><?php echo esc_html__('Thu', 'togo'); ?></div>
                            <div class="day-name"><?php echo esc_html__('Fri', 'togo'); ?></div>
                            <div class="day-name"><?php echo esc_html__('Sat', 'togo'); ?></div>
                            <div class="day-name"><?php echo esc_html__('Sun', 'togo'); ?></div>
                        </div>
                        <div class="calendar-dates" id="calendar-dates-next">
                            <!-- Next month dates will be populated here -->
                        </div>
                    </div>
                </div>
                <div class="calendar-actions">
                    <a href="#" class="calendar-check togo-button underline"><?php echo esc_html__('Apply', 'togo-framework'); ?></a>
                </div>
            </div>
        </div>
<?php
    }
}
