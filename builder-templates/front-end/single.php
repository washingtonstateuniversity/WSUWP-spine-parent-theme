<?php
global $ttfmake_section_data;
?>

<section class="single row builder-section <?php echo esc_attr( ttfmake_get_builder_save()->section_classes( $ttfmake_section_data ) ); ?>">
	<div class="column one">
		<?php if ( ! empty( $ttfmake_section_data['title'] ) ) : ?>
			<header class="builder-section-header">
				<h3 class="builder-section-title">
					<?php echo apply_filters( 'the_title', $ttfmake_section_data['title'] ); ?>
				</h3>
			</header>
		<?php endif; ?>

		<?php if ( ! empty( $ttfmake_section_data['content'] ) ) : ?>
			<div class="builder-section-content">
				<?php if ( '' !== $ttfmake_section_data['content'] ) : ?>
					<div class="builder-blank-content">
						<?php ttfmake_get_builder_save()->the_builder_content( $ttfmake_section_data['content'] ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>