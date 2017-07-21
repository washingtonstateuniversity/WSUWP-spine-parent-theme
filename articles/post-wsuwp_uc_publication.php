<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="article-header">
		<hgroup>
			<?php if ( is_single() ) : ?>
				<h1 class="article-title"><?php the_title(); ?></h1>
			<?php else : ?>
				<h2 class="article-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php endif; ?>
		</hgroup>
	</header>

	<?php if ( ! is_singular() ) : ?>
		<div class="article-summary">
			<?php

			if ( has_post_thumbnail() ) {
				?><figure class="article-thumbnail"><?php the_post_thumbnail( array( 132, 132, true ) ); ?></figure><?php
			}

			// If a manual excerpt is available, display this. Otherwise, only the most basic information is needed.
			if ( $post->post_excerpt ) {
				echo get_the_excerpt();
			}

			?>
		</div><!-- .article-summary -->
	<?php else : ?>
		<div class="article-body">
			<?php the_content(); ?>
			<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'spine' ),
				'after' => '</div>',
			) );
			?>
		</div>
	<?php endif; ?>

</article>
