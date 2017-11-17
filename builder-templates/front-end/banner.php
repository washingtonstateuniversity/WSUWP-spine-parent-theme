<?php
global $ttfmake_section_data, $ttfmake_sections;

// Assume by default that the section has no wrapper.
$section_has_wrapper = false;

// Sections can have ids (provided by outside forces other than this theme), classes, and wrappers with classes.
$section_classes = ( isset( $ttfmake_section_data['section-classes'] ) ) ? $ttfmake_section_data['section-classes'] : '';
$section_wrapper_classes = ( isset( $ttfmake_section_data['section-wrapper'] ) ) ? $ttfmake_section_data['section-wrapper'] : '';

// If a child theme or plugin has declared a section ID, we handle that.
// This may be supported in the parent theme one day.
$section_id = ( isset( $ttfmake_section_data['section-id'] ) ) ? $ttfmake_section_data['section-id'] : '';

$column_classes = ( isset( $ttfmake_section_data['column-classes'] ) ) ? $ttfmake_section_data['column-classes'] : '';

$banner_slides = ttfmake_builder_get_banner_array( $ttfmake_section_data );
$is_slider = ( count( $banner_slides ) > 1 ) ? true : false;

$responsive = ( isset( $ttfmake_section_data['responsive'] ) ) ? $ttfmake_section_data['responsive'] : 'balanced';
$slider_height = absint( $ttfmake_section_data['height'] );
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
if ( isset( $ttfmake_section_data['background-img'] ) && ! empty( $ttfmake_section_data['background-img'] ) ) {
	$section_background = $ttfmake_section_data['background-img'];
} else {
	$section_background = false;
}

if ( isset( $ttfmake_section_data['background-mobile-img'] ) && ! empty( $ttfmake_section_data['background-mobile-img'] ) ) {
	$section_mobile_background = $ttfmake_section_data['background-mobile-img'];
} elseif ( $section_background ) {
	$section_mobile_background = $section_background;
} else {
	$section_mobile_background = false;
}

// If a section has wrapper classes assigned, assume it (obviously) needs a wrapper.
if ( '' !== $section_wrapper_classes ) {
	$section_has_wrapper = true;
}

if ( $section_background || $section_mobile_background ) {
	$section_has_wrapper = true;
	$section_wrapper_classes .= ' section-wrapper-has-background';
}

if ( $section_has_wrapper ) {
	$section_wrapper_html = '<div';

	if ( '' !== $section_id ) {
		$section_wrapper_html .= ' id="' . esc_attr( $section_id ) . '"';
	}

	$section_wrapper_html .= ' class="section-wrapper ' . esc_attr( $section_wrapper_classes ) . '"';

	if ( $section_background ) {
		$section_wrapper_html .= ' data-background="' . esc_url( $section_background ) . '"';
	}

	if ( $section_mobile_background ) {
		$section_wrapper_html .= ' data-background-mobile="' . esc_url( $section_mobile_background ) . '"';
	}

	$section_wrapper_html .= '>';

	echo $section_wrapper_html;

	// Reset section_id so that the default is built for the section.
	$section_id = '';
}

// If a section ID is not available for use, we build a default ID.
if ( '' === $section_id ) {
	$section_id = 'builder-section-' . esc_attr( $ttfmake_section_data['id'] );
} else {
	$section_id = sanitize_key( $section_id );
}
?>
<section id="<?php echo esc_attr( $section_id ); ?>" class="row single builder-section <?php echo $section_classes; ?> <?php echo esc_attr( ttfmake_builder_get_banner_class( $ttfmake_section_data, $ttfmake_sections ) ); ?>">
	<div class="column one <?php echo esc_attr( $column_classes ); ?>">
		<?php if ( ! empty( $ttfmake_section_data['title'] ) ) : ?>
			<header>
				<h2><?php echo apply_filters( 'the_title', $ttfmake_section_data['title'] ); ?></h2>
			</header>
		<?php endif; ?>

		<div class="builder-section-content<?php echo ( $is_slider ) ? ' cycle-slideshow' : ''; ?>"<?php echo ( $is_slider ) ? ttfmake_builder_get_banner_slider_atts( $ttfmake_section_data ) : ''; ?>>
			<?php if ( ! empty( $banner_slides ) ) : $i = 0; foreach ( $banner_slides as $slide ) : ?>
				<div class="builder-banner-slide<?php echo ttfmake_builder_banner_slide_class( $slide ); echo ( 0 == $i++ ) ? ' first-slide' : ''; ?>" style="<?php echo ttfmake_builder_banner_slide_style( $slide, $ttfmake_section_data ); ?>">
					<?php if ( ! empty( $slide['slide-url'] ) ) : ?><a href="<?php echo esc_url( $slide['slide-url'] ); ?>"><?php endif; ?>
					<div class="builder-banner-content">
						<?php if ( ! empty( $slide['slide-title'] ) ) : ?>
						<div class="builder-banner-inner-title">
							<span class="builder-banner-slide-title"><?php echo esc_html( $slide['slide-title'] ); ?></span>
						</div>
						<?php endif; ?>
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
			<?php if ( $is_slider && false === (bool) $ttfmake_section_data['hide-dots'] ) : ?>
				<div class="cycle-pager"></div>
			<?php endif; ?>
			<?php if ( $is_slider && false === (bool) $ttfmake_section_data['hide-arrows'] ) : ?>
				<div class="cycle-prev"></div>
				<div class="cycle-next"></div>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php

if ( $section_has_wrapper ) {
	echo '</div>';
}
