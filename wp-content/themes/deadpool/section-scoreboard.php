<?php
/**
 * The scoreboard section of the main display.
 *
 * @package Deadpool
 */

use Deadpool\Helpers;
?>
<section class="section-box scoreboard">
	<header class="section-box-title">
		<?php require DIE_PATH . 'assets/img/title-bg.svg'; ?>
		<h3><?php esc_html_e( 'Scores', 'die' ); ?></h3>
	</header>
	<div class="scoreboard-items">
		<div class="score-item">
			<div class="score-item-inside">
				<h4 class="score-item-title">
					<?php esc_html_e( 'Episode', 'die' ); ?>
				</h4>
				<div class="score-item-content">
					<span>
						<?php echo esc_html( get_option( 'episode_number', 0 ) ); ?>
					</span>
				</div>
			</div>
		</div>
		<?php if ( Helpers\have_players() ) : ?>
			<?php while ( Helpers\have_players() ) : Helpers\the_player(); ?>
				<?php get_template_part( 'player', 'score' ); ?>
			<?php endwhile ?>
		<?php endif; ?>
		<?php Helpers\rewind_players(); ?>
	</div>
	<div class="svg-bg">
		<?php Helpers\get_end_cap(); ?>
	</div>
</section>
