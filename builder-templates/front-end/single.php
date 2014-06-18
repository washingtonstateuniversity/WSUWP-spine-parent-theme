<?php
global $ttfmake_section_data, $ttfmake_sections;

$section_classes = ( isset( $ttfmake_section_data['section-classes'] ) ) ? $ttfmake_section_data['section-classes'] : '';
$section_wrapper_classes = ( isset( $ttfmake_section_data['section-wrapper'] ) ) ? $ttfmake_section_data['section-wrapper'] : false;

if ( $section_wrapper_classes ) {
	echo '<div class="' . esc_attr( $section_wrapper_classes ) . '">';
}
?>
<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>" class="row single <?php echo $section_classes; ?>">
	<div class="column one">

		<?php if ( ! empty( $ttfmake_section_data['title'] ) ) : ?>
			<header>
				<h2><?php echo apply_filters( 'the_title', $ttfmake_section_data['title'] ); ?></h2>
			</header>
		<?php endif; ?>

		<?php if ( ! empty( $ttfmake_section_data['content'] ) ) : ?>
			<?php ttfmake_get_builder_save()->the_builder_content( $ttfmake_section_data['content'] ); ?>
		<?php endif; ?>

	</div>
</section>
<?php

if ( $section_wrapper_classes ) {
	echo '</div>';
}
