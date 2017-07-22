<?php
/**
* The main template file
*
* @package Deadpool
*/

use Deadpool\Helpers;

get_header();
get_template_part( 'section', 'scoreboard' );
if ( Helpers\have_players() ) {
	while ( Helpers\have_players() ) {
		Helpers\the_player();
		get_template_part( 'section', 'player' );
	}
}
?>
<?php get_footer();
