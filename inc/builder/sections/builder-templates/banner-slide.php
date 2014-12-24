<?php
/**
 * @package Make
 */

global $ttfmake_section_data, $ttfmake_is_js_template, $ttfmake_slide_id;
$section_name = 'ttfmake-section';
if ( true === $ttfmake_is_js_template ) {
	$section_name .= '[{{{ parentID }}}][banner-slides][{{{ id }}}]';
} else {
	$section_name .= '[' . $ttfmake_section_data[ 'data' ][ 'id' ] . '][banner-slides][' . $ttfmake_slide_id . ']';
}

$content          = ( isset( $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['content'] ) ) ? $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['content'] : '';
$background_color = ( isset( $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['background-color'] ) ) ? $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['background-color'] : '';
$darken           = ( isset( $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['darken'] ) ) ? $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['darken'] : 0;
$image_id         = ( isset( $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['image-id'] ) ) ? $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['image-id'] : 0;
$alignment        = ( isset( $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['alignment'] ) ) ? $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['alignment'] : 'none';
$state            = ( isset( $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['state'] ) ) ? $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ]['state'] : 'open';

// Set up the combined section + slide ID
$section_id  = ( isset( $ttfmake_section_data['data']['id'] ) ) ? $ttfmake_section_data['data']['id'] : '';
$combined_id = ( true === $ttfmake_is_js_template ) ? '{{{ parentID }}}-{{{ id }}}' : $section_id . '-' . $ttfmake_slide_id;
$overlay_id  = 'ttfmake-overlay-' . $combined_id;
?>

<?php if ( true !== $ttfmake_is_js_template ) : ?>
<div class="ttfmake-banner-slide" id="ttfmake-banner-slide-<?php echo esc_attr( $ttfmake_slide_id ); ?>" data-id="<?php echo esc_attr( $ttfmake_slide_id ); ?>" data-section-type="banner-slide">
<?php endif; ?>

	<div title="<?php esc_attr_e( 'Drag-and-drop this slide into place', 'make' ); ?>" class="ttfmake-sortable-handle">
		<div class="sortable-background"></div>
	</div>

	<?php echo ttfmake_get_builder_base()->add_uploader( $section_name, ttfmake_sanitize_image_id( $image_id ), __( 'Set banner image', 'make' ) ); ?>

	<a href="#" class="configure-banner-slide-link ttfmake-banner-slide-configure ttfmake-overlay-open" title="<?php esc_attr_e( 'Configure slide', 'make' ); ?>" data-overlay="#<?php echo $overlay_id; ?>">
		<span>
			<?php _e( 'Configure slide', 'make' ); ?>
		</span>
	</a>
	<a href="#" class="edit-content-link edit-banner-slide-link<?php if ( ! empty( $content ) ) : ?> item-has-content<?php endif; ?>" title="<?php esc_attr_e( 'Edit content', 'make' ); ?>" data-textarea="ttfmake-content-<?php echo $combined_id; ?>">
		<span>
			<?php _e( 'Edit content', 'make' ); ?>
		</span>
	</a>
	<a href="#" class="remove-banner-slide-link ttfmake-banner-slide-remove" title="<?php esc_attr_e( 'Delete slide', 'make' ); ?>">
		<span>
			<?php _e( 'Delete slide', 'make' ); ?>
		</span>
	</a>

	<?php ttfmake_get_builder_base()->add_frame( $combined_id, $section_name . '[content]', $content, false ); ?>

	<?php
	global $ttfmake_overlay_class, $ttfmake_overlay_id, $ttfmake_overlay_title;
	$ttfmake_overlay_class = 'ttfmake-configuration-overlay';
	$ttfmake_overlay_id    = $overlay_id;
	$ttfmake_overlay_title = __( 'Configure slide', 'make' );

	get_template_part( '/inc/builder/core/templates/overlay', 'header' );

	/**
	 * Filter the definitions of the Banner slide configuration inputs.
	 *
	 * @since 1.4.0.
	 *
	 * @param array    $inputs    The input definition array.
	 */
	$inputs = apply_filters( 'make_banner_slide_configuration', array(
		100 => array(
			'type'    => 'select',
			'name'    => 'alignment',
			'label'   => __( 'Content position', 'make' ),
			'default' => 'none',
			'options' => array(
				'none'  => __( 'None', 'make' ),
				'left'  => __( 'Left', 'make' ),
				'right' => __( 'Right', 'make' ),
			),
		),
		200 => array(
			'type'    => 'checkbox',
			'label'   => __( 'Darken background to improve readability', 'make' ),
			'name'    => 'darken',
			'default' => 0
		),
		300 => array(
			'type'    => 'color',
			'label'   => __( 'Background color', 'make' ),
			'name'    => 'background-color',
			'class'   => 'ttfmake-gallery-background-color ttfmake-configuration-color-picker',
			'default' => '',
		),
	) );

	// Sort the config in case 3rd party code added another input
	ksort( $inputs, SORT_NUMERIC );

	// Print the inputs
	$output = '';

	foreach ( $inputs as $input ) {
		if ( isset( $input['type'] ) && isset( $input['name'] ) ) {
			$section_data  = ( isset( $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ] ) ) ? $ttfmake_section_data['data']['banner-slides'][ $ttfmake_slide_id ] : array();
			$output       .= ttfmake_create_input( $section_name, $input, $section_data );
		}
	}

	echo $output;

	get_template_part( '/inc/builder/core/templates/overlay', 'footer' );
	?>

	<input type="hidden" class="ttfmake-banner-slide-state" name="<?php echo $section_name; ?>[state]" value="<?php echo esc_attr( $state ); ?>" />

	<?php if ( true !== $ttfmake_is_js_template ) : ?>
</div>
<?php endif; ?>
