<?php
global $ttfmake_section_data, $ttfmake_sections;

// Default to sidebar right if a section type has not been specified.
$section_type = ( isset( $ttfmake_section_data['section-type'] ) ) ? $ttfmake_section_data['section-type'] : 'wsuwpsidebarright';

if ( 'wsuwpsidebarright' === $section_type || 'wsuwpsidebarleft' === $section_type || 'wsuwpthirds' === $section_type ) {
	$section_layout = ( isset( $ttfmake_section_data['section-layout'] ) ) ? $ttfmake_section_data['section-layout'] : 'side-right';
} elseif ( 'wsuwphalves' === $section_type ) {
	$section_layout = 'halves';
} elseif ( 'wsuwpquarters' === $section_type ) {
	$section_layout = 'quarters';
} else {
	$section_layout = 'single';
}

// Provide a list matching the number of columns to the selected section type.
$section_type_columns = array(
	'wsuwpsidebarright' => 2,
	'wsuwpsidebarleft'  => 2,
	'wsuwpthirds'       => 3,
	'wsuwphalves'       => 2,
	'wsuwpquarters'     => 4,
	'wsuwpsingle'       => 1,
);

// Retrieve data for the column being output.
$data_columns = spine_get_column_data( $ttfmake_section_data, $section_type_columns[ $section_type ] );

// Assume by default that the section has no wrapper.
$section_has_wrapper = false;

// Sections can have ids (provided by outside forces other than this theme), classes, and wrappers with classes.
$section_classes = ( isset( $ttfmake_section_data['section-classes'] ) ) ? $ttfmake_section_data['section-classes'] : '';
$section_wrapper_classes = ( isset( $ttfmake_section_data['section-wrapper'] ) ) ? $ttfmake_section_data['section-wrapper'] : '';

// Section header.
$section_title = ( isset( $ttfmake_section_data['section-title'] ) ) ? $ttfmake_section_data['section-title'] : '';
$section_header_level = ( isset( $ttfmake_section_data['header-level'] ) ) ? $ttfmake_section_data['header-level'] : '';

// If a child theme or plugin has declared a section ID, we handle that.
// This may be supported in the parent theme one day.
$section_id = ( isset( $ttfmake_section_data['section-id'] ) ) ? $ttfmake_section_data['section-id'] : '';

// If a background image has been assigned to the section, capture it for use.
if ( isset( $ttfmake_section_data['background-img'] ) && ! empty( $ttfmake_section_data['background-img'] ) ) {
	$section_background = $ttfmake_section_data['background-img'];
} else {
	$section_background = false;
}

// If a mobile background image has been assigned to the section, capture it. Fallback to the section background.
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

// If a background image has been assigned, a wrapper is required.
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
	<section id="<?php echo esc_attr( $section_id ); ?>" class="row <?php echo esc_attr( $section_layout ); ?> <?php echo esc_attr( $section_classes ); ?>">
		<?php // Output the section title
			if ( '' !== $section_title ) {
				$section_header_level = in_array( $section_header_level, array( 'h1', 'h2', 'h3', 'h4' ), true ) ? $section_header_level : 'h2';
				?>
				<header>
					<<?php echo $section_header_level; ?>><?php echo apply_filters( 'the_title', $section_title ); ?></<?php echo $section_header_level; ?>>
				</header>
		<?php } ?>
		<?php
		if ( ! empty( $data_columns ) ) {
			// We output the column's number as part of a class and need to track count.
			$column_count = array( 'one', 'two', 'three', 'four' );
			$count = 0;
			foreach ( $data_columns as $column ) {
				if ( isset( $column['column-background-image'] ) && ! empty( $column['column-background-image'] ) ) {
					$column_background = "background-image:url('" . esc_url( $column['column-background-image'] ) . "');";
				} else {
					$column_background = '';
				}
				?>
				<div style="<?php echo $column_background; ?>" class="column <?php echo $column_count[ $count ]; $count++; ?> <?php if ( isset( $column['column-classes'] ) ) : echo esc_attr( $column['column-classes'] ); endif; ?>">

					<?php if ( '' !== $column['title'] ) : ?>
						<?php $header_level = in_array( $column['header-level'], array( 'h2', 'h3', 'h4', 'h5' ), true ) ? $column['header-level'] : 'h2'; ?>
						<header>
							<<?php echo $header_level; ?>><?php echo apply_filters( 'the_title', $column['title'] ); ?></<?php echo $header_level; ?>>
						</header>
					<?php endif; ?>

					<?php if ( '' !== $column['content'] ) : ?>
						<?php ttfmake_get_builder_save()->the_builder_content( $column['content'] ); ?>
					<?php endif; ?>

				</div>
			<?php
			}
		}
		?>
	</section>
<?php
if ( $section_has_wrapper ) {
	echo '</div>';
}
