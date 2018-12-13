<?php get_header(); ?>

<?php add_action( 'spine_theme_template_before_main', 'page.php' ); ?>

<main id="wsuwp-main" class="spine-page-default">

<?php add_action( 'spine_theme_template_before_headers', 'page.php' ); ?>

<?php wsuwp_spine_get_template_part( 'page.php', 'parts/headers' ); ?>

<?php add_action( 'spine_theme_template_after_headers', 'page.php' ); ?>

<?php wsuwp_spine_get_template_part( 'page.php', 'parts/featured-images' ); ?>

<?php add_action( 'spine_theme_template_before_content', 'page.php' ); ?>

<section class="row side-right gutter pad-ends">

	<div class="column one">

		<?php add_action( 'spine_theme_template_before_articles', 'page.php' ); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php wsuwp_spine_get_template_part( 'page.php', 'articles/article' ); ?>

		<?php endwhile; ?>

		<?php add_action( 'spine_theme_template_after_articles', 'page.php' ); ?>

	</div><!--/column-->

	<div class="column two">

		<?php add_action( 'spine_theme_template_before_sidebar', 'page.php' ); ?>

		<?php get_sidebar(); ?>

		<?php add_action( 'spine_theme_template_after_sidebar', 'page.php' ); ?>

	</div><!--/column two-->

</section>

<?php add_action( 'spine_theme_template_after_content', 'page.php' ); ?>

<?php add_action( 'spine_theme_template_before_footer', 'page.php' ); ?>

	<?php wsuwp_spine_get_template_part( 'page.php', 'parts/footers' ); ?>

	<?php add_action( 'spine_theme_template_after_footer', 'page.php' ); ?>

</main>

<?php add_action( 'spine_theme_template_after_main', 'page.php' ); ?>

<?php get_footer();
