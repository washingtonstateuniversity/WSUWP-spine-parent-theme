<?php
global $ttfmake_section_data, $ttfmake_sections;
$sideleft_columns = ttfmake_builder_get_text_array( $ttfmake_section_data );
?>

<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>" class="row sideleft builder-section<?php echo esc_attr( ttfmake_builder_get_text_class( $ttfmake_section_data, $ttfmake_sections ) ); ?>">
		<?php $count = 'one'; ?>
		<?php if ( ! empty( $sideleft_columns ) ) : foreach ( $sideleft_columns as $column ) :
			$link_front = '';
			$link_back = '';
			if ( '' !== $column['image-link'] ) :
				$link_front = '<a href="' . esc_url( $column['image-link'] ) . '">';
				$link_back = '</a>';
			endif;
			?>
		<div class="column <?php echo $count; $count = 'two'; ?> builder-text-column">
			<?php if ( 0 !== absint( $column['image-id'] ) ) : ?>
			<figure class="builder-sideleft-image">
				<?php echo $link_front . wp_get_attachment_image( $column['image-id'], 'large' ) . $link_back; ?>
			</figure>
			<?php endif; ?>
			<?php if ( '' !== $column['title'] ) : ?>
			<h3 class="builder-sideleft-title">
				<?php echo apply_filters( 'the_title', $column['title'] ); ?>
			</h3>
			<?php endif; ?>
			<?php if ( '' !== $column['content'] ) : ?>
			<div class="builder-sideleft-content">
				<?php ttfmake_get_builder_save()->the_builder_content( $column['content'] ); ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endforeach; endif; ?>
</section>