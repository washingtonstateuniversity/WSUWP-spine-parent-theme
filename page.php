<?php get_header(); ?>

<main id="wsuwp-main" class="spine-page-default">

<?php get_template_part( 'parts/headers' ); ?>
<?php get_template_part( 'parts/featured-images' ); ?>

<section class="row side-right gutter pad-ends">

	<div class="column one">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'articles/article' ); ?>

		<?php endwhile; ?>

	</div><!--/column-->

	<div class="column two">

		<?php get_sidebar(); ?>

	</div><!--/column two-->

</section>

	<?php get_template_part( 'parts/footers' ); ?>

</main>

<?php get_footer();
