<?php
/**
 * A partial for displaying a player's scoreboard
 *
 * @package Deadpool
 */

use Deadpool\Helpers;
?>

<div class="score-item">
	<div class="score-item-inside">
		<h4 class="score-item-title"><?php the_title(); ?></h4>
		<div class="score-item-content">
			<span><?php echo esc_html( Helpers\get_score() ); ?></span>
		</div>
	</div>
</div>
