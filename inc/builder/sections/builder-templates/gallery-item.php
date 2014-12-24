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

// Set up the combined section + slide ID
$section_id  = ( isset( $ttfmake_section_data['data']['id'] ) ) ? $ttfmake_section_data['data']['id'] : '';
$combined_id = ( true === $ttfmake_is_js_template ) ? '{{{ parentID }}}-{{{ id }}}' : $section_id . '-' . $ttfmake_gallery_id;
$overlay_id  = 'ttfmake-overlay-' . $combined_id;
?>

<?php if ( true !== $ttfmake_is_js_template ) : ?>
<div class="ttfmake-gallery-item" id="ttfmake-gallery-item-<?php echo esc_attr( $ttfmake_gallery_id ); ?>" data-id="<?php echo esc_attr( $ttfmake_gallery_id ); ?>" data-section-type="gallery-item">
<?php endif; ?>

	<div title="<?php esc_attr_e( 'Drag-and-drop this item into place', 'make' ); ?>" class="ttfmake-sortable-handle">
		<div class="sortable-background"></div>
	</div>

	<?php echo ttfmake_get_builder_base()->add_uploader( $section_name, ttfmake_sanitize_image_id( $image_id ), __( 'Set gallery image', 'make' ) ); ?>

	<a href="#" class="configure-gallery-item-link ttfmake-overlay-open" title="<?php esc_attr_e( 'Configure item', 'make' ); ?>" data-overlay="#<?php echo $overlay_id; ?>">
		<span>
			<?php _e( 'Configure item', 'make' ); ?>
		</span>
	</a>
	<a href="#" class="edit-content-link edit-gallery-item-link<?php if ( ! empty( $description ) ) : ?> item-has-content<?php endif; ?>" data-textarea="ttfmake-content-<?php echo $combined_id; ?>" title="<?php esc_attr_e( 'Edit content', 'make' ); ?>">
		<span>
			<?php _e( 'Edit content', 'make' ); ?>
		</span>
	</a>
	<a href="#" class="remove-gallery-item-link ttfmake-gallery-item-remove" title="<?php esc_attr_e( 'Delete item', 'make' ); ?>">
		<span>
			<?php _e( 'Delete item', 'make' ); ?>
		</span>
	</a>

	<?php ttfmake_get_builder_base()->add_frame( $combined_id, $section_name . '[description]', $description, false ); ?>

	<?php
	global $ttfmake_overlay_class, $ttfmake_overlay_id, $ttfmake_overlay_title;
	$ttfmake_overlay_class = 'ttfmake-configuration-overlay';
	$ttfmake_overlay_id    = $overlay_id;
	$ttfmake_overlay_title = __( 'Configure item', 'make' );

	get_template_part( '/inc/builder/core/templates/overlay', 'header' );

	/**
	 * Filter the definitions of the Gallery item configuration inputs.
	 *
	 * @since 1.4.0.
	 *
	 * @param array    $inputs    The input definition array.
	 */
	$inputs = apply_filters( 'make_gallery_item_configuration', array(
		100 => array(
			'type'    => 'section_title',
			'name'    => 'title',
			'label'   => __( 'Title', 'make' ),
			'default' => '',
			'class'   => 'ttfmake-configuration-title',
		),
		200 => array(
			'type'    => 'text',
			'name'    => 'link',
			'label'   => __( 'Item link URL', 'make' ),
			'default' => '',
		),
	) );

	// Sort the config in case 3rd party code added another input
	ksort( $inputs, SORT_NUMERIC );

	// Print the inputs
	$output = '';

	foreach ( $inputs as $input ) {
		if ( isset( $input['type'] ) && isset( $input['name'] ) ) {
			$section_data  = ( isset( $ttfmake_section_data['data']['gallery-items'][ $ttfmake_gallery_id ] ) ) ? $ttfmake_section_data['data']['gallery-items'][ $ttfmake_gallery_id ] : array();
			$output       .= ttfmake_create_input( $section_name, $input, $section_data );
		}
	}

	echo $output;

	get_template_part( '/inc/builder/core/templates/overlay', 'footer' );
	?>

<?php if ( true !== $ttfmake_is_js_template ) : ?>
</div>
<?php endif; ?>