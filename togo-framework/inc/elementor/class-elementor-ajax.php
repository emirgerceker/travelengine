<?php

namespace Togo_Framework\Elementor;

defined('ABSPATH') || exit;

class Widget_Ajax
{

    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    function __construct()
    {
        add_action('wp_ajax_togo_search', [$this, 'togo_search']);
        add_action('wp_ajax_nopriv_togo_search', [$this, 'togo_search']);

        add_action('wp_ajax_togo_ajax_login', [$this, 'togo_ajax_login']);
        add_action('wp_ajax_nopriv_togo_ajax_login', [$this, 'togo_ajax_login']);

        add_action('wp_ajax_togo_ajax_register', [$this, 'togo_ajax_register']);
        add_action('wp_ajax_nopriv_togo_ajax_register', [$this, 'togo_ajax_register']);

        add_action('wp_ajax_togo_ajax_forgot_password', [$this, 'togo_ajax_forgot_password']);
        add_action('wp_ajax_nopriv_togo_ajax_forgot_password', [$this, 'togo_ajax_forgot_password']);

        add_action('wp_ajax_togo_trip_tab_content', [$this, 'togo_trip_tab_content']);
        add_action('wp_ajax_nopriv_togo_trip_tab_content', [$this, 'togo_trip_tab_content']);
    }

    public function togo_search()
    {
        $key = sanitize_text_field($_POST['key']);
        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';
        $number_of_items = isset($_POST['number_of_items']) ? sanitize_text_field($_POST['number_of_items']) : '3';
        $view_all_text = isset($_POST['view_all_text']) ? sanitize_text_field($_POST['view_all_text']) : '';
        $args = array(
            'post_type' => $post_type,
            's' => $key,
            'posts_per_page' => $number_of_items,
            'ignore_sticky_posts' => 1,
            'post_status' => 'publish'
        );
        $the_query = new \WP_Query($args);

        if ($the_query->have_posts()) {
            while ($the_query->have_posts()) {
                $the_query->the_post();
                if ($post_type == 'product') {
                    $product = wc_get_product(get_the_ID());
                    $price = $product->get_price();
                }
?>
                <div class="search-item">
                    <?php
                    if (has_post_thumbnail(get_the_ID())) :
                    ?>
                        <div class="search-item-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail(); ?>
                            </a>
                        </div>
                    <?php
                    endif;
                    ?>
                    <div class="search-item-info">
                        <h3 class="search-item-title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <?php if ($post_type == 'product') : ?>
                            <div class="search-item-price">
                                <?php echo wc_price($price); ?>
                            </div>
                        <?php else : ?>
                            <div class="search-item-description">
                                <?php echo wp_trim_words(get_the_excerpt(), 10); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
            }

            if (isset($view_all_text) && $view_all_text != '') {
                if ($post_type == 'product') {
                    $url = get_home_url() . '/shop?s=' . $key;
                } else {
                    $url = get_home_url() . '/?s=' . $key . '&post_type=' . $post_type;
                }
                echo '<div class="search-button"><a class="togo-button underline" href="' . esc_url($url) . '">' . $view_all_text . '</a></div>';
            }
            wp_reset_postdata();
        } else {
            ?>
            <div class="search-item">
                <?php esc_html_e('Nothing found', 'togo-framework'); ?>
            </div>
<?php
        }
        wp_reset_postdata();

        wp_die();
    }

    public function togo_ajax_login()
    {
        // Check nonce for security
        check_ajax_referer('ajax-login-nonce', 'security');

        // Get the posted values
        $username_or_email = $_POST['username'];
        $password = $_POST['password'];
        $remember = !empty($_POST['remember']) ? true : false;
        $username_empty = $_POST['username_empty'];
        $username_invalid = $_POST['username_invalid'];
        $password_empty = $_POST['password_empty'];
        $login_invalid = $_POST['login_invalid'];
        $login_success = $_POST['login_success'];
        $redirect_to = $_POST['redirect_to'] ? $_POST['redirect_to'] : home_url();

        if (empty($username_or_email)) {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => $username_empty
                )
            ));
            wp_die();
        }

        if (empty($password)) {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => $password_empty
                )
            ));
            wp_die();
        }

        // Determine if the input is an email address
        if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
            $user = get_user_by('email', $username_or_email);
        } else {
            $user = get_user_by('login', $username_or_email);
        }

        if (!$user) {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => $username_invalid
                )
            ));
            wp_die();
        }

        // Get POST variables
        $info = array();
        $info['user_login'] = $user->user_login;
        $info['user_password'] = $password;
        $info['remember'] = $remember;

        // Attempt to sign in the user
        $user_signon = wp_signon($info, false);

        if (is_wp_error($user_signon)) {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => $login_invalid
                )
            ));
        } else {
            echo json_encode(array(
                'success' => true,
                'data'    => array(
                    'message'     => $login_success,
                    'redirect_url' => $redirect_to
                )
            ));
        }

        wp_die();
    }

    public function togo_ajax_register()
    {
        // Check nonce for security
        check_ajax_referer('ajax-register-nonce', 'security');

        $username = sanitize_user($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        $username_empty = sanitize_text_field($_POST['username_empty']);
        $username_invalid = sanitize_text_field($_POST['username_invalid']);
        $email_empty = sanitize_text_field($_POST['email_empty']);
        $email_invalid = sanitize_text_field($_POST['email_invalid']);
        $minimum_password_length = sanitize_text_field($_POST['minimum_password_length']);
        $password_empty = sanitize_text_field($_POST['password_empty']);
        $password_length = sanitize_text_field($_POST['password_length']);
        $password_confirm_empty = sanitize_text_field($_POST['password_confirm_empty']);
        $password_confirm_invalid = sanitize_text_field($_POST['password_confirm_invalid']);
        $register_success = sanitize_text_field($_POST['register_success']);
        $redirect_to = $_POST['redirect_to'] ? $_POST['redirect_to'] : home_url();
        $email_to_admin = sanitize_text_field($_POST['email_to_admin']);
        $email_to_user = sanitize_text_field($_POST['email_to_user']);
        $admin_email_subject = sanitize_text_field($_POST['admin_email_subject']);
        $admin_email_template = $_POST['admin_email_template'];
        $user_email_subject = sanitize_text_field($_POST['user_email_subject']);
        $user_email_template = $_POST['user_email_template'];

        $password_length = str_replace('{{minimum_password_length}}', $minimum_password_length, $password_length);

        if (empty($username)) {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => $username_empty
                )
            ));
            wp_die();
        }

        if (empty($email)) {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => $email_empty
                )
            ));
            wp_die();
        }

        if (empty($password)) {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => $password_empty
                )
            ));
            wp_die();
        }

        if (strlen($password) < $minimum_password_length) {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => $password_length
                )
            ));
            wp_die();
        }

        if (empty($password_confirm)) {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => $password_confirm_empty
                )
            ));
            wp_die();
        }

        if (username_exists($username)) {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => $username_invalid
                )
            ));
            wp_die();
        }

        if (email_exists($email)) {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => $email_invalid
                )
            ));
            wp_die();
        }

        if ($password !== $password_confirm) {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => $password_confirm_invalid
                )
            ));
            wp_die();
        }

        $user_id = wp_create_user($username, $password, $email);

        if (!is_wp_error($user_id)) {
            $user = get_user_by('ID', $user_id);
            if ($email_to_admin == 'yes') {
                $to = get_bloginfo('admin_email');
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $replace = array(
                    '{{user_login}}' => $user->user_login,
                    '{{user_email}}' => $user->user_email,
                    '{{user_password}}' => $password
                );
                $message = str_replace(array_keys($replace), array_values($replace), $admin_email_template);
                wp_mail($to, $admin_email_subject, $message, $headers);
            }

            if (
                $email_to_user == 'yes'
            ) {
                $to = $user->user_email;
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $replace = array(
                    '{{user_login}}' => $user->user_login,
                    '{{user_email}}' => $user->user_email,
                    '{{user_password}}' => $password
                );
                $message = str_replace(array_keys($replace), array_values($replace), $user_email_template);
                wp_mail($to, $user_email_subject, $message, $headers);
            }

            // Get POST variables
            $info = array();
            $info['user_login'] = $user->user_login;
            $info['user_password'] = $password;
            $info['remember'] = true;

            // Attempt to sign in the user
            wp_signon($info, false);

            echo json_encode(array(
                'success' => true,
                'data'    => array(
                    'message' => $register_success,
                    'redirect_url' => $redirect_to
                )
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => esc_html__('Registration failed: ', 'togo-framework') . $user_id->get_error_message()
                )
            ));
        }

        wp_die();
    }

    function togo_ajax_forgot_password()
    {
        // Check nonce for security
        check_ajax_referer('ajax-forgot-password-nonce', 'security');

        // Get the posted data
        $user_login = sanitize_text_field($_POST['user_login']);
        $user_empty_error = sanitize_text_field($_POST['user_empty_error']);
        $user_invalid_error = sanitize_text_field($_POST['user_invalid_error']);
        $reset_link_error = sanitize_text_field($_POST['reset_link_error']);
        $send_password_error = sanitize_text_field($_POST['send_password_error']);
        $send_password_success = sanitize_text_field($_POST['send_password_success']);
        $email_subject = sanitize_text_field($_POST['email_subject']);
        $email_template = $_POST['email_template'];

        if (empty($user_login)) {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => $user_empty_error
                )
            ));
            wp_die();
        }

        if (is_email($user_login)) {
            $user_data = get_user_by('email', $user_login);
        } else {
            $user_data = get_user_by('login', $user_login);
        }

        if (!$user_data) {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => $user_invalid_error
                )
            ));
            wp_die();
        }

        // Generate a key for password reset and send an email
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;

        $key = get_password_reset_key($user_data);

        if (is_wp_error($key)) {
            echo json_encode(array(
                'success' => false,
                'data'    => array(
                    'message' => $reset_link_error
                )
            ));
            wp_die();
        }

        $reset_url = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');

        if (!empty($email_template)) {

            $message = $email_template;

            $replace = array(
                '{{reset_link}}' => $reset_url,
                '{{user_login}}' => $user_login,
                '{{user_email}}' => $user_email
            );
            $message = str_replace(array_keys($replace), array_values($replace), $message);

            $headers = array('Content-Type: text/html; charset=UTF-8');
            $mail = wp_mail($user_email, $email_subject, $message, $headers);

            if (!$mail) {
                echo json_encode(array(
                    'success' => false,
                    'data'    => array(
                        'message' => $send_password_error
                    )
                ));
                wp_die();
            }
        }


        echo json_encode(array(
            'success' => true,
            'data'    => array(
                'message' => $send_password_success
            )
        ));

        wp_die();
    }

    function togo_trip_tab_content()
    {
        // Check nonce for security
        check_ajax_referer('togo_trip_tab_nonce', 'security');

        $data_tab = isset($_POST['data_tab']) ? json_decode(stripslashes($_POST['data_tab']), true) : array();
        $posts_per_page = isset($data_tab['posts_per_page']) ? intval($data_tab['posts_per_page']) : 10;
        $destination = isset($data_tab['destination']) ? sanitize_text_field($data_tab['destination']) : '';
        $order = isset($data_tab['order']) ? sanitize_text_field($data_tab['order']) : 'DESC';
        $orderby = isset($data_tab['orderby']) ? sanitize_text_field($data_tab['orderby']) : 'date';
        $layout = isset($data_tab['layout']) ? sanitize_text_field($data_tab['layout']) : 'grid';
        $image_size = isset($data_tab['image_size']) ? sanitize_text_field($data_tab['image_size']) : '500x420';

        // Generate a cache key based on the parameters
        $cache_key = 'togo_trip_tab_' . md5(serialize([$destination, $order, $orderby, $layout, $image_size]));

        // Try to get cached HTML
        $html = get_transient($cache_key);

        if ($html === false) {
            $args = array(
                'post_type' => 'togo_trip',
                'posts_per_page' => $posts_per_page,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'togo_trip_destinations',
                        'field' => 'slug',
                        'terms' => $destination,
                    ),
                ),
                'orderby' => $orderby,
                'order' => $order,
            );

            $query = new \WP_Query($args);
            ob_start();
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $trip_id = get_the_ID();
                    \Togo_Framework\Helper::togo_get_template('content/trip/trip-grid-' . $layout . '.php', ['trip_id' => $trip_id, 'image_size' => $image_size]);
                }
            } else {
                echo '<div class="no-results">' . esc_html__('No trips found', 'togo-framework') . '</div>';
            }
            wp_reset_postdata();
            $html = ob_get_clean();

            // Cache for 5 minutes
            set_transient($cache_key, $html, 5 * MINUTE_IN_SECONDS);
        }

        echo $html;
        wp_die();
    }
}
