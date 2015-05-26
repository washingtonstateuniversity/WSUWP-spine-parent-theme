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
$section_classes         = ( isset( $ttfmake_section_data['section-classes'] ) ) ? $ttfmake_section_data['section-classes'] : '';
$section_wrapper_classes = ( isset( $ttfmake_section_data['section-wrapper'] ) ) ? $ttfmake_section_data['section-wrapper'] : '';
$section_ids             = ( isset( $ttfmake_section_data['section-ids'] ) )     ? $ttfmake_section_data['section-ids']     : '';

// If a background image has been assigned to the section, capture it for use.
if ( isset( $ttfmake_section_data['background-img'] ) && ! empty( $ttfmake_section_data['background-img'] ) ) {
	$section_background = $ttfmake_section_data['background-img'];
} else {
	$section_background = false;
}

// If a mobile background image has been assigned to the section, capture it. Fallback to the section background.
if ( isset( $ttfmake_section_data['background-mobile-img'] ) && ! empty( $ttfmake_section_data['background-mobile-img'] ) ) {
	$section_mobile_background = $ttfmake_section_data['background-mobile-img'];
} elseif( $section_background ) {
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
	?><div
		<?php if ( '' !== $section_ids ) : echo ' id="' . esc_attr( $section_ids ) . '"'; endif; ?>
		class="section-wrapper <?php echo esc_attr( $section_wrapper_classes ); ?>"
		<?php if ( $section_background ) : echo 'data-background="' . esc_url( $section_background ) . '"'; endif; ?>
		<?php if ( $section_mobile_background ) : echo 'data-background-mobile="' . esc_url( $section_mobile_background ) . '"'; endif; ?>>
	<?php
}
?>
	<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] );?><?php if ( false === $section_has_wrapper ) : echo ' ' . esc_attr( $section_ids ); endif; ?>"
			 class="row <?php echo esc_attr( $section_layout ); ?> <?php echo esc_attr( $section_classes ); ?>">
		<?php
		if ( ! empty( $data_columns ) ) {
			// We output the column's number as part of a class and need to track count.
			$column_count = array( 'one', 'two', 'three', 'four' );
			$count = 0;
			foreach( $data_columns as $column ) {
				?>
				<div class="column <?php echo $column_count[ $count ]; $count++; ?> <?php if ( isset( $column['column-classes'] ) ) : echo esc_attr( $column['column-classes'] ); endif; ?>">

					<?php if ( '' !== $column['title'] ) : ?>
						<header>
							<h2><?php echo apply_filters( 'the_title', $column['title'] ); ?></h2>
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