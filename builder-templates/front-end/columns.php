<?php
global $ttfmake_section_data, $ttfmake_sections;

$section_type = ( isset( $ttfmake_section_data['section-type'] ) ) ? $ttfmake_section_data['section-type'] : 'wsuwpsidebarright';
$section_type_columns = array(
	'wsuwpsidebarright' => 2,
	'wsuwpsidebarleft'  => 2,
	'wsuwpthirds'       => 3,
	'wsuwphalves'       => 2,
	'wsuwpquarters'     => 4,
	'wsuwpsingle'       => 1,
);
$data_columns = spine_get_column_data( $ttfmake_section_data, $section_type_columns[ $section_type ] );

$column_count = array( 'one', 'two', 'three', 'four' );
$count = 0;

$section_classes = ( isset( $ttfmake_section_data['section-classes'] ) ) ? $ttfmake_section_data['section-classes'] : '';
$section_wrapper_classes = ( isset( $ttfmake_section_data['section-wrapper'] ) ) ? $ttfmake_section_data['section-wrapper'] : false;

if ( 'wsuwpsidebarright' === $section_type || 'wsuwpsidebarleft' === $section_type || 'wsuwpthirds' === $section_type ) {
	$section_layout = ( isset( $ttfmake_section_data['section-layout'] ) ) ? $ttfmake_section_data['section-layout'] : 'side-right';
} elseif ( 'wsuwphalves' === $section_type ) {
	$section_layout = 'halves';
} elseif ( 'wsuwpquarters' === $section_type ) {
	$section_layout = 'quarters';
} else {
	$section_layout = 'single';
}

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
	<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>"
			 class="row <?php echo esc_attr( $section_layout ); ?> <?php echo esc_attr( $section_classes ); ?>">
		<?php
		if ( ! empty( $data_columns ) ) {
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
if ( $section_wrapper_classes ) {
	echo '</div>';
}