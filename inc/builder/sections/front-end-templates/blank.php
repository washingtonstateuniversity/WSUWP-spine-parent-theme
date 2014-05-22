<?php
/**
 * @package Make
 */

global $ttfmake_section_data, $ttfmake_sections;
?>

<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>" class="builder-section <?php echo esc_attr( ttfmake_get_builder_save()->section_classes( $ttfmake_section_data, $ttfmake_sections ) ); ?>">
	<?php if ( ! empty( $ttfmake_section_data['title'] ) ) : ?>
	<header class="builder-section-header">
		<h3 class="builder-section-title builder-blank-section-title">
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
</section>
