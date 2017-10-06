<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="article-header">
		<hgroup>
			<h2 class="article-title">
				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h2>
		</hgroup>
		<hgroup class="source">
			<time class="article-date" datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date(); ?></time>
		</hgroup>
	</header>

</article>
