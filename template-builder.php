<?php
/**
 * Template Name: Builder Template
 */

get_header();
?>

<?php do_action( 'spine_theme_template_before_main', 'page.php' ); ?>

	<main id="wsuwp-main" class="spine-blank-template">

		<?php do_action( 'spine_theme_template_before_headers', 'page.php' ); ?>

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<?php wsuwp_spine_get_template_part( 'page.php', 'parts/headers' ); ?>
			
			<?php do_action( 'spine_theme_template_after_headers', 'page.php' ); ?>

			<?php wsuwp_spine_get_template_part( 'page.php', 'parts/featured-images' ); ?>

			<?php do_action( 'spine_theme_template_before_content', 'page.php' ); ?>

			<div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php

				/**
				 * `the_content` is fired on builder template pages while it is saved
				 * rather than while it is output in order for some advanced tags to
				 * survive the process and to avoid autop issues.
				 */
				remove_filter( 'the_content', 'wpautop', 10 );
				add_filter( 'wsu_content_syndicate_host_data', 'spine_filter_local_content_syndicate_item', 10, 3 );
				the_content();
				remove_filter( 'wsu_content_syndicate_host_data', 'spine_filter_local_content_syndicate_item', 10 );
				add_filter( 'the_content', 'wpautop', 10 );

				?>
			</div><!-- #post -->

		<?php
		endwhile;
		endif;

		?>
		<?php do_action( 'spine_theme_template_after_content', 'page.php' ); ?>

		<?php do_action( 'spine_theme_template_before_footer', 'page.php' ); ?>

		<?php wsuwp_spine_get_template_part( 'page.php', 'parts/footers' ); ?>

		<?php do_action( 'spine_theme_template_after_footer', 'page.php' ); ?>

	</main>
	<?php do_action( 'spine_theme_template_after_main', 'page.php' ); ?>
<?php get_footer();
