<?php get_header(); ?>

<?php if (has_post_thumbnail( $post->ID ) ): ?>
<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
<style>
	main { background-image: url(<?php echo $image[0]; ?>); }
</style>
<?php endif; ?>

<?php $position = get_post_meta($post->ID, 'position', TRUE);
if($position != '') { $position = $position - 132; ?>

<style>
	main section:nth-of-type(1) { margin-top: <?php echo $position; ?>px; }
</style>
<?php } ?>

<main class="single">

<header class="main-header">
	<div class="main-headers">
	    <div class="parent-header"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></div>
	    <div class="child-header"><?php the_category(' '); ?></div>
	</div>
</header>

<section class="row margin">

	<div class="column one">
	
		<?php while ( have_posts() ) : the_post(); ?>
				
			<?php get_template_part( 'articles/article', get_post_format() ); ?>
			
			<?php // get_comments( ); ?>

		<?php endwhile; ?>
		
	</div><!--/column-->

	<div class="column two">
		
	</div><!--/column two-->

</section>

<footer>
<section class="row halves pager">
	<div class="column one">
		<?php previous_post_link(); ?> 
	</div>
	<div class="column two">
		<?php next_post_link(); ?>
	</div>
</section><!--pager-->
</footer>

</main><!--/#page-->

<a href="<?php echo get_edit_post_link(); ?>" class="wp-edit-link">Edit</a>

<?php get_footer(); ?>