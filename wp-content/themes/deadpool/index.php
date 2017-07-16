<?php
/**
 * The main template file
 *
 * @package Deadpool
 */

get_header(); ?>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ): the_post(); ?>
			<h2><?php the_title(); ?></h2>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php else: ?>
		<div>
			<h2><?php esc_html_e( 'Nothing found', 'die' ); ?></h2>
			<?php esc_html_e( 'Sorry, you found a DEAD page...', 'die' ); ?>
		</div>
	<?php endif ?>
	<?php if ( ! is_singular() ) : ?>
		<div>
			<?php posts_nav_link(
				' ',
				__( 'Newer Posts', 'die' ),
				__( 'Older Posts', 'die' )
			); ?>
		</div>
	<?php endif; ?>
<?php get_footer();
