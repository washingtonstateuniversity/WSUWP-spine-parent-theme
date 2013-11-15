<?php get_header(); ?>

<main id="page" role="main" class="skeleton">

<header id="siteID">
    <h2><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>
    <h1><a href="index.html"><?php bloginfo( 'description' ); ?></a></h1>
</header><!--siteID-->

<section class="row sidebar">

	<div class="column one">
	
		<?php while ( have_posts() ) : the_post(); ?>
				
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header>
			<?php the_content(); ?>
		</article>

		<?php endwhile; // end of the loop. ?>
		
		<section class="row sidebar">

	<div class="column one">
		<article>
			
			<h1>Heading using the h1tag</h1>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce aliquet porttitor enim, et porttitor sem auctor ac. Aliquam posuere facilisis tempus. Phasellus consectetur eu risus non dignissim. Mauris suscipit, nulla quis eleifend rhoncus, felis ligula euismod ligula, in pulvinar eros quam sed neque. Integer enim mauris, eleifend id tellus ut, laoreet rhoncus eros. Proin ultricies suscipit mi eget placerat. Quisque ultrices ultricies sem non varius.</p>
			
			<p>Nulla leo mi, vestibulum ut ornare nec, gravida at odio. Ut dignissim molestie velit. Etiam quis porta orci, id congue lacus. Nam quis elit rutrum eros mollis venenatis. Ut volutpat arcu in interdum hendrerit. Pellentesque at diam elit. Praesent turpis metus, tincidunt nec consectetur ut, porta vitae odio. Sed urna metus, tincidunt ac elementum euismod, adipiscing in nunc. Mauris fringilla feugiat nunc, at imperdiet arcu interdum sed. Aliquam malesuada ornare libero, a euismod ligula laoreet posuere.</p>
			
			<ul>
				<li>Sed bibendum vel arcu vel aliquam.</li>
				<li>Donec sodales iaculis lobortis. Praesent ac feugiat sem.</li>
				<li>Duis non commodo dui.</li>
				<li>Integer consectetur erat et dignissim porttitor.</li>
				<li>Quisque convallis sodales mauris, sed commodo eros bibendum vitae.</li>
			</ul>
			
			<p>Morbi gravida magna magna. Aliquam erat volutpat. Sed enim sem, laoreet a nisi sit amet, mattis venenatis est. Curabitur imperdiet, nibh et sagittis feugiat, nunc purus faucibus lacus, sodales auctor ipsum lorem eu lectus. Integer aliquet placerat metus, ut rhoncus augue congue quis. Duis feugiat augue nec elementum faucibus. Nam tincidunt dui elementum magna pretium luctus. Aenean fringilla lectus nulla, sit amet mollis neque tristique eu. Pellentesque justo ligula, auctor quis viverra vitae, accumsan vel nisl. Sed bibendum vel arcu vel aliquam. Donec sodales iaculis lobortis. Praesent ac feugiat sem. Duis non commodo dui. Integer consectetur erat et dignissim porttitor. Quisque convallis sodales mauris, sed commodo eros bibendum vitae.</p>

		</article>
	</div><!--/column-->

</section>

		
	</div><!--/column-->

	<div class="column two">
		
		<?php get_sidebar(); ?>
		
	</div><!--/column two-->

</section>

</main><!--/#page-->

<?php get_template_part( 'spine/spine' ); ?>

<?php get_footer(); ?>