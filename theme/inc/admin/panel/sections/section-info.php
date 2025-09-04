<?php
add_thickbox();
function togo_let_to_num($size)
{
    $l   = substr($size, -1);
    $ret = substr($size, 0, -1);
    switch (strtoupper($l)) {
        case 'P':
            $ret *= 1024;
        case 'T':
            $ret *= 1024;
        case 'G':
            $ret *= 1024;
        case 'M':
            $ret *= 1024;
        case 'K':
            $ret *= 1024;
    }

    return $ret;
}

?>
<div class="section-info">
    <div class="about-wrap box">
        <div class="box-header">
            <span class="icon"><i class="fab fa-wordpress-simple"></i></span>
            <?php esc_html_e('WordPress Environment', 'togo'); ?>
        </div>
        <div class="box-body">
            <table class="wp-list-table widefat striped system" cellspacing="0">
                <tbody>
                    <tr>
                        <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('The URL of your site\'s homepage.', 'togo') . '">[?]</a>'; ?></td>
                        <td class="title"><?php _e('Home URL', 'togo'); ?></td>
                        <td><?php form_option('home'); ?></td>
                    </tr>
                    <tr>
                        <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('The root URL of your site.', 'togo') . '">[?]</a>'; ?></td>
                        <td class="title"><?php _e('Site URL', 'togo'); ?></td>
                        <td><?php form_option('siteurl'); ?></td>
                    </tr>
                    <tr>
                        <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('The version of WordPress installed on your site.', 'togo') . '">[?]</a>'; ?></td>
                        <td class="title"><?php _e('WP Version', 'togo'); ?></td>
                        <td><?php bloginfo('version'); ?></td>
                    </tr>
                    <tr>
                        <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('Whether or not you have WordPress Multisite enabled.', 'togo') . '">[?]</a>'; ?></td>
                        <td class="title"><?php _e('WP Multisite', 'togo'); ?></td>
                        <td>
                            <?php if (is_multisite()) {
                                echo '&#10004;';
                            } else {
                                echo '&ndash;';
                            } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('The maximum amount of memory (RAM) that your site can use at one time.', 'togo') . '">[?]</a>'; ?></td>
                        <td class="title"><?php _e('WP Memory Limit', 'togo'); ?></td>
                        <td>
                            <?php
                            $memory = togo_let_to_num(WP_MEMORY_LIMIT);

                            if (function_exists('memory_get_usage')) {
                                $server_memory = togo_let_to_num(@ini_get('memory_limit'));
                                $memory        = max($memory, $server_memory);
                            }

                            if ($memory < 134217728) {
                                echo '<mark class="error">' . sprintf(__('%s - We recommend setting memory to at least 128MB. See: <a href="%s" target="_blank">Increasing memory allocated to PHP</a>', 'togo'), size_format($memory), 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP') . '</mark>';
                            } else {
                                echo '<mark class="yes">' . size_format($memory) . '</mark>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('Displays whether or not WordPress is in Debug Mode.', 'togo') . '">[?]</a>'; ?></td>
                        <td class="title"><?php _e('WP Debug Mode', 'togo'); ?></td>
                        <td>
                            <?php if (defined('WP_DEBUG') && WP_DEBUG) {
                                echo '<mark class="yes">&#10004;</mark>';
                            } else {
                                echo '&ndash;';
                            } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('The current language used by WordPress. Default = English', 'togo') . '">[?]</a>'; ?></td>
                        <td class="title"><?php _e('Language', 'togo'); ?></td>
                        <td><?php echo get_locale() ?></td>
                    </tr>
                    <tr>
                        <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('The current theme name', 'togo') . '">[?]</a>'; ?></td>
                        <td class="title"><?php _e('Theme Name', 'togo'); ?></td>
                        <td><?php echo esc_html(TOGO_THEME_NAME); ?></td>
                    </tr>
                    <tr>
                        <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('The current theme version', 'togo') . '">[?]</a>'; ?></td>
                        <td class="title"><?php _e('Theme Version', 'togo'); ?></td>
                        <td><?php echo esc_html(TOGO_THEME_VERSION); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="about-wrap box">
        <div class="box-header">
            <span class="icon"><i class="fal fa-server"></i></span>
            <?php esc_html_e('Server Environment', 'togo'); ?>
        </div>
        <div class="box-body">
            <table class="wp-list-table widefat striped system" cellspacing="0">
                <tbody>
                    <tr>
                        <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('The version of PHP installed on your hosting server.', 'togo') . '">[?]</a>'; ?></td>
                        <td class="title"><?php _e('PHP Version', 'togo'); ?></td>
                        <td><?php if (function_exists('phpversion')) {
                                $php_version = esc_html(phpversion());

                                if (version_compare($php_version, '5.6', '<')) {
                                    echo '<mark class="error">' . esc_html__('Theme requires PHP version 5.6 or greater. Please contact your hosting provider to upgrade PHP version.', 'togo') . '</mark>';
                                } else {
                                    echo esc_html($php_version);
                                }
                            }
                            ?></td>
                    </tr>
                    <?php if (function_exists('ini_get')) : ?>
                        <tr>
                            <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('The largest filesize that can be contained in one post.', 'togo') . '">[?]</a>'; ?></td>
                            <td class="title"><?php _e('PHP Post Max Size', 'togo'); ?></td>
                            <td><?php echo size_format(togo_let_to_num(ini_get('post_max_size'))); ?></td>
                        </tr>
                        <tr>
                            <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups)', 'togo') . '">[?]</a>'; ?></td>
                            <td class="title"><?php _e('PHP Time Limit', 'togo'); ?></td>
                            <td><?php
                                $time_limit = ini_get('max_execution_time');

                                if ($time_limit > 0 && $time_limit < 180) {
                                    echo '<mark class="error">' . sprintf(__('%s - We recommend setting max execution time to at least 180. See: <a href="%s" target="_blank">Increasing max execution to PHP</a>', 'togo'), $time_limit, 'http://codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceeded') . '</mark>';
                                } else {
                                    echo '<mark class="yes">' . $time_limit . '</mark>';
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('The maximum number of variables your server can use for a single function to avoid overloads.', 'togo') . '">[?]</a>'; ?></td>
                            <td class="title"><?php _e('PHP Max Input Vars', 'togo'); ?></td>
                            <td><?php
                                $max_input_vars = ini_get('max_input_vars');

                                if ($max_input_vars < 5000) {
                                    echo '<mark class="error">' . sprintf(__('%s - Max input vars limitation will truncate POST data such as menus. Required >= 5000', 'togo'), $max_input_vars) . '</mark>';
                                } else {
                                    echo '<mark class="yes">' . $max_input_vars . '</mark>';
                                }
                                ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('The version of MySQL installed on your hosting server.', 'togo') . '">[?]</a>'; ?></td>
                        <td class="title"><?php _e('MySQL Version', 'togo'); ?></td>
                        <td>
                            <?php
                            global $wpdb;
                            echo esc_html($wpdb->db_version());
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('The largest filesize that can be uploaded to your WordPress installation.', 'togo') . '">[?]</a>'; ?></td>
                        <td class="title"><?php _e('Max Upload Size', 'togo'); ?></td>
                        <td><?php echo size_format(wp_max_upload_size()); ?></td>
                    </tr>
                    <tr>
                        <td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__('The default timezone for your server.', 'togo') . '">[?]</a>'; ?></td>
                        <td class="title"><?php _e('Default Timezone is UTC', 'togo'); ?></td>
                        <td><?php
                            $default_timezone = date_default_timezone_get();
                            if ('UTC' !== $default_timezone) {
                                echo '<mark class="error">&#10005; ' . sprintf(__('Default timezone is %s - it should be UTC', 'togo'), $default_timezone) . '</mark>';
                            } else {
                                echo '<mark class="yes">&#10004;</mark>';
                            } ?>
                        </td>
                    </tr>
                    <?php
                    $checks = array();
                    // fsockopen/cURL
                    $checks['fsockopen_curl']['name'] = 'fsockopen/cURL';
                    $checks['fsockopen_curl']['help'] = '<a href="#" class="hint--right" aria-label="' . esc_attr__('Plugins may use it when communicating with remote services.', 'togo') . '">[?]</a>';
                    if (function_exists('fsockopen') || function_exists('curl_init')) {
                        $checks['fsockopen_curl']['success'] = true;
                    } else {
                        $checks['fsockopen_curl']['success'] = false;
                        $checks['fsockopen_curl']['note']    = __('Your server does not have fsockopen or cURL enabled. Please contact your hosting provider to enable it.', 'togo') . '</mark>';
                    }
                    // DOMDocument
                    $checks['dom_document']['name'] = 'DOMDocument';
                    $checks['dom_document']['help'] = '<a href="#" class="hint--right" aria-label="' . esc_attr__('WordPress Importer use DOMDocument.', 'togo') . '">[?]</a>';
                    if (class_exists('DOMDocument')) {
                        $checks['dom_document']['success'] = true;
                    } else {
                        $checks['dom_document']['success'] = false;
                        $checks['dom_document']['note']    = sprintf(__('Your server does not have <a href="%s">the DOM extension</a> class enabled. Please contact your hosting provider to enable it.', 'togo'), 'http://php.net/manual/en/intro.dom.php') . '</mark>';
                    }
                    // XMLReader
                    $checks['xml_reader']['name'] = 'XMLReader';
                    $checks['xml_reader']['help'] = '<a href="#" class="hint--right" aria-label="' . esc_attr__('WordPress Importer use XMLReader.', 'togo') . '">[?]</a>';
                    if (class_exists('XMLReader')) {
                        $checks['xml_reader']['success'] = true;
                    } else {
                        $checks['xml_reader']['success'] = false;
                        $checks['xml_reader']['note']    = sprintf(__('Your server does not have <a href="%s">the XMLReader extension</a> class enabled. Please contact your hosting provider to enable it.', 'togo'), 'http://php.net/manual/en/intro.xmlreader.php') . '</mark>';
                    }
                    // WP Remote Get Check
                    $checks['wp_remote_get']['name'] = __('Remote Get', 'togo');
                    $checks['wp_remote_get']['help'] = '<a href="#" class="hint--right" aria-label="' . esc_attr__('Retrieve the raw response from the HTTP request using the GET method.', 'togo') . '">[?]</a>';
                    $response                        = wp_remote_get(TOGO_THEME_URI . '/assets/test.txt');

                    if (! is_wp_error($response) && $response['response']['code'] >= 200 && $response['response']['code'] < 300) {
                        $checks['wp_remote_get']['success'] = true;
                    } else {
                        $checks['wp_remote_get']['note'] = __(' WordPress function <a href="https://codex.wordpress.org/Function_Reference/wp_remote_get">wp_remote_get()</a> test failed. Please contact your hosting provider to enable it.', 'togo');
                        if (is_wp_error($response)) {
                            $checks['wp_remote_get']['note'] .= ' ' . sprintf(__('Error: %s', 'togo'), sanitize_text_field($response->get_error_message()));
                        } else {
                            $checks['wp_remote_get']['note'] .= ' ' . sprintf(__('Status code: %s', 'togo'), sanitize_text_field($response['response']['code']));
                        }
                        $checks['wp_remote_get']['success'] = false;
                    }
                    foreach ($checks as $check) {
                        $mark = ! empty($check['success']) ? 'yes' : 'error';
                    ?>
                        <tr>
                            <td class="help"><?php echo isset($check['help']) ? $check['help'] : ''; ?></td>
                            <td class="title"><?php echo esc_html($check['name']); ?></td>
                            <td>
                                <mark class="<?php echo esc_attr($mark); ?>">
                                    <?php echo ! empty($check['success']) ? '&#10004' : '&#10005'; ?><?php echo ! empty($check['note']) ? wp_kses_data($check['note']) : ''; ?>
                                </mark>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>