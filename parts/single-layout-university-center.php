<section class="row side-right gutter pad-ends">

	<div class="column one">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'articles/post', get_post_type() ) ?>

			<?php // get_comments( ); ?>

		<?php endwhile; ?>

	</div><!--/column-->

	<div class="column two"></div><!--/column two-->

</section>