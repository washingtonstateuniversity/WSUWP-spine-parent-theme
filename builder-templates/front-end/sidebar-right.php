<?php
global $ttfmake_section_data, $ttfmake_sections;
$text_columns = spine_get_two_column_data( $ttfmake_section_data );
?>
<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>" class="row sidebar">
	<?php $count = 'one'; ?>
	<?php if ( ! empty( $text_columns ) ) : foreach ( $text_columns as $column ) :
		$link_front = '';
		$link_back = '';
		if ( '' !== $column['image-link'] ) :
			$link_front = '<a href="' . esc_url( $column['image-link'] ) . '">';
			$link_back = '</a>';
		endif;
		?>
		<div class="column <?php echo $count; $count = 'two'; ?>">
			<article>

			<?php if ( 0 !== absint( $column['image-id'] ) ) : ?>
				<figure class="builder-text-image">
					<?php echo $link_front . wp_get_attachment_image( $column['image-id'], 'large' ) . $link_back; ?>
				</figure>
			<?php endif; ?>

			<?php if ( '' !== $column['title'] ) : ?>
				<header>
					<h2><?php echo apply_filters( 'the_title', $column['title'] ); ?></h2>
				</header>
			<?php endif; ?>

			<?php if ( '' !== $column['content'] ) : ?>
				<?php ttfmake_get_builder_save()->the_builder_content( $column['content'] ); ?>
			<?php endif; ?>

			</article>
		</div>
	<?php endforeach; endif; ?>
</section>