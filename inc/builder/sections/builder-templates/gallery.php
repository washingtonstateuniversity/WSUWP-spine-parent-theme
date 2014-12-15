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

<div class="ttfmake-gallery-items">
	<div class="ttfmake-gallery-items-stage ttfmake-gallery-columns-<?php echo absint( $columns ); ?>">
		<?php foreach ( $section_order as $key => $section_id  ) : ?>
			<?php if ( isset( $ttfmake_section_data['data']['gallery-items'][ $section_id ] ) ) : ?>
				<?php global $ttfmake_gallery_id; $ttfmake_gallery_id = $section_id; ?>
				<?php get_template_part( '/inc/builder/sections/builder-templates/gallery', 'item' ); ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<a href="#" class="ttfmake-add-item ttfmake-gallery-add-item-link" title="<?php esc_attr_e( 'Add new item', 'make' ); ?>">
		<div class="ttfmake-gallery-add-item">
			<span>
				<?php _e( 'Add Item', 'make' ); ?>
			</span>
		</div>
	</a>

	<input type="hidden" value="<?php echo esc_attr( implode( ',', $section_order ) ); ?>" name="<?php echo $section_name; ?>[gallery-item-order]" class="ttfmake-gallery-item-order" />
</div>

<input type="hidden" class="ttfmake-section-state" name="<?php echo $section_name; ?>[state]" value="<?php if ( isset( $ttfmake_section_data['data']['state'] ) ) echo esc_attr( $ttfmake_section_data['data']['state'] ); else echo 'open'; ?>" />
<?php ttfmake_load_section_footer();