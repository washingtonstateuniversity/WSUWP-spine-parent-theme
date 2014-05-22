<?php
global $ttfmake_section_data, $ttfmake_sections;

$data_columns = spine_get_two_column_data( $ttfmake_section_data );
$count = 'one';
?>
<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>" class="row halves">
	<?php
	if ( ! empty( $data_columns ) ) {
		foreach( $data_columns as $column ) {
			$link_front = '';
			$link_back = '';
			if ( '' !== $column['image-link'] ) {
				$link_front = '<a href="' . esc_url( $column['image-link'] ) . '">';
				$link_back = '</a>';
			}
			?>
			<div class="column <?php echo $count; $count = 'two'; ?> builder-text-column">
			<?php if ( 0 !== absint( $column['image-id'] ) ) : ?>
				<figure class="builder-halves-image">
					<?php echo $link_front . wp_get_attachment_image( $column['image-id'], 'larger' ) . $link_back; ?>
				</figure>
			<?php endif; ?>
			<?php if ( '' !== $column['title'] ) : ?>
				<h3 class="builder-halves-title"><?php echo apply_filters( 'the_title', $column['title'] ); ?></h3>
			<?php endif; ?>
			<?php if ( '' !== $column['content'] ) : ?>
				<div class="builder-sideleft-content">
					<?php ttfmake_get_builder_save()->the_builder_content( $column['content'] ); ?>
				</div>
			<?php endif; ?>
			</div>
			<?php
		}
	}
	?>
</section>
