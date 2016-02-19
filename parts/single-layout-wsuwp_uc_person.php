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
		<?php
		if ( function_exists( 'wsuwp_uc_get_meta' ) ) {
			$display_fields = array( 'prefix', 'first_name', 'last_name', 'suffix', 'title', 'title_secondary', 'office', 'email', 'phone' );
			$display_data = array();
			foreach ( $display_fields as $df ) {
				$display_data[ $df ] = wsuwp_uc_get_meta( get_the_ID(), $df );
			}

			$display_name_array = array( $display_data['prefix'], $display_data['first_name'], $display_data['last_name'] );
			$display_name_array = array_filter( $display_name_array, 'trim' );
			$display_name = join( ' ', $display_name_array );
			if ( ! empty( trim( $display_data['suffix'] ) ) ) {
				$display_name .= ', ' . $display_data['suffix'];
			}

			if ( ! empty( $display_name ) ) : ?><h1 class="article-title"><?php echo esc_html( $display_name ); ?></h1><?php endif;
			if ( ! empty( $display_data['title'] ) ) : ?><div class="person-title"><?php echo esc_html( $display_data['title'] ); ?></div><?php endif;
			if ( ! empty( $display_data['title_secondary'] ) ) : ?><div class="person-title-secondary"><?php echo esc_html( $display_data['title_secondary'] ); ?></div><?php endif;
			if ( ! empty( $display_data['office'] ) ) : ?><div class="person-office"><strong>Office</strong> <?php echo esc_html( $display_data['office'] ); ?></div><?php endif;
			if ( ! empty( $display_data['email'] ) ) : ?><div class="person-email"><strong>Email:</strong> <?php echo esc_html( $display_data['email'] ); ?></div><?php endif;
			if ( ! empty( $display_data['phone'] ) ) : ?><div class="person-phone"><strong>Phone:</strong> <?php echo esc_html( $display_data['phone'] ); ?></div><?php endif;
		} ?>
	</div>

</section>

<section class="row single gutter pad-ends">

	<div class="column one">

			<?php get_template_part( 'articles/post', get_post_type() ) ?>

	</div><!--/column-->

</section>
<?php endwhile;
