<?php /* Template Name: Side - Right */ ?>

<?php get_header(); ?>

<?php do_action( 'spine_theme_template_before_main', 'side-right.php' ); ?>

<main id="wsuwp-main" class="spine-sideright-template">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php do_action( 'spine_theme_template_before_headers', 'side-right.php' ); ?>

<?php wsuwp_spine_get_template_part( 'side-right.php', 'parts/headers' ); ?>

<?php do_action( 'spine_theme_template_after_headers', 'side-right.php' ); ?>

<?php wsuwp_spine_get_template_part( 'side-right.php', 'parts/featured-images' ); ?>

<?php do_action( 'spine_theme_template_before_content', 'side-right.php' ); ?>

<section class="row side-right gutter pad-ends">

	<div class="column one">

		<?php do_action( 'spine_theme_template_before_articles', 'side-right.php' ); ?>

		<?php get_template_part( 'articles/article' ); ?>

		<?php do_action( 'spine_theme_template_after_articles', 'side-right.php' ); ?>

	</div><!--/column-->

	<div class="column two">

		<?php do_action( 'spine_theme_template_before_sidebar', 'side-right.php' ); ?>

		<?php
		$column = get_post_meta( get_the_ID(), 'column-two', true );
		if ( ! empty( $column ) ) {
			echo wp_kses_post( $column );
		}
		?>

		<?php do_action( 'spine_theme_template_after_sidebar', 'side-right.php' ); ?>

	</div>

</section>
<?php
endwhile;
endif; ?>

<?php do_action( 'spine_theme_template_after_content', 'side-right.php' ); ?>

<?php do_action( 'spine_theme_template_before_footer', 'side-right.php' ); ?>

	<?php wsuwp_spine_get_template_part( 'side-right.php', 'parts/footers' ); ?>

	<?php do_action( 'spine_theme_template_after_footer', 'side-right.php' ); ?>

</main>

<?php do_action( 'spine_theme_template_after_main', 'side-right.php' ); ?>

<?php get_footer();
