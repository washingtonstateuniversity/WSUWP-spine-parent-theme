<?php
global $ttfmake_section_data, $ttfmake_is_js_template;

$section_name = ttfmake_get_section_name( $ttfmake_section_data, $ttfmake_is_js_template );

ttfmake_load_section_header();
?>

<div class="ttfmake-titlediv">
	<div class="ttfmake-titlewrap">
		<input placeholder="<?php esc_attr_e( 'Enter title here' ); ?>" type="text" name="<?php echo $section_name; ?>[title]" class="ttfmake-title ttfmake-section-header-title-input" value="<?php if ( isset( $ttfmake_section_data['data']['title'] ) ) echo esc_attr( htmlspecialchars( $ttfmake_section_data['data']['title'] ) ); ?>" autocomplete="off" />
	</div>
</div>

<div class="wsuwp-builder-meta" style="width:100%; margin-top:10px;">
	<label for="<?php echo $section_name; ?>[section-classes]">Section Classes</label><input type="text" id="<?php echo $section_name; ?>[section-classes]" class="wsuwp-builder-section-classes widefat" name="<?php echo $section_name; ?>[section-classes]" value="<?php if ( isset( $ttfmake_section_data['data']['section-classes'] ) ) echo esc_attr( $ttfmake_section_data['data']['section-classes'] ); ?>" />
</div>

<div class="wsuwp-builder-meta" style="width:100%; margin-top:10px;">
	<label for="<?php echo $section_name; ?>[section-wrapper]">Section Wrapper</label><input type="text" id="<?php echo $section_name; ?>[section-wrapper]" class="wsuwp-builder-section-wrapper widefat" name="<?php echo $section_name; ?>[section-wrapper]" value="<?php if ( isset( $ttfmake_section_data['data']['section-wrapper'] ) ) echo esc_attr( $ttfmake_section_data['data']['section-wrapper'] ); ?>" />
</div>

<input type="hidden" class="ttfmake-section-state" name="<?php echo $section_name; ?>[state]" value="<?php if ( isset( $ttfmake_section_data['data']['state'] ) ) echo esc_attr( $ttfmake_section_data['data']['state'] ); else echo 'open'; ?>" />

<?php ttfmake_load_section_footer(); ?>

<style>
	.ttfmake-section-wsuwpheader .ttfmake-section-remove {
		margin-top: 0;
	}
</style>