<?php

get_header();

add_action( 'spine_theme_template_before_main', 'page.php' );

?>

<main id="wsuwp-main" class="spine-page-default">

<?php

if ( apply_filters( 'spine_theme_part_display_headers', true, 'page.php' ) ) {

	get_template_part( 'parts/headers' );

}; // End if

add_action( 'spine_theme_template_after_main', 'page.php' );

if ( apply_filters( 'spine_theme_part_display_featured_image', true, 'page.php' ) ) {

	get_template_part( 'parts/featured-images' );

};

add_action( 'spine_theme_template_before_content', 'page.php' );

?>

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

	<?php

	if ( apply_filters( 'spine_theme_part_display_footer', true, 'page.php' ) ) {

		get_template_part( 'parts/footers' );

	};

	add_action( 'spine_theme_template_after_content', 'page.php' );
	?>

</main>

<?php get_footer();
