<?php
/**
 * Template Name: Builder Template
 */

get_header();
?>
	<main class="spine-blank-template">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<?php get_template_part('parts/headers'); ?>
			<?php get_template_part('parts/featured-images'); ?>

			<div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php

				/**
				 * `the_content` is fired on builder template pages while it is saved
				 * rather than while it is output in order for some advanced tags to
				 * survive the process and to avoid autop issues.
				 */
				remove_filter( 'the_content', 'wpautop', 10 );
				the_content();
				add_filter( 'the_content', 'wpautop', 10 );

				?>
			</div><!-- #post -->

		<?php endwhile; endif; ?>

	</main>

<?php get_footer(); ?>