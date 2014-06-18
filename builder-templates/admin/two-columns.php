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
						'toolbar1' => 'bold,italic,link,underline,strikethrough,blockquote,wp_adv',
						'toolbar2' => 'bullist,numlist,alignleft,aligncenter,alignright,alignjustify,outdent,indent,removeformat,formatselect',
						'toolbar3' => '',
						'toolbar4' => '',
					),
					'quicktags'     => array(
						'buttons' => 'strong,em,link',
					),
					'textarea_name' => $column_name . '[content]',
					'hidetb' => '1',
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

	<div class="wsuwp-builder-meta" style="width:100%; margin-top:10px;">
		<label for="<?php echo $section_name; ?>[section-classes]">Section Classes</label><input type="text" id="<?php echo $section_name; ?>[section-classes]" class="wsuwp-builder-section-classes widefat" name="<?php echo $section_name; ?>[section-classes]" value="<?php if ( isset( $ttfmake_section_data['data']['section-classes'] ) ) echo esc_attr( $ttfmake_section_data['data']['section-classes'] ); ?>" />
	</div>

	<div class="wsuwp-builder-meta" style="width:100%; margin-top:10px;">
		<label for="<?php echo $section_name; ?>[section-wrapper]">Section Wrapper</label><input type="text" id="<?php echo $section_name; ?>[section-wrapper]" class="wsuwp-builder-section-wrapper widefat" name="<?php echo $section_name; ?>[section-wrapper]" value="<?php if ( isset( $ttfmake_section_data['data']['section-wrapper'] ) ) echo esc_attr( $ttfmake_section_data['data']['section-wrapper'] ); ?>" />
	</div>

	<input type="hidden" value="<?php echo esc_attr( implode( ',', $section_order ) ); ?>" name="<?php echo $section_name; ?>[columns-order]" class="wsuwp-spine-halves-columns-order" />
	<input type="hidden" class="ttfmake-section-state" name="<?php echo $section_name; ?>[state]" value="<?php if ( isset( $ttfmake_section_data['data']['state'] ) ) echo esc_attr( $ttfmake_section_data['data']['state'] ); else echo 'open'; ?>" />
<?php ttfmake_load_section_footer(); ?>