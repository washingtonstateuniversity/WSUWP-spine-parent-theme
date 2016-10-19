<?php get_header(); ?>

	<main id="wsuwp-main" class="spine-single-template">

		<?php get_template_part( 'parts/headers' ); ?>

		<section class="row single gutter pad-top">

			<div class="column one">

				<article id="post-0" class="post error404 no-results not-found">

					<header class="article-header">
						<h1 class="article-title">Page Not Found</h1>
					</header>

					<div class="entry-content">
						<p>It seems we can't find what you're looking for. Perhaps searching can help.</p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->

				</article>

			</div><!--/column-->

		</section>

		<?php get_template_part( 'parts/footers' ); ?>

	</main>

<?php get_footer();
