<?php
/**
 * The main set up file for the theme.
 *
 * @package Deadpool
 */

namespace Deadpool\Core;

/**
 * Sets up this file with the WordPress API.
 *
 * @return void
 */
function load() {
	add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup' );
	add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup_theme', 100 );
}

/**
 * Register various methods with the WordPress API.
 *
 * @return void
 */
function setup() {
	add_action( 'die_setup_theme',  __NAMESPACE__ . '\\i18n' );
	add_action( 'die_setup_theme', __NAMESPACE__ . '\\theme_support' );
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\scripts' );
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\styles' );
}

/**
 * Fires the setup theme hooks for theme functionality to run on.
 *
 * @return void
 */
function setup_theme() {
	do_action( 'die_setup_theme' );
}

/**
 * Makes WP Theme available for translation.
 *
 * Translations can be added to the /languages directory for the hnf text
 * domain. These should be based off of the included .pot file.
 *
 * @return void
 */
function i18n() {
	load_theme_textdomain( 'die', DIE_PATH . '/languages' );
}

/**
 * Registers support for various core theme features.
 *
 * @return void.
 */
function theme_support() {
	$GLOBALS['content_width'] = 900;
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	] );
}

/**
 * Enqueue scripts for front-end.
 *
 * @return void
 */
function scripts() {
	wp_enqueue_script(
		'deadpool-main',
		DIE_TEMPLATE_URL . '/assets/js/dist/main.bundle.js',
		[],
		false,
		true
	);
}

/**
 * Enqueue styles for front-end.
 *
 * @return void
 */
function styles() {
	wp_enqueue_style(
		'deadpoo-main',
		DIE_URL . '/assets/css/dist/main.css',
		[],
		false
	);
}
