<?php

namespace HM\Gutenberg\Widgets;

/**
 * Bootstrap the plugin.
 */
function setup() {
	// Add block assets.
	add_action( 'enqueue_block_editor_assets',  __NAMESPACE__ . '\\enqueue_editor_assets' );

	// Register blocks.
	add_action( 'init', __NAMESPACE__ . '\\register_blocks' );
}

/**
 * Enqueue block scripts.
 */
function enqueue_editor_assets() {
	global $wp_registered_sidebars;

	$plugin_dir_path  = dirname( __FILE__, 2 );
	$plugin_file_path = "{$plugin_dir_path}/plugin.php";

	$path = 'assets/dist/main.js';
	$src  = plugins_url( $path, $plugin_file_path );

	if ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) || ( defined( 'HM_GUTENBERG_WIDGETS_DEBUG' ) && HM_GUTENBERG_WIDGETS_DEBUG ) ) {
		$src = str_replace( content_url(), 'https://localhost:8884', $src );
		$ver = null;
	} else {
		$ver = filemtime( "{$plugin_dir_path}/{$path}" );
	}

	$handle = 'hm-gutenberg-widgets';
	$deps   = [
		'wp-i18n',
		'wp-blocks',
		'wp-components',
	];

	$data = [
		'widgetAreas' => array_values( $wp_registered_sidebars ),
		'canEdit'     => current_user_can( 'edit_theme_options' ),
	];

	$locale_data = gutenberg_get_jed_locale_data( 'gutenberg-widgets' );

	wp_enqueue_script( $handle, $src, $deps, $ver, false );
	wp_add_inline_script( $handle, sprintf( 'var HMGutenbergWidgetAreas = %s;', wp_json_encode( $data ) ), 'before' );
	wp_add_inline_script( $handle, sprintf( 'wp.i18n.setLocaleData( %s, "gutenberg-widgets" );', wp_json_encode( $locale_data ) ), 'before' );
}

/**
 * Register the widget area block for server side rendering.
 *
 * @return void
 */
function register_blocks() {
	register_block_type( 'hm/widget-area', [
		'attributes' => [
			'widgetArea' => [
				'type' => 'string',
			],
			'className' => [
				'type' => 'string',
			],
			'layout' => [
				'type' => 'string',
			],
		],
		'render_callback' => __NAMESPACE__ . '\\render_widget_area',
	] );
}

/**
 * Handle widget area block rendering.
 *
 * @param [type] $attributes
 * @return void
 */
function render_widget_area( $attributes ) {
	if ( empty( $attributes['widgetArea'] ) || ! is_dynamic_sidebar( $attributes['widgetArea'] ) ) {
		return;
	}

	ob_start();
	dynamic_sidebar( $attributes['widgetArea'] );
	$widget_area = ob_get_clean();

	$classes = [];

	if ( ! empty( $attributes['className'] ) ) {
		$classes[] = sanitize_html_class( $attributes['className'] );
	}

	if ( ! empty( $attributes['layout'] ) ) {
		$classes[] = sanitize_html_class( sprintf( 'layout-%s', $attributes['layout'] ) );
	}

	if ( ! empty( $classes ) ) {
		$widget_area = sprintf( '<div class="%s">%s</div>', implode( ' ', $classes ), $widget_area );
	}

	return $widget_area;
}
