<?php
global $ttfmake_section_data, $ttfmake_sections;
?>
<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>" class="row single">
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