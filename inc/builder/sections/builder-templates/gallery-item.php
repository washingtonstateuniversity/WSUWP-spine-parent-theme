<?php
/**
 * @package Make
 */

global $ttfmake_section_data, $ttfmake_is_js_template, $ttfmake_gallery_id;
$section_name = 'ttfmake-section';
if ( true === $ttfmake_is_js_template ) {
	$section_name .= '[{{{ parentID }}}][gallery-items][{{{ id }}}]';
} else {
	$section_name .= '[' . $ttfmake_section_data['data']['id'] . '][gallery-items][' . $ttfmake_gallery_id . ']';
}

$title       = ( isset( $ttfmake_section_data['data']['gallery-items'][ $ttfmake_gallery_id ]['title'] ) ) ? $ttfmake_section_data['data']['gallery-items'][ $ttfmake_gallery_id ]['title'] : '';
$link        = ( isset( $ttfmake_section_data['data']['gallery-items'][ $ttfmake_gallery_id ]['link'] ) ) ? $ttfmake_section_data['data']['gallery-items'][ $ttfmake_gallery_id ]['link'] : '';
$image_id    = ( isset( $ttfmake_section_data['data']['gallery-items'][ $ttfmake_gallery_id ]['image-id'] ) ) ? $ttfmake_section_data['data']['gallery-items'][ $ttfmake_gallery_id ]['image-id'] : 0;
$description = ( isset( $ttfmake_section_data['data']['gallery-items'][ $ttfmake_gallery_id ]['description'] ) ) ? $ttfmake_section_data['data']['gallery-items'][ $ttfmake_gallery_id ]['description'] : '';
?>
<?php if ( true !== $ttfmake_is_js_template ) : ?>
<div class="ttfmake-gallery-item" id="ttfmake-gallery-item-<?php echo esc_attr( $ttfmake_gallery_id ); ?>" data-id="<?php echo esc_attr( $ttfmake_gallery_id ); ?>" data-section-type="gallery-item">
<?php endif; ?>
	<div title="<?php esc_attr_e( 'Drag-and-drop this column into place', 'make' ); ?>" class="ttfmake-sortable-handle">
		<div class="sortable-background"></div>
	</div>

	<div class="ttfmake-titlediv">
		<input placeholder="<?php esc_attr_e( 'Enter link here', 'make' ); ?>" type="text" name="<?php echo $section_name; ?>[link]" class="ttfmake-link code widefat" value="<?php echo esc_url( $link ); ?>" autocomplete="off" />
	</div>

	<?php ttfmake_get_builder_base()->add_uploader( $section_name, ttfmake_sanitize_image_id( $image_id ) ); ?>

	<div class="ttfmake-titlediv">
		<div class="ttfmake-titlewrap">
			<input placeholder="<?php esc_attr_e( 'Enter title here', 'make' ); ?>" type="text" name="<?php echo $section_name; ?>[title]" class="ttfmake-title" value="<?php echo esc_attr( htmlspecialchars( $title ) ); ?>" autocomplete="off" />
		</div>
	</div>

	<div class="ttfmake-gallery-item-description-wrapper">
		<textarea placeholder="<?php esc_attr_e( 'Enter description here', 'make' ); ?>" name="<?php echo $section_name; ?>[description]"><?php echo esc_textarea( $description ); ?></textarea>
	</div>

	<a href="#" class="ttfmake-gallery-item-remove">
		<?php _e( 'Remove this item', 'make' ); ?>
	</a>
<?php if ( true !== $ttfmake_is_js_template ) : ?>
</div>
<?php endif; ?>