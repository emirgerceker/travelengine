<div class="wrap togo-wrap">
    <div class="togo-body">
        <div class="box">
            <div class="box-header">
                <?php esc_html_e('Export Demo Data', 'togo'); ?>
            </div>
            <div class="box-body">
                <table class="table">
                    <tbody>
                        <tr valign="middle">
                            <td>
                                <?php esc_html_e('Content', 'togo'); ?>
                            </td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="export_option" value="content" />
                                    <input type='submit' value='<?php echo esc_attr__('Export', 'togo'); ?>' name='export' class='button' />
                                </form>
                            </td>
                        </tr>
                        <tr valign="middle">
                            <td>
                                <?php esc_html_e('Widgets', 'togo'); ?>
                            </td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="export_option" value="widgets" />
                                    <input type="submit" value="<?php echo esc_attr__('Export', 'togo'); ?>" name="export" class="button button-primary" />
                                </form>
                            </td>
                        </tr>
                        <tr valign="middle">
                            <td>
                                <?php esc_html_e('Menus', 'togo'); ?>
                            </td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="export_option" value="menus" />
                                    <input type="submit" value="<?php echo esc_attr__('Export', 'togo'); ?>" name="export" class="button button-primary" />
                                </form>
                            </td>
                        </tr>
                        <tr valign="middle">
                            <td>
                                <?php esc_html_e('Page Options', 'togo'); ?>
                            </td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="export_option" value="page_options" />
                                    <input type="submit" value="<?php echo esc_attr__('Export', 'togo'); ?>" name="export" class="button button-primary" />
                                </form>
                            </td>
                        </tr>
                        <?php if (class_exists('WooCommerce')) { ?>
                            <tr valign="middle">
                                <td>
                                    <?php esc_html_e('WooCommerce', 'togo'); ?>
                                </td>
                                <td>
                                    <form method="post" action="">
                                        <input type="hidden" name="export_option" value="woocommerce" />
                                        <input type="submit" value="<?php echo esc_attr__('Export', 'togo'); ?>" name="export" class="button button-primary" />
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if (defined('ELEMENTOR_VERSION')) { ?>
                            <tr valign="middle">
                                <td>
                                    <?php esc_html_e('Elementor', 'togo'); ?>
                                </td>
                                <td>
                                    <form method="post" action="">
                                        <input type="hidden" name="export_option" value="elementor" />
                                        <input type="submit" value="<?php echo esc_attr__('Export', 'togo'); ?>"
                                            name="export"
                                            class="button button-primary" />
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if (class_exists('Uxper_Booking')) { ?>
                            <tr valign="middle">
                                <td>
                                    <?php esc_html_e('Uxper Booking', 'togo'); ?>
                                </td>
                                <td>
                                    <form method="post" action="">
                                        <input type="hidden" name="export_option" value="uxper_booking" />
                                        <input type="submit" value="<?php echo esc_attr__('Export', 'togo'); ?>" name="export" class="button button-primary" />
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if (class_exists('RevSliderAdmin')) { ?>
                            <tr valign="middle">
                                <td>
                                    <?php esc_html_e('Revolution Slider', 'togo'); ?>
                                </td>
                                <td>
                                    <form method="post" action="">
                                        <input type="hidden" name="export_option" value="rev_sliders" />
                                        <input type="submit" value="<?php echo esc_attr__('Export', 'togo'); ?>" name="export" class="button button-primary" />
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>