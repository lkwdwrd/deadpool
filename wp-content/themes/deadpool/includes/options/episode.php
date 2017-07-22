<?php
/**
 * Select the current episode for the season.
 *
 * @package Deadpool
 */

namespace Deadpool\Options\Episode;

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
	add_action( 'admin_init', __NAMESPACE__ . '\\register_option' );
}

function register_option() {
	add_settings_field(
		'episode_number',
		__( 'Episode Number', 'die' ),
		__NAMESPACE__ . '\\render_field',
		'general',
		'default',
		[
			'label_for' => 'episode_number',
		]
	);
	register_setting( 'general', 'episode_number', 'intval' );
}

function render_field() {
	// Output the form for this meta
	?>
	<input
		type="number"
		name="episode_number"
		id="characters"
		style="width:50px;"
		value="<?php echo (int) get_option( 'episode_number', 0 ); ?>"
	/>
	<?php
}
