<div class="section-togo section-changelog">
    <div class="entry-heading">
        <h4><?php esc_html_e('Changelogs', 'togo'); ?></h4>
    </div>

    <div class="wrap-content">
        <table class="table-changelogs">
            <thead>
                <tr>
                    <th><?php esc_html_e('Version', 'togo'); ?></th>
                    <th><?php esc_html_e('Description', 'togo'); ?></th>
                    <th><?php esc_html_e('Date', 'togo'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php echo Togo_Panel::get_changelogs(true); ?>
            </tbody>
        </table>
    </div><!-- end .wrap-content -->
</div>