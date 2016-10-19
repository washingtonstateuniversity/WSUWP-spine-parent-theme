<?php /* Template Name: Halves */ ?>

<?php get_header(); ?>

<main id=""wsuwp-main">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php get_template_part( 'parts/headers' ); ?>
<?php get_template_part( 'parts/featured-images' ); ?>

<section class="row halves gutter pad-ends">

	<div class="column one">

		<?php get_template_part( 'articles/article' ); ?>

	</div><!--/column-->

	<div class="column two">


	</div>

</section>
<?php
endwhile;
endif;

get_template_part( 'parts/footers' );
?>
</main>
<?php get_footer();
