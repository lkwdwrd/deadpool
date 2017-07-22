<?php
/**
 * The character wiki link meta.
 *
 * @package Deadpool
 */

namespace Deadpool\Post_Meta\Wiki_Link;

use Deadpool\Helpers;

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
	add_action( 'add_meta_boxes', __NAMESPACE__ . '\\register_meta_boxes' );
	add_action( 'save_post', __NAMESPACE__ . '\\save' );
	add_filter( 'is_protected_meta', __NAMESPACE__ . '\\private_meta', 10, 2 );
}

/**
 * Display the metabox for this meta.
 *
 * @param  WP_Post $post The post object this meta box is being output for.
 * @return void
 */
function display_metabox( $post ) {
	// Output the nonce
	wp_nonce_field( 'char_wiki_noncy_' . $post->ID, 'char_wiki_nonce' );

	// Output the form for this meta
	?>
	<label for="char_wiki" class="screen-reader-text">
		<?php esc_html_e( 'Character Wiki URL', 'die' ); ?>
	</label>
	<p>
		<input
			type="text"
			name="char_wiki"
			id="char_wiki"
			class="widefat"
			value="<?php echo esc_url( $post->char_wiki ); ?>"
		/>
	</p>
	<?php
}

/**
 * Save the this meta when the post is saved.
 *
 * @param  int  $post_id The post ID being saved.
 * @return void
 */
function save( $post_id ) {
	// Don't save during autosave.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Verify post type
	if ( ! in_array( get_post_type( $post_id ), get_post_types(), true ) ) {
		return;
	}
	// Verify nonce
	$nonce_name = 'char_wiki_nonce';
	$nonce_action = 'char_wiki_noncy_' . $post_id;
	// @codingStandardsIgnoreLine
	if ( ! wp_verify_nonce( $_POST[ $nonce_name ] ?? '', $nonce_action ) ) {
		return;
	}
	// Verify permissions
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	// Save data
	// @codingStandardsIgnoreLine
	$value = $_POST[ 'char_wiki' ] ?? null;
	if ( null !== $value ) {
		update_post_meta( $post_id, 'char_wiki', esc_url_raw( $value ) );
	} else {
		delete_post_meta( $post_id, 'char_wiki' );
	}
}

/**
 * Register meta box for this meta.
 *
 * @return void
 */
function register_meta_boxes() {
	foreach ( get_post_types() as $post_type ) {
		add_meta_box(
			'char-wiki-metabox',
			__( 'Character Wiki URL', 'die' ),
			__NAMESPACE__ . '\\display_metabox',
			$post_type
		);
	}
}

/**
 * Sets the meta to private so it doesn't show up in custom fields.
 *
 * @param  boolean $protected Whether or not to protect the meta key.
 * @param  string  $meta_key  The meta key potentially being output.
 * @return boolean            Whether or not to protect the meta key.
 */
function private_meta( $protected, $meta_key ) {
	if ( 'char_wiki' === $meta_key ) {
		$protected = true;
	}
	return $protected;
}

/**
 * Gets the post types that support the hide title post meta checkbox.
 * @return array The post types that support this metabox.
 */
function get_post_types() {
	return apply_filters( 'char_wiki_meta', [] );
}
