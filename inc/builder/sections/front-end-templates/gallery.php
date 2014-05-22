<?php
/**
 * @package Make
 */

global $ttfmake_section_data, $ttfmake_sections;
$gallery  = ttfmake_builder_get_gallery_array( $ttfmake_section_data );
$darken   = ( isset( $ttfmake_section_data[ 'darken' ] ) ) ? absint( $ttfmake_section_data[ 'darken' ] ) : 0;
$captions = ( isset( $ttfmake_section_data[ 'captions' ] ) ) ? esc_attr( $ttfmake_section_data[ 'captions' ] ) : 'reveal';
$aspect   = ( isset( $ttfmake_section_data[ 'aspect' ] ) ) ? esc_attr( $ttfmake_section_data[ 'aspect' ] ) : 'square';
?>

<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>" class="builder-section<?php echo esc_attr( ttfmake_builder_get_gallery_class( $ttfmake_section_data, $ttfmake_sections ) ); ?>" style="<?php echo esc_attr( ttfmake_builder_get_gallery_style( $ttfmake_section_data ) ); ?>">
	<?php if ( '' !== $ttfmake_section_data['title'] ) : ?>
	<h3 class="builder-gallery-section-title">
		<?php echo apply_filters( 'the_title', $ttfmake_section_data['title'] ); ?>
	</h3>
	<?php endif; ?>
	<div class="builder-section-content">
		<?php if ( ! empty( $gallery ) ) : $i = 0; foreach ( $gallery as $item ) :
			$onclick = '';
			if ( '' !== $item['link'] ) :
				$onclick = ' onclick="window.location.href = \'' . esc_js( esc_url( $item['link'] ) ) . '\';"';
			endif;
			$i++;
		?>
		<div class="builder-gallery-item<?php echo esc_attr( ttfmake_builder_get_gallery_item_class( $ttfmake_section_data, $i ) ); ?>"<?php echo $onclick; ?>>
			<?php $image = ttfmake_builder_get_gallery_item_image( $item, $aspect ); ?>
			<?php if ( '' !== $image ) : ?>
				<?php echo $image; ?>
			<?php endif; ?>
			<?php if ( 'none' !== $captions && ( '' !== $item['title'] || '' !== $item['description'] || has_excerpt( $item['image-id'] ) ) ) : ?>
			<div class="builder-gallery-content">
				<?php if ( '' !== $item['title'] ) : ?>
				<h4 class="builder-gallery-title">
					<?php echo apply_filters( 'the_title', $item['title'] ); ?>
				</h4>
				<?php endif; ?>
				<?php if ( '' !== $item['description'] ) : ?>
				<div class="builder-gallery-description">
					<?php ttfmake_get_builder_save()->the_builder_content( $item['description'] ); ?>
				</div>
				<?php elseif ( has_excerpt( $item['image-id'] ) ) : ?>
				<div class="builder-gallery-description">
					<?php echo ttfmake_sanitize_text( get_post( $item['image-id'] )->post_excerpt ); ?>
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endforeach; endif; ?>
	</div>
	<?php if ( 0 !== $darken ) : ?>
	<div class="builder-section-overlay"></div>
	<?php endif; ?>
</section>
