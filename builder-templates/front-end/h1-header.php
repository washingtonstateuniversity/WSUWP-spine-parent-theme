<?php
global $ttfmake_section_data;

$section_classes = ( isset( $ttfmake_section_data['section-classes'] ) ) ? $ttfmake_section_data['section-classes'] : '';
$section_wrapper_classes = ( isset( $ttfmake_section_data['section-wrapper'] ) ) ? $ttfmake_section_data['section-wrapper'] : false;
$column_classes = ( isset( $ttfmake_section_data['column-classes'] ) ) ? $ttfmake_section_data['column-classes'] : false;


if ( isset( $ttfmake_section_data['background-img'] ) && ! empty( $ttfmake_section_data['background-img'] ) ) {
	$section_background = $ttfmake_section_data['background-img'];
} else {
	$section_background = false;
}

if ( isset( $ttfmake_section_data['background-mobile-img'] ) && ! empty( $ttfmake_section_data['background-mobile-img'] ) ) {
	$section_mobile_background = $ttfmake_section_data['background-mobile-img'];
} elseif( $section_background ) {
	$section_mobile_background = $section_background;
} else {
	$section_mobile_background = false;
}

if ( $section_background || $section_mobile_background ) {
	if ( $section_wrapper_classes ) {
		$section_wrapper_classes .= ' section-wrapper-has-background';
	} else {
		$section_wrapper_classes = 'section-wrapper-has-background';
	}
}

if ( $section_wrapper_classes ) {
	?><div class="<?php echo esc_attr( $section_wrapper_classes ); ?>"
	<?php if ( $section_background ) : echo 'data-background="' . esc_url( $section_background ) . '"'; endif; ?>
	<?php if ( $section_mobile_background ) : echo 'data-background-mobile="' . esc_url( $section_mobile_background ) . '"'; endif; ?>>
<?php
}
?>
<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>" class="row single h1-header <?php echo esc_attr( $section_classes ); ?>">
	<div class="column one <?php echo esc_attr( $column_classes ); ?>">
		<?php if ( ! empty( $ttfmake_section_data['title'] ) ) : ?>
			<h1><?php echo apply_filters( 'the_title', $ttfmake_section_data['title'] ); ?></h1>
		<?php endif; ?>
	</div>
</section>
<?php

if ( $section_wrapper_classes ) {
	echo '</div>';
}