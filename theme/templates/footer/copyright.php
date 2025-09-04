<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 */

$copyright_text = togo_get_setting('footer_copyright_text');
if ($copyright_text) {
?>
	<div class="copyright-text">
		<p><?php echo esc_html($copyright_text); ?></p>
	</div>
<?php } ?>