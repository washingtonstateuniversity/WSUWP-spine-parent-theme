<?php /* Template Name: Side - Left */ ?>

<?php get_header(); ?>

<?php do_action( 'spine_theme_template_before_main', 'side-left.php' ); ?>

<main id="wsuwp-main" class="spine-sideleft-template">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php do_action( 'spine_theme_template_before_headers', 'side-left.php' ); ?>

<?php wsuwp_spine_get_template_part( 'side-left.php', 'parts/headers' ); ?>

<?php do_action( 'spine_theme_template_after_headers', 'side-left.php' ); ?>

<?php wsuwp_spine_get_template_part( 'side-left.php', 'parts/featured-images' ); ?>

<?php do_action( 'spine_theme_template_before_content', 'side-left.php' ); ?>

<section class="row side-left gutter pad-ends">

	<div class="column one">

		<?php do_action( 'spine_theme_template_before_sidebar', 'side-left.php' ); ?>

		<?php
		$column = get_post_meta( get_the_ID(), 'column-one', true );
		if ( ! empty( $column ) ) {
			echo wp_kses_post( $column );
		}
		?>

		<?php do_action( 'spine_theme_template_after_sidebar', 'side-left.php' ); ?>

	</div><!--/column-->

	<div class="column two">

		<?php do_action( 'spine_theme_template_before_articles', 'side-left.php' ); ?>

		<?php get_template_part( 'articles/article' ); ?>

		<?php do_action( 'spine_theme_template_after_articles', 'side-left.php' ); ?>

	</div>

</section>
<?php
endwhile;
endif; ?>

<?php do_action( 'spine_theme_template_after_content', 'side-left.php' ); ?>

<?php do_action( 'spine_theme_template_before_footer', 'side-left.php' ); ?>

<?php wsuwp_spine_get_template_part( 'side-left.php', 'parts/footers' ); ?>

<?php do_action( 'spine_theme_template_after_footer', 'side-left.php' ); ?>

</main>

<?php do_action( 'spine_theme_template_after_main', 'side-left.php' ); ?>

<?php get_footer();
