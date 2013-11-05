<?php get_header(); ?>

<div id="spine" class="cropped shelved crimson">
	<div id="glue" class="clearfix">
		<?php get_template_part( 'spine/header' ); ?>
	</div><!--/glue-->
</div><!--/spine-->

<main id="page" role="main" class="skeleton">
	
<?php // while ( have_posts() ) : the_post(); ?>
	<?php // get_template_part( 'content', get_post_format() ); ?>
<?php // endwhile; ?>

<section class="row fifths">
	<div class="column one" style="height: 400px;"></div>
	<div class="column two"></div>
	<div class="column three"></div>
	<div class="column four"></div>
	<div class="column five"></div>
</section>
<section class="row fifths">
	<div class="column one"></div>
	<div class="column two"></div>
	<div class="column three"></div>
	<div class="column four"></div>
	<div class="column five"></div>
</section>

<footer class="looseleaf">
	<?php get_template_part( 'spine/footer' ); ?>
</footer>

</main><!--/#page-->

<?php get_footer(); ?>