<?php
/**
 * Various helper functions for use on the Deadpool website.
 *
 * @package Deadpool
 */

namespace Deadpool\Helpers;

/**
 * Get the current status of a character.
 *
 * @param  \WP_Post|int|null $char The character post object, ID, or is will use
 *                                 the global value.
 * @return string                  The status of the character -- alive or dead.
 */
function get_char_status( $char = null ) {
	$char = get_post( $char );
	return 'dead' === $char->char_status ? 'dead' : 'alive';
}

/**
 * Gets all of the characters for a player
 *
 * @param  \WP_Post|int|null $player The player post object, ID, or is will use
 *                                   the global value.
 * @return array                An array of Character post object.
 */
function get_characters( $player = null ) {
	$player = get_post( $player );
	return array_map( 'get_post' , explode( ',', $player->characters ) );
}

/**
 * Gets the main player WP_Query - cached after the first run.
 *
 * @return \WP_Query The player WP Query object.
 */
function get_players_query() {
	static $players;
	if ( null === $players ) {
		$players = new \WP_Query([
			'post_type' => 'player',
			'posts_per_page' => 100,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		]);
	}

	return $players;
}

/**
 * Helper for runnng have_posts on the player WP_Query object.
 *
 * @return bool Whether or not the query contains players.
 */
function have_players() {
	return get_players_query()->have_posts();
}

/**
 * Helper for runnng the_post on the player WP_Query object.
 *
 * @return void
 */
function the_player() {
	return get_players_query()->the_post();
}

/**
 * Helper for runnng rewinde_posts on the player WP_Query object.
 *
 * @return void
 */
function rewind_players() {
	return get_players_query()->rewind_posts();
}

/**
 * Outputs an end-cap SVG, rotating through the available endcaps.
 *
 * @return void
 */
function get_end_cap() {
	static $num;
	$num = ( null === $num || 8 < $num ) ? 1 : ++$num;
	require DIE_PATH . "assets/img/bg-shape-$num.svg";
}

/**
 * Gets a players score based on how many dead characters they have.
 *
 * @param  \WP_Post|int|null $player The player post object, ID, or is will use
 *                                   the global value.
 * @return int                       The number of dead characters a player has.
 */
function get_score( $player = null ) {
	$characters = get_characters( $player );
	return array_reduce( $characters, function( $score, $character ) {
		return 'dead' === get_char_status( $character ) ? ++$score : $score;
	}, 0 );
}
