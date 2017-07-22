<?php
/**
 * The character post type
 *
 * @package Deadpool
 */

namespace Deadpool\Post_Types\Character;

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
	add_action( 'char_status_meta', __NAMESPACE__ . '\\opt_in' );
	add_action( 'char_wiki_meta', __NAMESPACE__ . '\\opt_in' );
}

/**
 * Register a custom post type called "player".
 *
 * @see get_post_type_labels() for label keys.
 */
function register_cpt() {

	$labels = array(
		'name' => _x( 'Character', 'Post type general name', 'die' ),
		'die' => _x( 'Character', 'Post type singular name', 'die' ),
		'die' => _x( 'Character', 'Admin Menu text', 'die' ),
		'name_admin_bar' => _x( 'Character', 'Add New on Toolbar', 'die' ),
		'add_new' => __( 'Add Character', 'die' ),
		'add_new_item' => __( 'Add New Character', 'die' ),
		'new_item' => __( 'New Character', 'die' ),
		'edit_item' => __( 'Edit Character', 'die' ),
		'view_item' => __( 'View Character', 'die' ),
		'all_items' => __( 'All Characters', 'die' ),
		'search_items' => __( 'Search characters', 'die' ),
		'not_found' => __( 'No characters found.', 'die' ),
		'not_found_in_trash' => __( 'No characters found in Trash.', 'die' ),
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
		'menu_icon'          => 'dashicons-id-alt',
		'supports'           => [ 'title', 'thumbnail' ],
	);

	register_post_type( 'character', $args );
}

/**
 * Adds the post type to an opt-in array.
 *
 * @param  array $post_types The opted in post types.
 * @return array             The opted in post types with this cpt added to it.
 */
function opt_in( $post_types ) {
	$post_types[] = 'character';
	return $post_types;
}
