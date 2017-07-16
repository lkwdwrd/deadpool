<?php
/**
 * The post meta template file
 *
 * @package Deadpool
 */

 namespace Deadpool\Post_Meta\Meta_Name;

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
	$current_value = get_post_meta( $post->ID, 'cmeta', true );

	// Output the nonce
	wp_nonce_field( 'noncy' . $post->ID, 'cmeta-nonce' );

	// Output the form for this meta
	?>
		<p>
			<label for="cmeta">
				<?php esc_html_e( 'Custom Meta', 'die' ); ?>
			</label>
		</p>
		<p>
			<input
				type="text"
				name="cmeta"
				id="cmeta"
				value="<?php echo esc_attr( $current_value ); ?>"
				class="widefat"
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
	if ( !in_array( get_post_type( $post_id ), get_post_types() ) ) {
		return;
	}
	// Verify nonce
	$nonce_name = 'cmeta-nonce';
	$nonce_action = 'noncy' . $post_id;
	$nonce = isset( $_POST[ $nonce_name ] ) ? $_POST[ $nonce_name ] : '';
	if ( !wp_verify_nonce( $nonce, $nonce_action ) ) {
		return;
	}
	// Verify permissions
	if ( !current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	// Save data
	$value = $_POST[ 'cmeta' ] ?? null;
	if ( null !== $value ) {
		update_post_meta( $post_id, 'cmeta', $value );
	} else {
		delete_post_meta( $post_id, 'cmeta' );
	}
}

/**
 * Register meta box for this meta.
 *
 * @return void
 */
function register_meta_boxes() {
	foreach( get_post_types() as $post_type ) {
		add_meta_box(
			'cmeta-metabox',
			__( 'Custom Metabox', 'die' ),
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
	if ( 'cmeta' === $meta_key ){
		$protected = true;
	}
	return $protected;
}

/**
 * Gets the post types that support the hide title post meta checkbox.
 * @return array The post types that support this metabox.
 */
function get_post_types() {
	return apply_filters( 'cmeta-pts', [] );
}
