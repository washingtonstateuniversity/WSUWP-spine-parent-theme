<?php
/**
 * @package Make
 */

global $ttfmake_section_data, $ttfmake_is_js_template;
?>

<?php if ( ! isset( $ttfmake_is_js_template ) || true !== $ttfmake_is_js_template ) : ?>
<div class="ttfmake-section <?php if ( isset( $ttfmake_section_data['data']['state'] ) && 'open' === $ttfmake_section_data['data']['state'] ) echo 'ttfmake-section-open'; ?> ttfmake-section-<?php echo esc_attr( $ttfmake_section_data['section']['id'] ); ?>" id="<?php echo 'ttfmake-section-' . esc_attr( $ttfmake_section_data['data']['id'] ); ?>" data-id="<?php echo esc_attr( $ttfmake_section_data['data']['id'] ); ?>" data-section-type="<?php echo esc_attr( $ttfmake_section_data['section']['id'] ); ?>">
<?php endif; ?>
	<div class="ttfmake-section-header">
		<?php $header_title = ( isset( $ttfmake_section_data['data']['label'] ) ) ? $ttfmake_section_data['data']['label'] : ''; ?>
		<h3>
			<span class="ttfmake-section-header-title"><?php echo esc_html( $header_title ); ?></span><em><?php echo ( esc_html( $ttfmake_section_data['section']['label'] ) ); ?></em>
		</h3>
		<a href="#" class="ttfmake-section-toggle" title="<?php esc_attr_e( 'Click to toggle', 'make' ); ?>">
			<div class="handlediv"></div>
		</a>
	</div>
	<div class="clear"></div>
	<div class="ttfmake-section-body">
		<input type="hidden" value="<?php echo $ttfmake_section_data['section']['id']; ?>" name="<?php echo ttfmake_get_section_name( $ttfmake_section_data, $ttfmake_is_js_template ); ?>[section-type]" />
