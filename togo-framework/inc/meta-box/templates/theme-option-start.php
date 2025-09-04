<div class="uxper-theme-options-wrapper wrap">
	<h2 class="screen-reader-text"><?php echo esc_html($page_title) ?></h2>
	<?php do_action("uxper/{$option_name}-theme-option-form/before") ?>
	<div class="area-theme-options inside">
		<form action="#" method="post" enctype="multipart/form-data">
			<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce( $option_name); ?>" />
			<input type="hidden" id="_current_page" name="_current_page" value="<?php echo esc_attr($page); ?>"/>
			<div class="uxper-theme-options-title">
				<h1><?php echo esc_html($page_title) ?></h1>

				<div class="uxper-theme-options-action">
					<button class="button button-primary uxper-theme-options-save-options" type="submit" name="uxper_save_option"><?php esc_html_e('Save Options','uxper-booking'); ?></button>
				</div>
			</div>
			<div class="uxper-meta-box-wrap">
				<?php uxper_get_template('templates/theme-option-section', array('list_section' => $list_section)) ?>
				<div class="uxper-fields">
					<div class="uxper-fields-wrapper">