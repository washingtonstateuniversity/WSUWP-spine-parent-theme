<?php
global $ttfmake_section_data;

// Assume by default that the section has no wrapper.
$section_has_wrapper = false;

// Sections can have ids (provided by outside forces other than this theme), classes, and wrappers with classes.
$section_classes = ( isset( $ttfmake_section_data['section-classes'] ) ) ? $ttfmake_section_data['section-classes'] : '';
$section_wrapper_classes = ( isset( $ttfmake_section_data['section-wrapper'] ) ) ? $ttfmake_section_data['section-wrapper'] : '';

// If a child theme or plugin has declared a section ID, we handle that.
// This may be supported in the parent theme one day.
$section_id = ( isset( $ttfmake_section_data['section-id'] ) ) ? $ttfmake_section_data['section-id'] : '';

$column_classes = ( isset( $ttfmake_section_data['column-classes'] ) ) ? $ttfmake_section_data['column-classes'] : false;
$header_level = ( isset( $ttfmake_section_data['header-level'] ) && in_array( $ttfmake_section_data['header-level'], array( 'h1', 'h2', 'h3', 'h4' ) ) ) ? $ttfmake_section_data['header-level'] : 'h1';
$column_background = ( isset( $ttfmake_section_data['column-background-image'] ) && ! empty( $ttfmake_section_data['column-background-image'] ) ) ? "background-image:url('" . esc_url( $ttfmake_section_data['column-background-image'] ) . "');" : '';

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
<section id="<?php echo esc_attr( $section_id ); ?>" class="row single h1-header <?php echo esc_attr( $section_classes ); ?>">
	<div style="<?php echo $column_background; ?>" class="column one <?php echo esc_attr( $column_classes ); ?>">
		<?php if ( ! empty( $ttfmake_section_data['title'] ) ) : ?>
			<<?php echo $header_level; ?>><?php echo apply_filters( 'the_title', $ttfmake_section_data['title'] ); ?></<?php echo $header_level; ?>>
		<?php endif; ?>
	</div>
</section>
<?php

if ( $section_has_wrapper ) {
	echo '</div>';
}
