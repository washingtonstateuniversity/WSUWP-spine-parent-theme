<?php
/**
 * @package Make
 */

global $ttfmake_section_data, $ttfmake_sections;

$section_classes = ( isset( $ttfmake_section_data['section-classes'] ) ) ? $ttfmake_section_data['section-classes'] : '';
$section_wrapper_classes = ( isset( $ttfmake_section_data['section-wrapper'] ) ) ? $ttfmake_section_data['section-wrapper'] : false;

$banner_slides = ttfmake_builder_get_banner_array( $ttfmake_section_data );
$is_slider = ( count( $banner_slides ) > 1 ) ? true : false;

$responsive = ( isset( $ttfmake_section_data['responsive'] ) ) ? $ttfmake_section_data['responsive'] : 'balanced';
$slider_height = ( isset( $ttfmake_section_data['height'] ) ) ? absint( $ttfmake_section_data['height'] ) : 0;
if ( 0 === $slider_height ) {
	$slider_height = 600;
}
$slider_ratio = ( $slider_height / 960 ) * 100;
?>
<style type="text/css">
	<?php
	// Maintain aspect ratio
	if ( 'aspect' === $responsive ) : ?>
	#builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?> .builder-banner-slide {
		padding-bottom: <?php echo $slider_ratio; ?>%;
	}
	<?php
	// Balanced
	else : ?>
	#builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?> .builder-banner-slide {
		padding-bottom: <?php echo $slider_height; ?>px;
	}
	@media screen and (min-width: 600px) and (max-width: 960px) {
		#builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?> .builder-banner-slide {
			padding-bottom: <?php echo $slider_ratio; ?>%;
		}
	}
	<?php endif; ?>
</style>
<?php
if ( $section_wrapper_classes ) {
	echo '<div class="' . esc_attr( $section_wrapper_classes ) . '">';
}
?>
<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>" class="row single builder-section <?php echo $section_classes; ?> <?php echo esc_attr( ttfmake_builder_get_banner_class( $ttfmake_section_data, $ttfmake_sections ) ); ?>">
	<div class="column one">
		<?php if ( ! empty( $ttfmake_section_data['title'] ) ) : ?>
			<header>
				<h2><?php echo apply_filters( 'the_title', $ttfmake_section_data['title'] ); ?></h2>
			</header>
		<?php endif; ?>

		<div class="builder-section-content<?php echo ( $is_slider ) ? ' cycle-slideshow' : ''; ?>"<?php echo ( $is_slider ) ? ttfmake_builder_get_banner_slider_atts( $ttfmake_section_data ) : ''; ?>>
			<?php if ( ! empty( $banner_slides ) ) : foreach ( $banner_slides as $slide ) : ?>
				<div class="builder-banner-slide<?php echo ttfmake_builder_banner_slide_class( $slide ); ?>" style="<?php echo ttfmake_builder_banner_slide_style( $slide, $ttfmake_section_data ); ?>">
					<?php if ( ! empty( $slide['slide-url'] ) ) : ?><a href="<?php echo esc_url( $slide['slide-url'] ); ?>"><?php endif; ?>
					<div class="builder-banner-content">
						<div class="builder-banner-inner-content">
							<?php ttfmake_get_builder_save()->the_builder_content( $slide['content'] ); ?>
						</div>
					</div>
					<?php if ( 0 !== absint( $slide['darken'] ) ) : ?>
						<div class="builder-banner-overlay"></div>
					<?php endif; ?>
					<?php if ( ! empty( $slide['slide-url'] ) ) : ?></a><?php endif; ?>
				</div>
			<?php endforeach; endif; ?>
			<?php if ( $is_slider && false === (bool) $ttfmake_section_data['hide-arrows'] ) : ?>
				<div class="cycle-prev"></div>
				<div class="cycle-next"></div>
			<?php endif; ?>
			<?php if ( $is_slider && false === (bool) $ttfmake_section_data['hide-dots'] ) : ?>
				<div class="cycle-pager"></div>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php

if ( $section_wrapper_classes ) {
	echo '</div>';
}
