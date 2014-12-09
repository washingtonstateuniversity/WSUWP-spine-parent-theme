<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="article-header">
		<hgroup>
			<?php
			if ( has_post_thumbnail() ) {
				?><figure class="article-thumbnail"><?php the_post_thumbnail( array( 132, 132, true ) ); ?></figure><?php
			}
			?>
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

			// If a manual excerpt is available, display this. Otherwise, only the most basic information is needed.
			if ( $post->post_excerpt ) {
				echo get_the_excerpt();
			}
			?>
		</div><!-- .article-summary -->
	<?php else : ?>
		<div class="article-body">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'spine' ), 'after' => '</div>' ) ); ?>
		</div>
	<?php endif; ?>

	<footer class="article-footer">
		<?php
		// Display site level categories attached to the post.
		if ( has_category() ) {
			echo '<dl class="categorized">';
			echo '<dt><span class="categorized-default">Categorized</span></dt>';
			foreach( get_the_category() as $category ) {
				echo '<dd><a href="' . get_category_link( $category->cat_ID ) . '">' . $category->cat_name . '</a></dd>';
			}
			echo '</dl>';
		}

		// Display University categories attached to the post.
		if ( has_term( '', 'wsuwp_university_category' ) ) {
			$university_category_terms = get_the_terms( get_the_ID(), 'wsuwp_university_category' );
			if ( ! is_wp_error( $university_category_terms ) ) {
				echo '<dl class="university-categorized">';
				echo '<dt><span class="university-categorized-default">Categorized</span></dt>';

				foreach ( $university_category_terms as $term ) {
					$term_link = get_term_link( $term->term_id, 'wsuwp_university_category' );
					if ( ! is_wp_error( $term_link ) ) {
						echo '<dd><a href="' . esc_url( $term_link ) . '">' . $term->name . '</a></dd>';
					}
				}
				echo '</dl>';
			}
		}

		// Display University tags attached to the post.
		if ( has_tag() ) {
			echo '<dl class="tagged">';
			echo '<dt><span class="tagged-default">Tagged</span></dt>';
			foreach( get_the_tags() as $tag ) {
				echo '<dd><a href="' . get_tag_link( $tag->term_id ) . '">' . $tag->name . '</a></dd>';
			}
			echo '</dl>';
		}

		// Display University locations attached to the post.
		if ( has_term( '', 'wsuwp_university_location' ) ) {
			$university_location_terms = get_the_terms( get_the_ID(), 'wsuwp_university_location' );
			if ( ! is_wp_error( $university_location_terms ) ) {
				echo '<dl class="university-location">';
				echo '<dt><span class="university-location-default">Location</span></dt>';

				foreach ( $university_location_terms as $term ) {
					$term_link = get_term_link( $term->term_id, 'wsuwp_university_location' );
					if ( ! is_wp_error( $term_link ) ) {
						echo '<dd><a href="' . esc_url( $term_link ) . '">' . $term->name . '</a></dd>';
					}
				}
				echo '</dl>';
			}
		}
		?>
	</footer><!-- .entry-meta -->

</article>