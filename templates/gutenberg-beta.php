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

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<?php the_content(); ?>

	<?php
	endwhile;
	endif;
	?>
</main>
<?php
get_footer();
