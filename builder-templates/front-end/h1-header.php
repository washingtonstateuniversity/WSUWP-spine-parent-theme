<?php
global $ttfmake_section_data;

$section_classes = ( isset( $ttfmake_section_data['section-classes'] ) ) ? $ttfmake_section_data['section-classes'] : '';
$section_wrapper_classes = ( isset( $ttfmake_section_data['section-wrapper'] ) ) ? $ttfmake_section_data['section-wrapper'] : false;

if ( $section_wrapper_classes ) {
	echo '<div class="' . esc_attr( $section_wrapper_classes ) . '">';
}
?>
<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>" class="row single h1-header <?php echo esc_attr( $section_classes ); ?>">
	<div class="column one">
		<?php if ( ! empty( $ttfmake_section_data['title'] ) ) : ?>
			<h1><?php echo apply_filters( 'the_title', $ttfmake_section_data['title'] ); ?></h1>
		<?php endif; ?>
	</div>
</section>
<?php

if ( $section_wrapper_classes ) {
	echo '</div>';
}