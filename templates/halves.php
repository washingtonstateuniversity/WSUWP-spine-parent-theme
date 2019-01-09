<?php /* Template Name: Halves */ ?>

<?php get_header(); ?>

<?php do_action( 'spine_theme_template_before_main', 'halves.php' ); ?>

<main id="wsuwp-main">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php do_action( 'spine_theme_template_before_headers', 'halves.php' ); ?>

<?php wsuwp_spine_get_template_part( 'halves.php', 'parts/headers' ); ?>

<?php do_action( 'spine_theme_template_after_headers', 'halves.php' ); ?>

<?php wsuwp_spine_get_template_part( 'halves.php', 'parts/featured-images' ); ?>

<?php do_action( 'spine_theme_template_before_content', 'halves.php' ); ?>

<section class="row halves gutter pad-ends">

	<div class="column one">

		<?php do_action( 'spine_theme_template_before_articles', 'halves.php' ); ?>

		<?php get_template_part( 'articles/article' ); ?>

		<?php do_action( 'spine_theme_template_after_articles', 'halves.php' ); ?>

	</div><!--/column-->

	<div class="column two">

		<?php do_action( 'spine_theme_template_before_sidebar', 'halves.php' ); ?>

		<?php do_action( 'spine_theme_template_after_sidebar', 'halves.php' ); ?>

	</div>

</section>
<?php
endwhile;
endif; ?>

<?php do_action( 'spine_theme_template_after_content', 'halves.php' ); ?>

<?php do_action( 'spine_theme_template_before_footer', 'halves.php' ); ?>

<?php wsuwp_spine_get_template_part( 'halves.php', 'parts/footers' ); ?>

<?php do_action( 'spine_theme_template_after_footer', 'halves.php' ); ?>

</main>

<?php do_action( 'spine_theme_template_after_main', 'halves.php' ); ?>

<?php get_footer();
