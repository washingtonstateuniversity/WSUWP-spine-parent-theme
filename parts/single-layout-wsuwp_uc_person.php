<?php while ( have_posts() ) : the_post(); ?>
<section class="row halves gutter pad-ends">
	<div class="column one">
		<?php
		if ( has_post_thumbnail() ) {
			?><figure class="article-thumbnail"><?php spine_the_featured_image(); ?></figure><?php
		}
		?>
	</div>
	<div class="column two">
		<h1 class="article-title"><?php the_title(); ?></h1>
	</div>
</section>

<section class="row single gutter pad-ends">

	<div class="column one">

			<?php get_template_part( 'articles/post', get_post_type() ) ?>

	</div><!--/column-->

</section>
<?php endwhile;