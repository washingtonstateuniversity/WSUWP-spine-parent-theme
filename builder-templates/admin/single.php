<?php
global $ttfmake_section_data, $ttfmake_is_js_template;

$section_name = ttfmake_get_section_name( $ttfmake_section_data, $ttfmake_is_js_template );

get_template_part( 'builder-templates/admin/section', 'header' );
?>

<div class="ttfmake-titlediv">
	<div class="ttfmake-titlewrap">
		<input placeholder="<?php esc_attr_e( 'Enter title here' ); ?>" type="text" name="<?php echo $section_name; ?>[title]" class="ttfmake-title ttfmake-section-header-title-input" value="<?php if ( isset( $ttfmake_section_data['data']['title'] ) ) echo esc_attr( htmlspecialchars( $ttfmake_section_data['data']['title'] ) ); ?>" autocomplete="off" />
	</div>
</div>

<?php

$editor_settings = array(
	'tinymce'       => true,
	'quicktags'     => true,
	'editor_height' => 345,
	'textarea_name' => $section_name . '[content]',
);

if ( true === $ttfmake_is_js_template ) {
	ttfmake_get_builder_base()->wp_editor( '', ttfmake_get_wp_editor_id( $ttfmake_section_data, $ttfmake_is_js_template ), $editor_settings );
} else {
	$content = ( isset( $ttfmake_section_data['data']['content'] ) ) ? $ttfmake_section_data['data']['content'] : '';
	ttfmake_get_builder_base()->wp_editor( $content, ttfmake_get_wp_editor_id( $ttfmake_section_data, $ttfmake_is_js_template ), $editor_settings );
}

?>
<div id="<?php echo $section_name; ?>-advanced" class="builder-advanced-controls">
<?php
spine_output_builder_section_classes( $section_name, $ttfmake_section_data );
spine_output_builder_section_wrapper( $section_name, $ttfmake_section_data );
?>
</div>
<input type="hidden" class="ttfmake-section-state" name="<?php echo $section_name; ?>[state]" value="<?php if ( isset( $ttfmake_section_data['data']['state'] ) ) echo esc_attr( $ttfmake_section_data['data']['state'] ); else echo 'open'; ?>" />

<?php ttfmake_load_section_footer(); ?>