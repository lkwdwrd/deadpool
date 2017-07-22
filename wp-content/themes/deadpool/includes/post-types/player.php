<?php
/**
 * The player post type
 *
 * @package Deadpool
 */

namespace Deadpool\Post_Types\Player;

/**
 * Sets up this file with the WordPress API.
 *
 * @return void
 */
function load() {
	add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup' );
}

/**
 * Register various methods with the WordPress API.
 *
 * @return void
 */
function setup() {
	add_action( 'init', __NAMESPACE__ . '\\register_cpt' );
	// Add opt-in filters for any meta/taxonomies this post type should support.
	add_filter( 'characters_meta', __NAMESPACE__ . '\\opt_in' );
}

/**
 * Register a custom post type called "player".
 *
 * @see get_post_type_labels() for label keys.
 */
function register_cpt() {

	$labels = array(
		'name' => _x( 'Player', 'Post type general name', 'die' ),
		'die' => _x( 'Player', 'Post type singular name', 'die' ),
		'die' => _x( 'Player', 'Admin Menu text', 'die' ),
		'name_admin_bar' => _x( 'Player', 'Add New on Toolbar', 'die' ),
		'add_new' => __( 'Add Player', 'die' ),
		'add_new_item' => __( 'Add New Player', 'die' ),
		'new_item' => __( 'New Player', 'die' ),
		'edit_item' => __( 'Edit Player', 'die' ),
		'view_item' => __( 'View Player', 'die' ),
		'all_items' => __( 'All Players', 'die' ),
		'search_items' => __( 'Search Players', 'die' ),
		'not_found' => __( 'No players found.', 'die' ),
		'not_found_in_trash' => __( 'No players found in Trash.', 'die' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'rewrite'            => false,
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-universal-access-alt',
		'supports'           => [ 'title', 'page-attributes' ],
	);

	register_post_type( 'player', $args );
}

/**
 * Adds the post type to an opt-in array.
 *
 * @param  array $post_types The opted in post types.
 * @return array             The opted in post types with this cpt added to it.
 */
function opt_in( $post_types ) {
	$post_types[] = 'player';
	return $post_types;
}
