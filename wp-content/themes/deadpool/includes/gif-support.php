<?php
/**
 * Remove upload sizes for GIFs
 */

namespace Deadpool\Gifs;

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
 */
function setup() {
	add_filter( 'intermediate_image_sizes_advanced', __NAMESPACE__ . '\\disable_upload_sizes', 10, 2 );
	add_filter( 'post_thumbnail_size', __NAMESPACE__ . '\\force_full_size_gifs', 10, 1 );
}

/**
 * Removes upload sizes for GIF files
 *
 * @param array $sizes An associative array of image sizes.
 * @param array $metadata An associative array of image metadata: width, height, file.
 * @return array $sizes An associative array of image sizes.
 */
function disable_upload_sizes( $sizes, $metadata ) {
	$type = wp_check_filetype( $metadata['file'] );
	// See if this is a gif
	if ( 'image/gif' === $type['type'] ) {
		$sizes = [];
	}
	return $sizes;
}

/**
 * Forces get_the_post_thumbnail to return 'full' if the image is a gif.
 *
 * @param  string|array $size The thumbnail size.
 * @return string|array       The thumbnail size.
 */
function force_full_size_gifs( $size ) {
	$pid = get_the_id();
	$tid = get_post_thumbnail_id( $pid );

	if ( ! $tid ) {
		return $size;
	}

	$file = wp_get_attachment_metadata( $tid );
	$type = wp_check_filetype( $file['file'] );

	if ( 'gif' === $file['ext'] ) {
		$size = 'full';
	}

	return $size;
}
