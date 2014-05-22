<?php

ttfmake_load_section_header();

global $ttfmake_section_data, $ttfmake_is_js_template;

$section_name   = ttfmake_get_section_name( $ttfmake_section_data, $ttfmake_is_js_template );
$section_order  = ( ! empty( $ttfmake_section_data['data']['columns-order'] ) ) ? $ttfmake_section_data['data']['columns-order'] : range(1, 2);

?>
	<div class="wsuwp-spine-halves-stage">
		<?php $j = 1; foreach ( $section_order as $key => $i ) : ?>
			<?php
			$column_name = $section_name . '[columns][' . $i . ']';
			$image_id = ( isset( $ttfmake_section_data['data']['columns'][ $i ]['image-id'] ) ) ? $ttfmake_section_data['data']['columns'][ $i ]['image-id'] : 0;
			$title    = ( isset( $ttfmake_section_data['data']['columns'][ $i ]['title'] ) ) ? $ttfmake_section_data['data']['columns'][ $i ]['title'] : '';
			$content  = ( isset( $ttfmake_section_data['data']['columns'][ $i ]['content'] ) ) ? $ttfmake_section_data['data']['columns'][ $i ]['content'] : '';
			?>
			<div class="wsuwp-spine-halves-column wsuwp-spine-halves-column-position-<?php echo $j; ?>" data-id="<?php echo $i; ?>">
				<div title="<?php esc_attr_e( 'Drag-and-drop this column into place', 'ttfmake' ); ?>" class="ttfmake-sortable-handle">
					<div class="sortable-background"></div>
				</div>

				<div class="ttfmake-titlediv">
					<div class="ttfmake-titlewrap">
						<input placeholder="<?php esc_attr_e( 'Enter title here', 'ttfmake' ); ?>" type="text" name="<?php echo $column_name; ?>[title]" class="ttfmake-title ttfmake-section-header-title-input" value="<?php echo esc_attr( htmlspecialchars( $title ) ); ?>" autocomplete="off" />
					</div>
				</div>

				<?php
				$editor_settings = array(
					'tinymce'       => array(
						'toolbar1' => 'bold,italic,link',
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
					<?php ttfmake_get_builder_base()->wp_editor( $content, 'ttfmakeeditortext' . $ttfmake_section_data['data']['id'] . $i, $editor_settings ); ?>
				<?php endif; ?>
			</div>
			<?php $j++; endforeach; ?>
	</div>

	<div class="clear"></div>

	<input type="hidden" value="<?php echo esc_attr( implode( ',', $section_order ) ); ?>" name="<?php echo $section_name; ?>[columns-order]" class="wsuwp-spine-halves-columns-order" />
	<input type="hidden" class="ttfmake-section-state" name="<?php echo $section_name; ?>[state]" value="<?php if ( isset( $ttfmake_section_data['data']['state'] ) ) echo esc_attr( $ttfmake_section_data['data']['state'] ); else echo 'open'; ?>" />
<?php ttfmake_load_section_footer(); ?>