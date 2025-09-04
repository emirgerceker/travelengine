<?php
$togo_tgm_plugins       = apply_filters('togo_tgm_plugins', array());
$installed_plugins      = class_exists('TGM_Plugin_Activation') ? TGM_Plugin_Activation::$instance->plugins : array();
$required_plugins_count = 0;
?>
<div class="section-togo section-plugins">
    <div class="entry-heading">
        <h4><?php esc_html_e('Plugins', 'togo'); ?></h4>
        <p><?php esc_html_e('Please install and activate plugins to use all functionality.', 'togo'); ?></p>
    </div>

    <div class="wrap-content">
        <?php if (! empty($togo_tgm_plugins) && class_exists('TGM_Plugin_Activation')) : ?>
            <div class="list-item">
                <?php foreach ($togo_tgm_plugins as $plugin) : ?>
                    <?php
                    $plugin_obj = $installed_plugins[$plugin['slug']];

                    $css_class = '';
                    if ($plugin['required']) {
                        if (class_exists($plugin['slug'])) {
                            $css_class .= 'plugin-activated';
                        } else {
                            $css_class .= 'plugin-deactivated';
                        }
                    }

                    $thumb = isset($plugin['thumb']) ? esc_html($plugin['thumb']) : TOGO_THEME_URI . '/assets/images/placeholder.png';
                    $version = isset($plugin['version']) ? sprintf(__(' - %1$s', 'togo'), '<span class="version">' . $plugin['version'] . '</span>') : '';
                    ?>
                    <div class="item <?php echo esc_attr($css_class); ?>">
                        <div class="entry-detail">
                            <?php if ($thumb) : ?>
                                <div class="plugin-thumb">
                                    <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_html($plugin['name']); ?>">
                                </div>
                            <?php endif; ?>

                            <div class="plugin-name">
                                <div class="entry-name">
                                    <?php echo sprintf(__('%1$s %2$s', 'togo'), $plugin['name'], $version); ?>
                                </div>
                                <div class="plugin-type">
                                    <span><?php echo !empty($plugin['required']) ? esc_html__('(Required)', 'togo') : esc_html__('(Recommended)', 'togo'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="plugin-action">
                            <?php echo Togo_Panel::get_plugin_action($plugin_obj); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else : ?>

            <p><?php esc_html_e("This theme doesn't require any plugins.", 'togo'); ?></p>

        <?php endif; ?>

    </div><!-- end .wrap-content -->
</div>