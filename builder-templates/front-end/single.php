<?php
global $ttfmake_section_data, $ttfmake_sections;

if ( isset( $ttfmake_section_data['section-classes'] ) ) {
	$section_classes = $ttfmake_section_data['section-classes'];
} else {
	$section_classes = '';
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