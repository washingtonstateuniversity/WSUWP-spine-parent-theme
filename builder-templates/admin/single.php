<?php
global $ttfmake_section_data, $ttfmake_is_js_template;

$section_id     = ( isset( $ttfmake_section_data['data']['id'] ) ) ? $ttfmake_section_data['data']['id'] : '{{{ id }}}';
$section_name = ttfmake_get_section_name( $ttfmake_section_data, $ttfmake_is_js_template );
$content = ( isset( $ttfmake_section_data['data']['content'] ) ) ? $ttfmake_section_data['data']['content'] : '';

$iframe_id   = 'ttfmake-iframe-' . $section_id;
$textarea_id = 'ttfmake-content-' . $section_id;
$overlay_id  = 'ttfmake-overlay-' . $section_id . '-1';
$title       = ( isset( $ttfmake_section_data['data']['title'] ) ) ? $ttfmake_section_data['data']['title'] : '';

$item_has_content = ( ! empty( $content ) ) ? ' item-has-content' : '';

ttfmake_load_section_header();
?>

<div class="spine-builder-layout-single ttfmake-titlediv ttfmake-text-columns-stage ttfmake-text-columns-1">
	<div class="ttfmake-titlewrap">
		<input placeholder="<?php esc_attr_e( 'Enter title here' ); ?>" type="text" name="<?php echo $section_name; ?>[title]" class="ttfmake-title ttfmake-section-header-title-input" value="<?php if ( isset( $ttfmake_section_data['data']['title'] ) ) echo esc_attr( htmlspecialchars( $ttfmake_section_data['data']['title'] ) ); ?>" autocomplete="off" />
	</div>

<?php

$column_buttons = array(
	100 => array(
		'label'              => __( 'Configure column', 'make' ),
		'href'               => '#',
		'class'              => 'configure-column-link ttfmake-overlay-open',
		'title'              => __( 'Configure column', 'make' ),
		'other-a-attributes' => ' data-overlay="#' . $overlay_id .'"',
	),
	200 => array(
		'label'              => __( 'Edit text column', 'make' ),
		'href'               => '#',
		'class'              => 'edit-content-link edit-text-column-link' . $item_has_content,
		'title'              => __( 'Edit content', 'make' ),
		'other-a-attributes' => 'data-textarea="' . esc_attr( $textarea_id ) . '" data-iframe="' . esc_attr( $iframe_id ) . '"',
	),
);

/**
 * Filter the buttons added to a text column.
 *
 * @since 1.4.0.
 *
 * @param array    $column_buttons          The current list of buttons.
 * @param array    $ttfmake_section_data    All data for the section.
 */
$column_buttons = apply_filters( 'make_column_buttons', $column_buttons, $ttfmake_section_data );
ksort( $column_buttons );

/**
 * Execute code before an individual text column is displayed.
 *
 * @since 1.2.3.
 *
 * @param array    $ttfmake_section_data    The data for the section.
 */
do_action( 'make_section_text_before_column', $ttfmake_section_data );

foreach ( $column_buttons as $button ) : ?>
	<a href="<?php echo esc_url( $button['href'] ); ?>" class="column-buttons <?php echo esc_attr( $button['class'] ); ?>" title="<?php echo esc_attr( $button['title'] ); ?>" <?php if ( ! empty( $button['other-a-attributes'] ) ) echo $button['other-a-attributes']; ?>>
			<span>
				<?php echo esc_html( $button['label'] ); ?>
			</span>
	</a>
<?php endforeach;

ttfmake_get_builder_base()->add_frame( $section_id , $section_name . '[content]', $content );

/**
 * Execute code after an individual text column is displayed.
 *
 * @since 1.2.3.
 *
 * @param array    $ttfmake_section_data    The data for the section.
 */
do_action( 'make_section_text_after_column', $ttfmake_section_data );
?>

<?php
global $ttfmake_overlay_class, $ttfmake_overlay_id, $ttfmake_overlay_title;
$ttfmake_overlay_class = 'ttfmake-configuration-overlay';
$ttfmake_overlay_id    = $overlay_id;
$ttfmake_overlay_title = __( 'Configure column', 'make' );

get_template_part( '/inc/builder/core/templates/overlay', 'header' );

$inputs = apply_filters( 'make_column_configuration', array(
	100 => array(
		'type'    => 'text',
		'name'    => 'column-classes',
		'label'   => 'Column CSS Classes',
		'default' => '',
	),
) );

// Sort the config in case 3rd party code added another input
ksort( $inputs, SORT_NUMERIC );

// Print the inputs
$output = '';

foreach ( $inputs as $input ) {
	if ( isset( $input['type'] ) && isset( $input['name'] ) ) {
		$section_data  = ( isset( $ttfmake_section_data['data'] ) ) ? $ttfmake_section_data['data'] : array();
		$output       .= ttfmake_create_input( $section_name, $input, $section_data );
	}
}

echo $output;

get_template_part( '/inc/builder/core/templates/overlay', 'footer' );
?>
<input type="hidden" class="ttfmake-section-state" name="<?php echo $section_name; ?>[state]" value="<?php if ( isset( $ttfmake_section_data['data']['state'] ) ) echo esc_attr( $ttfmake_section_data['data']['state'] ); else echo 'open'; ?>" />
</div>
<?php ttfmake_load_section_footer(); ?>