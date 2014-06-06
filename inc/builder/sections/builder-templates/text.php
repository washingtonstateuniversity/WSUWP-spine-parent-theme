<?php
/**
 * @package Make
 */

ttfmake_load_section_header();

global $ttfmake_section_data, $ttfmake_is_js_template;
$section_name   = ttfmake_get_section_name( $ttfmake_section_data, $ttfmake_is_js_template );
$columns_number = ( isset( $ttfmake_section_data['data']['columns-number'] ) ) ? $ttfmake_section_data['data']['columns-number'] : 3;
$section_order  = ( ! empty( $ttfmake_section_data['data']['columns-order'] ) ) ? $ttfmake_section_data['data']['columns-order'] : range(1, 4);
$columns_class  = ( in_array( $columns_number, range( 1, 4 ) ) && true !== $ttfmake_is_js_template ) ? $columns_number : 3;
?>

<?php if ( false === ttfmake_is_plus() ) : ?>
<div class="ttfmake-plus-info">
	<p>
		<em>
		<?php
		printf(
			__( '%s and convert any column into an area for widgets.', 'make' ),
			sprintf(
				'<a href="%1$s" target="_blank">%2$s</a>',
				esc_url( ttfmake_get_plus_link( 'widget-area' ) ),
				sprintf(
					__( 'Upgrade to %s', 'make' ),
					'Make Plus'
				)
			)
		);
		?>
		</em>
	</p>
</div>
<?php endif; ?>

<div class="ttfmake-columns-select ttfmake-select">
	<label for="<?php echo $section_name; ?>[columns-number]"><?php _e( 'Columns:', 'make' ); ?></label>
	<select id="<?php echo $section_name; ?>[columns-number]" class="ttfmake-text-columns" name="<?php echo $section_name; ?>[columns-number]">
		<option value="1"<?php selected( 1, $columns_number ); ?>>1</option>
		<option value="2"<?php selected( 2, $columns_number ); ?>>2</option>
		<option value="3"<?php selected( 3, $columns_number ); ?>>3</option>
		<option value="4"<?php selected( 4, $columns_number ); ?>>4</option>
	</select>
</div>

<div class="ttfmake-titlediv">
	<div class="ttfmake-titlewrap">
		<input placeholder="<?php esc_attr_e( 'Enter title here' ); ?>" type="text" name="<?php echo $section_name; ?>[title]" class="ttfmake-title ttfmake-section-header-title-input" value="<?php if ( isset( $ttfmake_section_data['data']['title'] ) ) echo esc_attr( htmlspecialchars( $ttfmake_section_data['data']['title'] ) ); ?>" autocomplete="off" />
	</div>
</div>

<div class="ttfmake-text-columns-stage ttfmake-text-columns-<?php echo $columns_class; ?>">
	<?php $j = 1; foreach ( $section_order as $key => $i ) : ?>
	<?php
		$column_name = $section_name . '[columns][' . $i . ']';
		$link     = ( isset( $ttfmake_section_data['data']['columns'][ $i ]['image-link'] ) ) ? $ttfmake_section_data['data']['columns'][ $i ]['image-link'] : '';
		$image_id = ( isset( $ttfmake_section_data['data']['columns'][ $i ]['image-id'] ) ) ? $ttfmake_section_data['data']['columns'][ $i ]['image-id'] : 0;
		$title    = ( isset( $ttfmake_section_data['data']['columns'][ $i ]['title'] ) ) ? $ttfmake_section_data['data']['columns'][ $i ]['title'] : '';
		$content  = ( isset( $ttfmake_section_data['data']['columns'][ $i ]['content'] ) ) ? $ttfmake_section_data['data']['columns'][ $i ]['content'] : '';
	?>
	<div class="ttfmake-text-column ttfmake-text-column-position-<?php echo $j; ?>" data-id="<?php echo $i; ?>">
		<div title="<?php esc_attr_e( 'Drag-and-drop this column into place', 'make' ); ?>" class="ttfmake-sortable-handle">
			<div class="sortable-background"></div>
		</div>

		<?php do_action( 'ttfmake_section_text_before_column', $ttfmake_section_data, $i ); ?>

		<div class="ttfmake-titlediv">
			<input placeholder="<?php esc_attr_e( 'Enter link here', 'make' ); ?>" type="text" name="<?php echo $column_name; ?>[image-link]" class="ttfmake-link code widefat" value="<?php echo esc_url( $link ); ?>" autocomplete="off" />
		</div>

		<?php ttfmake_get_builder_base()->add_uploader( $column_name, ttfmake_sanitize_image_id( $image_id ) ); ?>

		<div class="ttfmake-titlediv">
			<div class="ttfmake-titlewrap">
				<input placeholder="<?php esc_attr_e( 'Enter title here', 'make' ); ?>" type="text" name="<?php echo $column_name; ?>[title]" class="ttfmake-title ttfmake-section-header-title-input" value="<?php echo esc_attr( htmlspecialchars( $title ) ); ?>" autocomplete="off" />
			</div>
		</div>

		<?php
		$editor_settings = array(
			'tinymce'       => array(
				'toolbar1' => 'bold,italic,link,ttfmake_mce_button_button',
				'toolbar2' => '',
				'toolbar3' => '',
				'toolbar4' => '',
			),
			'quicktags'     => array(
				'buttons' => 'strong,em,link',
			),
			'textarea_name' => $column_name . '[content]'
		);

		if ( true === $ttfmake_is_js_template ) : ?>
			<?php ttfmake_get_builder_base()->wp_editor( '', 'ttfmakeeditortextcolumn' . $i . 'temp', $editor_settings ); ?>
		<?php else : ?>
			<?php ttfmake_get_builder_base()->wp_editor( $content, 'ttfmakeeditortextcolumn' . $i . $ttfmake_section_data['data']['id'], $editor_settings ); ?>
		<?php endif; ?>

		<?php do_action( 'ttfmake_section_text_after_column', $ttfmake_section_data, $i ); ?>
	</div>
	<?php $j++; endforeach; ?>
</div>

<div class="clear"></div>

<input type="hidden" value="<?php echo esc_attr( implode( ',', $section_order ) ); ?>" name="<?php echo $section_name; ?>[columns-order]" class="ttfmake-text-columns-order" />
<input type="hidden" class="ttfmake-section-state" name="<?php echo $section_name; ?>[state]" value="<?php if ( isset( $ttfmake_section_data['data']['state'] ) ) echo esc_attr( $ttfmake_section_data['data']['state'] ); else echo 'open'; ?>" />
<?php ttfmake_load_section_footer();