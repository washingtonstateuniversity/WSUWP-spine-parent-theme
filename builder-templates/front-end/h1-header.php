<?php
global $ttfmake_section_data;

$section_classes = ( isset( $ttfmake_section_data['section-classes'] ) ) ? $ttfmake_section_data['section-classes'] : '';

?>
<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>" class="row single <?php echo esc_attr( $section_classes ); ?>">
		<?php if ( ! empty( $ttfmake_section_data['title'] ) ) : ?>
			<h1><?php echo apply_filters( 'the_title', $ttfmake_section_data['title'] ); ?></h1>
		<?php endif; ?>
</section>