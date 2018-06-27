<?php
/**
 * Plugin Name: Gutenberg Widgets
 * Description: Adds a Gutenberg block to display a widget area or sidebar.
 * Author: Human Made Limited
 * License: GPL-3.0
 */

namespace HM\Gutenberg\Widgets;

require_once __DIR__ . '/inc/namespace.php';

add_action( 'plugins_loaded', __NAMESPACE__ . '\\setup' );
