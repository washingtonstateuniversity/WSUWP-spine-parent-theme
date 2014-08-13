<?php
/**
 * @package Make
 */

global $ttfmake_section_data, $ttfmake_sections;
$text_columns = ttfmake_builder_get_text_array( $ttfmake_section_data );
?>

<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>" class="builder-section<?php echo esc_attr( ttfmake_builder_get_text_class( $ttfmake_section_data, $ttfmake_sections ) ); ?>">
	<?php if ( '' !== $ttfmake_section_data['title'] ) : ?>
	<h3 class="builder-text-section-title">
		<?php echo apply_filters( 'the_title', $ttfmake_section_data['title'] ); ?>
	</h3>
	<?php endif; ?>
	<div class="builder-section-content">
		<?php if ( ! empty( $text_columns ) ) : $i = 1; foreach ( $text_columns as $column ) :
			$link_front = '';
			$link_back = '';
			if ( '' !== $column['image-link'] ) :
				$link_front = '<a href="' . esc_url( $column['image-link'] ) . '">';
				$link_back = '</a>';
			endif;
			?>
		<div class="builder-text-column builder-text-column-<?php echo $i; ?>" id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>-column-<?php echo $i; ?>">
			<?php $image_html =  ttfmake_get_image( $column['image-id'], 'large' ); ?>
			<?php if ( '' !== $image_html ) : ?>
			<figure class="builder-text-image">
				<?php echo $link_front . $image_html . $link_back; ?>
			</figure>
			<?php endif; ?>
			<?php if ( '' !== $column['title'] ) : ?>
			<h3 class="builder-text-title">
				<?php echo apply_filters( 'the_title', $column['title'] ); ?>
			</h3>
			<?php endif; ?>
			<?php if ( '' !== $column['content'] ) : ?>
			<div class="builder-text-content">
				<?php ttfmake_get_builder_save()->the_builder_content( $column['content'] ); ?>
			</div>
			<?php endif; ?>
		</div>
		<?php $i++; endforeach; endif; ?>
	</div>
</section>
