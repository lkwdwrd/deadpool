<?php
/**
 * The main player section of the home page.
 *
 * @package Deadpool
 */

use Deadpool\Helpers;

$characters = Helpers\get_characters();
?>

<section class="section-box">
	<header class="section-box-title">
		<?php require DIE_PATH . 'assets/img/title-bg.svg'; ?>
		<h3><?php the_title(); ?></h3>
	</header>
	<?php if ( 0 < count( $characters ) ) : ?>
		<div class="characters">
			<?php foreach ( $characters as $character ) : ?>
				<?php if ( $character->char_wiki ) : ?>
					<a
						class="character"
						href="<?php echo esc_url( $character->char_wiki ); ?>"
						title="<?php echo esc_html( get_the_title( $character ) ); ?>"
						rel="bookmark"
					>
				<?php else : ?>
					<div class="character">
				<?php endif; ?>
					<h4 class="character-name">
						<?php require DIE_PATH . 'assets/img/charname-bg.svg'; ?>
						<span>
							<?php echo wp_kses_post( get_the_title( $character ) ); ?>
						</span>
					</h4>
					<?php echo get_the_post_thumbnail(
						$character,
						'full',
						[
							'class' => 'character-img',
						]
					); ?>
				<?php if ( $character->char_wiki ) : ?>
					</a>
				<?php else : ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<div class="svg-bg">
		<?php Helpers\get_end_cap(); ?>
	</div>
</section>
