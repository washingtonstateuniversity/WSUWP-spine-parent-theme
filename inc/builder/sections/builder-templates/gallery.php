<?php
/**
 * @package Make
 */

ttfmake_load_section_header();

global $ttfmake_section_data, $ttfmake_is_js_template;
$section_name     = ttfmake_get_section_name( $ttfmake_section_data, $ttfmake_is_js_template );
$columns          = ( isset( $ttfmake_section_data['data']['columns'] ) ) ? $ttfmake_section_data['data']['columns'] : 3;
$caption_color    = ( isset( $ttfmake_section_data['data']['caption-color'] ) ) ? $ttfmake_section_data['data']['caption-color'] : 'light';
$captions         = ( isset( $ttfmake_section_data['data']['captions'] ) ) ? $ttfmake_section_data['data']['captions'] : 'reveal';
$aspect           = ( isset( $ttfmake_section_data['data']['aspect'] ) ) ? $ttfmake_section_data['data']['aspect'] : 'square';
$title            = ( isset( $ttfmake_section_data['data']['title'] ) ) ? $ttfmake_section_data['data']['title'] : '';
$background_image = ( isset( $ttfmake_section_data['data']['background-image'] ) ) ? $ttfmake_section_data['data']['background-image'] : 0;
$background_color = ( isset( $ttfmake_section_data['data']['background-color'] ) ) ? $ttfmake_section_data['data']['background-color'] : '';
$background_style = ( isset( $ttfmake_section_data['data']['background-style'] ) ) ? $ttfmake_section_data['data']['background-style'] : 'tile';
$darken           = ( isset( $ttfmake_section_data['data']['darken'] ) ) ? $ttfmake_section_data['data']['darken'] : 0;
$section_order    = ( ! empty( $ttfmake_section_data['data']['gallery-item-order'] ) ) ? $ttfmake_section_data['data']['gallery-item-order'] : array();
?>

<div class="ttfmake-captions-select-wrapper">
	<label for="<?php echo $section_name; ?>[caption-color]"><?php _e( 'Caption color:', 'make' ); ?></label>
	<select id="<?php echo $section_name; ?>[caption-color]" name="<?php echo $section_name; ?>[caption-color]">
		<option value="light"<?php selected( 'light', $caption_color ); ?>><?php echo esc_html( __( 'Light', 'make' ) ); ?></option>
		<option value="dark"<?php selected( 'dark', $caption_color ); ?>><?php echo esc_html( __( 'Dark', 'make' ) ); ?></option>
	</select>
</div>

<div class="ttfmake-captions-select-wrapper">
	<label for="<?php echo $section_name; ?>[captions]"><?php _e( 'Caption style:', 'make' ); ?></label>
	<select id="<?php echo $section_name; ?>[captions]" name="<?php echo $section_name; ?>[captions]">
		<option value="overlay"<?php selected( 'overlay', $captions ); ?>><?php echo esc_html( __( 'Overlay', 'make' ) ); ?></option>
		<option value="reveal"<?php selected( 'reveal', $captions ); ?>><?php echo esc_html( __( 'Reveal', 'make' ) ); ?></option>
		<option value="none"<?php selected( 'none', $captions ); ?>><?php echo esc_html( __( 'None', 'make' ) ); ?></option>
	</select>
</div>

<div class="ttfmake-aspect-select-wrapper">
	<label for="<?php echo $section_name; ?>[aspect]"><?php _e( 'Aspect ratio:', 'make' ); ?></label>
	<select id="<?php echo $section_name; ?>[aspect]" name="<?php echo $section_name; ?>[aspect]">
		<option value="landscape"<?php selected( 'landscape', $aspect ); ?>><?php echo esc_html( __( 'Landscape', 'make' ) ); ?></option>
		<option value="portrait"<?php selected( 'portrait', $aspect ); ?>><?php echo esc_html( __( 'Portrait', 'make' ) ); ?></option>
		<option value="square"<?php selected( 'square', $aspect ); ?>><?php echo esc_html( __( 'Square', 'make' ) ); ?></option>
		<option value="none"<?php selected( 'none', $aspect ); ?>><?php echo esc_html( __( 'None', 'make' ) ); ?></option>
	</select>
</div>

<div class="ttfmake-columns-select-wrapper">
	<label for="<?php echo $section_name; ?>[columns]"><?php _e( 'Columns:', 'make' ); ?></label>
	<select id="<?php echo $section_name; ?>[columns]" name="<?php echo $section_name; ?>[columns]" class="ttfmake-gallery-columns">
		<option value="1"<?php selected( 1, $columns ); ?>>1</option>
		<option value="2"<?php selected( 2, $columns ); ?>>2</option>
		<option value="3"<?php selected( 3, $columns ); ?>>3</option>
		<option value="4"<?php selected( 4, $columns ); ?>>4</option>
	</select>
</div>

<div class="ttfmake-add-gallery-item-wrapper">
	<a href="#" class="button button-primary ttfmake-button-large button-large ttfmake-gallery-add-item"><?php _e( 'Add New Item', 'make' ); ?></a>
</div>

<div class="ttfmake-gallery-items">
	<div class="ttfmake-gallery-items-stage ttfmake-gallery-columns-<?php echo absint( $columns ); ?>">
		<?php foreach ( $section_order as $key => $section_id  ) : ?>
			<?php if ( isset( $ttfmake_section_data['data']['gallery-items'][ $section_id ] ) ) : ?>
				<?php global $ttfmake_gallery_id; $ttfmake_gallery_id = $section_id; ?>
				<?php get_template_part( '/inc/builder/sections/builder-templates/gallery', 'item' ); ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<input type="hidden" value="<?php echo esc_attr( implode( ',', $section_order ) ); ?>" name="<?php echo $section_name; ?>[gallery-item-order]" class="ttfmake-gallery-item-order" />
</div>

<div class="ttfmake-gallery-background-options-container">
	<h2 class="ttfmake-large-title ttfmake-gallery-options-heading">
		<?php _e( 'Options', 'make' ); ?>
	</h2>

	<div class="ttfmake-titlediv">
		<div class="ttfmake-titlewrap">
			<input placeholder="<?php esc_attr_e( 'Enter title here', 'make' ); ?>" type="text" name="<?php echo $section_name; ?>[title]" class="ttfmake-title ttfmake-section-header-title-input" value="<?php echo esc_attr( htmlspecialchars( $title ) ); ?>" autocomplete="off" />
		</div>
	</div>

	<div class="ttfmake-gallery-background-image-wrapper">
		<?php
			ttfmake_get_builder_base()->add_uploader(
				$section_name . '[background-image]',
				ttfmake_sanitize_image_id( $background_image ),
				array(
					'add'    => __( 'Set background image', 'make' ),
					'remove' => __( 'Remove background image', 'make' ),
					'title'  => __( 'Background image', 'make' ),
					'button' => __( 'Use as Background Image', 'make' ),
				)
			);
		?>
	</div>

	<div class="ttfmake-gallery-background-options-wrapper">
		<h4><?php _e( 'Background image', 'make' ); ?></h4>
		<input id="<?php echo $section_name; ?>[darken]" type="checkbox" name="<?php echo $section_name; ?>[darken]" value="1"<?php checked( $darken ); ?> />
		<label for="<?php echo $section_name; ?>[darken]">
			<?php _e( 'Darken to improve readability', 'make' ); ?>
		</label>

		<h4><?php _e( 'Background color', 'make' ); ?></h4>
		<input id="<?php echo $section_name; ?>[background-color]" type="text" name="<?php echo $section_name . '[background-color]'; ?>" class="ttfmake-gallery-background-color" value="<?php echo maybe_hash_hex_color( $background_color ); ?>" />

		<h4><?php _e( 'Background style:', 'make' ); ?></h4>
		<select id="<?php echo $section_name; ?>[background-style]" name="<?php echo $section_name; ?>[background-style]">
			<option value="tile"<?php selected( 'tile', $background_style ); ?>><?php echo esc_html( __( 'Tile', 'make' ) ); ?></option>
			<option value="cover"<?php selected( 'cover', $background_style ); ?>><?php echo esc_html( __( 'Cover', 'make' ) ); ?></option>
		</select>
	</div>
</div>

<input type="hidden" class="ttfmake-section-state" name="<?php echo $section_name; ?>[state]" value="<?php if ( isset( $ttfmake_section_data['data']['state'] ) ) echo esc_attr( $ttfmake_section_data['data']['state'] ); else echo 'open'; ?>" />
<?php ttfmake_load_section_footer();