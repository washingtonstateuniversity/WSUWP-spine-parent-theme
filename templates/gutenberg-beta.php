<?php
/**
 * Template Name: Gutenberg (Beta)
 *
 * Provides a template that can be used to enable the Gutenberg editor
 * on pages without interrupting existing page builder templates.
 */

get_header();

?>
<main id="wsuwp-main" class="spine-gutenberg-beta-template">

	<?php get_template_part( 'parts/featured-images' ); ?>

	<header class="row single gutter pad-ends">
		<h1 class="column one"><?php the_title(); ?></h1>
	</header>

	<section class="row single gutter pad-bottom">

		<div class="column one">

			<?php
			if ( have_posts() ) : while ( have_posts() ) : the_post();

				the_content();

			endwhile;
			endif;

			?>
		</div>

	</section>

</main>
<?php
get_footer();
