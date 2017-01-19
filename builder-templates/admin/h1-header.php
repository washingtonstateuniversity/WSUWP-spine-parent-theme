<?php
global $ttfmake_section_data, $ttfmake_is_js_template;

$section_name = ttfmake_get_section_name( $ttfmake_section_data, $ttfmake_is_js_template );

spine_load_section_header();
?>
<div class="wsuwp-spine-builder-column">
	<div class="ttfmake-titlediv">
		<a href="#" class="spine-builder-column-configure"><span>Configure this column</span></a>
		<div class="ttfmake-titlewrap">
			<input placeholder="<?php esc_attr_e( 'Enter title here' ); ?>" type="text" name="<?php echo $section_name; ?>[title]" class="ttfmake-title ttfmake-section-header-title-input" value="<?php
			if ( isset( $ttfmake_section_data['data']['title'] ) ) {
				echo esc_attr( htmlspecialchars( $ttfmake_section_data['data']['title'] ) );
			} ?>" autocomplete="off" />
		</div>
	</div>

	<div class="spine-builder-column-overlay">
		<div class="spine-builder-column-overlay-wrapper">
			<div class="spine-builder-overlay-header">
				<div class="spine-builder-overlay-title">Configure Column</div>
				<div class="spine-builder-column-overlay-close">Done</div>
			</div>
			<div class="spine-builder-overlay-body">
				<?php
				spine_output_builder_column_classes( $section_name, $ttfmake_section_data );
				?>
			</div>
		</div>
	</div>
<input type="hidden"
		class="ttfmake-section-state"
		name="<?php echo $section_name; ?>[state]"
		value="<?php
if ( isset( $ttfmake_section_data['data']['state'] ) ) {
	echo esc_attr( $ttfmake_section_data['data']['state'] );
} else {
	echo 'open';
} ?>" />
</div>
<div class="spine-builder-overlay">
	<div class="spine-builder-overlay-wrapper">
		<div class="spine-builder-overlay-header">
			<div class="spine-builder-overlay-title">Configure Section</div>
			<div class="spine-builder-overlay-close">Done</div>
		</div>
		<div class="spine-builder-overlay-body">
			<?php
			spine_output_builder_section_layout( $section_name, $ttfmake_section_data );
			spine_output_builder_section_classes( $section_name, $ttfmake_section_data );
			spine_output_builder_section_wrapper( $section_name, $ttfmake_section_data );
			spine_output_builder_section_label( $section_name, $ttfmake_section_data );
			spine_output_builder_section_background( $section_name, $ttfmake_section_data );

			do_action( 'spine_output_builder_section', $section_name, $ttfmake_section_data, 'h1-header' );
			?>
		</div>
	</div>
</div>
<?php spine_load_section_footer(); ?>

<style>
	.ttfmake-section-wsuwpheader .ttfmake-section-remove {
		margin-top: 0;
	}
</style>
