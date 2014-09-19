<?php
global $ttfmake_section_data, $ttfmake_sections;
$text_columns = spine_get_column_data( $ttfmake_section_data, 2 );

$section_classes = ( isset( $ttfmake_section_data['section-classes'] ) ) ? $ttfmake_section_data['section-classes'] : '';
$section_wrapper_classes = ( isset( $ttfmake_section_data['section-wrapper'] ) ) ? $ttfmake_section_data['section-wrapper'] : false;
$section_layout = ( isset( $ttfmake_section_data['section-layout'] ) ) ? $ttfmake_section_data['section-layout'] : 'side-right';
if ( $section_wrapper_classes ) {
	echo '<div class="' . esc_attr( $section_wrapper_classes ) . '">';
}
?>
<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>" class="row <?php echo esc_attr( $section_layout ); ?> <?php echo esc_attr( $section_classes ); ?>">
	<?php $count = 'one'; ?>
	<?php if ( ! empty( $text_columns ) ) : foreach ( $text_columns as $column ) :
		?>
		<div class="column <?php echo $count; $count = 'two'; ?>">

			<?php if ( '' !== $column['title'] ) : ?>
				<header>
					<h2><?php echo apply_filters( 'the_title', $column['title'] ); ?></h2>
				</header>
			<?php endif; ?>

			<?php if ( '' !== $column['content'] ) : ?>
				<?php ttfmake_get_builder_save()->the_builder_content( $column['content'] ); ?>
			<?php endif; ?>

		</div>
	<?php endforeach; endif; ?>
</section>
<?php

if ( $section_wrapper_classes ) {
	echo '</div>';
}
