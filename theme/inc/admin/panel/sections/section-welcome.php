<div class="section-togo section-welcome">
    <div class="entry-heading">
        <div class="theme-logo"></div>

        <div class="inner-heading">
            <h3><?php esc_html_e('Welcome to Togo Theme', 'togo'); ?></h3>
            <p><?php esc_html_e("We've assembled some links to get you started", 'togo'); ?></p>
        </div>
    </div>

    <div class="wrap-column wrap-column-3 col-started">
        <div class="panel-column">
            <div class="entry-heading">
                <h4><?php esc_html_e('Get Started', 'togo'); ?></h4>
            </div>
            <div class="entry-detail">
                <a href="<?php echo esc_url(admin_url('customize.php')); ?>"><?php esc_html_e('Customize your site', 'togo'); ?></a>
            </div>
        </div>

        <div class="panel-column col-update">
            <div class="entry-heading">
                <h4>
                    <?php esc_html_e('Update', 'togo'); ?>
                </h4>
            </div>
            <div class="entry-detail">
                <div class="box-detail">
                    <span class="entry-title"><?php esc_html_e('Current Version', 'togo'); ?></span>
                    <p><?php echo esc_html(TOGO_THEME_VERSION); ?></p>
                </div>
                <div class="box-detail">
                    <span class="entry-title">
                        <?php esc_html_e('Lastest Version', 'togo'); ?>

                        <?php
                        $update = Togo_Panel::check_theme_update();
                        $new_version = isset($update['new_version']) ? $update['new_version'] : TOGO_THEME_VERSION;

                        if (Togo_Panel::check_valid_update() && $update) {

                            printf(__('<a class="button togo-update" href="%1$s" %2$s>Update now</a>', 'togo'), wp_nonce_url(self_admin_url('update.php?action=upgrade-theme&theme=') . TOGO_THEME_SLUG, 'upgrade-theme_' . TOGO_THEME_SLUG), sprintf('id="update-theme" aria-label="%s"', esc_attr(sprintf(__('Update %s now', 'togo'), TOGO_THEME_NAME))));
                        }
                        ?>
                    </span>
                    <p><?php echo esc_html($new_version); ?></p>
                </div>
            </div>
        </div>

        <div class="panel-column col-support">
            <div class="entry-heading">
                <h4><?php esc_html_e('Support', 'togo'); ?></h4>
            </div>
            <div class="entry-detail">
                <div class="box-detail">
                    <a class="entry-title" href="<?php echo esc_attr($get_info['docs']); ?>" target="_blank"><?php esc_html_e('Online Documentation', 'togo'); ?></a>
                    <p><?php esc_html_e('Detailed instruction to get the right way with our theme.', 'togo'); ?></p>
                </div>
                <div class="box-detail">
                    <a class="entry-title" href="<?php echo esc_attr($get_info['support']); ?>" target="_blank"><?php esc_html_e('Request Support', 'togo'); ?></a>
                    <p><?php esc_html_e('Need help? Our users enjoy premium 24/7 support.', 'togo'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>