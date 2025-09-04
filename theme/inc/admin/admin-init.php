<?php

/**
 * Togo Admin Engine Room.
 * This is where all Admin Functions run
 *
 * @package togo
 */

/**
 * Theme Panel
 */
require TOGO_THEME_DIR . '/inc/admin/panel/panel.php';

/**
 * Include Kirki
 */
require_once dirname(__FILE__) . '/kirki/kirki.php';

/**
 * Theme Customizer
 */
require_once TOGO_CUSTOMIZER_DIR . '/customizer.php';
