<?php /* Template Name: Single */ ?>

<?php get_header(); ?>

<?php do_action( 'spine_theme_template_before_main', 'single.php' ); ?>

<main id="wsuwp-main" class="spine-single-template">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php do_action( 'spine_theme_template_before_headers', 'single.php' ); ?>

<?php wsuwp_spine_get_template_part( 'single.php', 'parts/headers' ); ?>

<?php do_action( 'spine_theme_template_after_headers', 'single.php' ); ?>

<?php wsuwp_spine_get_template_part( 'single.php', 'parts/featured-images' ); ?>

<?php do_action( 'spine_theme_template_before_content', 'single.php' ); ?>

<section class="row single gutter pad-ends">

	<div class="column one">

		<?php do_action( 'spine_theme_template_before_articles', 'single.php' ); ?>

		<?php get_template_part( 'articles/article' ); ?>

		<?php do_action( 'spine_theme_template_after_articles', 'single.php' ); ?>

	</div><!--/column-->

</section>
<?php
endwhile;
endif; ?>

<?php do_action( 'spine_theme_template_after_content', 'single.php' ); ?>

<?php do_action( 'spine_theme_template_before_footer', 'single.php' ); ?>

<?php wsuwp_spine_get_template_part( 'single.php', 'parts/footers' ); ?>

<?php do_action( 'spine_theme_template_after_footer', 'single.php' ); ?>

</main>

<?php do_action( 'spine_theme_template_after_main', 'single.php' ); ?>

<?php get_footer();
