<?php
/**
 * The player-character connection meta.
 *
 * @package Deadpool
 */

namespace Deadpool\Post_Meta\Characters;

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
	wp_nonce_field( 'characters_noncy_' . $post->ID, 'characters_nonce' );

	// Output the form for this meta
	?>
	<label for="characters" class="screen-reader-text">
		<?php esc_html_e( 'Choose Characters', 'die' ); ?>
	</label>
	<p>
		<input
			type="text"
			name="characters"
			id="characters"
			class="widefat"
			value="<?php echo esc_attr( $post->characters ); ?>"
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
	$nonce_name = 'characters_nonce';
	$nonce_action = 'characters_noncy_' . $post_id;
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
	$value = $_POST[ 'characters' ] ?? null;
	if ( null !== $value ) {
		update_post_meta( $post_id, 'characters', sanitize_text_field( $value ) );
	} else {
		delete_post_meta( $post_id, 'characters' );
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
			'chararacters-metabox',
			__( 'Select Characters', 'die' ),
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
	if ( 'characters' === $meta_key ) {
		$protected = true;
	}
	return $protected;
}

/**
 * Gets the post types that support the hide title post meta checkbox.
 * @return array The post types that support this metabox.
 */
function get_post_types() {
	return apply_filters( 'characters_meta', [] );
}
