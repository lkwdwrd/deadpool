<?php
/**
 * Various cleanup functions.
 */

namespace Deadpool\CleanUp;

/**
 * Sets up this file with the WordPress API.
 *
 * @return void
 */
function load() {
	add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup' );
}

/**
 * Sets up this file with the WordPress API.
 *
 * @return void
 */
function setup() {
	add_filter( 'comments_open', '__return_false' );
	add_filter( 'pings_open', '__return_false' );
	add_filter( 'xmlrpc_methods', __NAMESPACE__ . '\\pingbacks' );
	add_filter( 'wp_headers', __NAMESPACE__ . '\\pingback_header' );
	add_filter( 'rewrite_rules_array', __NAMESPACE__ . '\\trackback_rewrites' );
	add_filter( 'xmlrpc_enabled', '__return_false' );
	add_action( 'template_redirect', __NAMESPACE__ . '\\attachments', 1 );
	add_filter( 'attachment_link', '__return_empty_string' );
	add_action( 'current_screen', __NAMESPACE__ . '\\admin_pages' );
	add_action( 'admin_menu', __NAMESPACE__ . '\\menus' );
	add_action( 'wp_before_admin_bar_render', __NAMESPACE__ . '\\admin_bar' );
	add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\\dashboard_widgets' );
	add_action( 'customize_register', __NAMESPACE__ . '\\customizer_sections' );
	add_filter( 'rest_authentication_errors', __NAMESPACE__ . '\\rest_api_auth' );
	add_filter( 'pre_option_default_pingback_flag', '__return_zero' );
	add_filter( 'pre_option_default_ping_status', function() {
		return 'closed';
	} );
	add_filter( 'pre_option_default_comment_status', function() {
		return 'closed';
	} );
}

/**
 * Remove the pingback XML-RPC methods.
 *
 * @param  array $methods An array of XML-RPC methods.
 * @return array
 */
function pingbacks( $methods ) {
	unset( $methods['pingback.ping'] );
	unset( $methods['pingback.extensions.getPingbacks'] );

	return $methods;
}

/**
 * Remove the X-Pingback HTTP Header.
 *
 * @param array $headers Current headers
 * @return array
 */
function pingback_header( $headers ) {
	if ( isset( $headers['X-Pingback'] ) ) {
		unset( $headers['X-Pingback'] );
	}

	return $headers;
}

/**
 * Remove /trackback/ from any rewrites.
 *
 * @param array $rules The compiled array of rewrite rules
 * @return array
 */
function trackback_rewrites( $rules ) {
	foreach ( $rules as $rule => $rewrite ) {
		if ( false !== strpos( $rule, 'trackback' ) ) {
			unset( $rules[ $rule ] );
		}
	}

	return $rules;
}

/**
 * If a single attachment page is accessed, redirect it to the home page.
 *
 * @return void
 */
function attachments() {
	if ( ! is_attachment() ) {
		return;
	}
	wp_safe_redirect( home_url() );
	die();
}

/**
 * Restrict access to some admin pages.
 *
 * @return void
 */
function admin_pages() {
	$redirect = false;
	$screen   = get_current_screen();

	if ( isset( $screen->post_type ) && ( 'post' === $screen->post_type || 'page' === $screen->post_type ) ) {
		$redirect = true;
	}

	if ( isset( $screen->id ) && ( 'edit-comments' === $screen->id || 'comment' === $screen->id || 'options-discussion' === $screen->id ) ) {
		$redirect = true;
	}

	if ( $redirect ) {
		wp_safe_redirect( admin_url( '/' ) );
	}
}

/**
 * Remove menus from the sidebar.
 *
 * @return void
 */
function menus() {
	remove_menu_page( 'edit.php' );
	remove_menu_page( 'edit.php?post_type=page' );
	remove_menu_page( 'edit-comments.php' );
	remove_submenu_page( 'options-general.php', 'options-discussion.php' );
}

/**
 * Remove items from the admin bar menu.
 *
 * @return void
 */
function admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_node( 'new-post' );
	$wp_admin_bar->remove_node( 'new-page' );
	$wp_admin_bar->remove_menu( 'comments' );
}

/**
 * Remove some default dashboard widgets.
 *
 * @return void
 */
function dashboard_widgets() {
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
}

/**
 * Remove some default sections from the Customizer.
 *
 * @param \WP_Customize_Manager $wp_customize WP_Customize_Manager instance
 */
function customizer_sections( $wp_customize ) {
	$wp_customize->remove_section( 'static_front_page' );
	$wp_customize->remove_section( 'custom_css' );
}

/**
 * Require authentication for all REST API requests.
 *
 * @param  \WP_Error|null|bool $result
 * @return \WP_Error|null|bool
 */
function rest_api_auth( $result ) {
	if ( ! empty( $result ) ) {
		return $result;
	}

	if ( ! is_user_logged_in() ) {
		return new \WP_Error(
			'rest_not_logged_in',
			'You are not currently logged in.',
			[
				'status' => 401,
			]
		);
	}

	return $result;
}
