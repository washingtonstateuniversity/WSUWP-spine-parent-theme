<?php
/**
 * @package Make
 */

global $ttfmake_section_data, $ttfmake_is_js_template;
?>

	<?php if ( ! empty( $ttfmake_section_data['section']['config'] ) ) : ?>
		<?php global $ttfmake_overlay_id; $id = ( true === $ttfmake_is_js_template ) ? '{{{ id }}}' : esc_attr( $ttfmake_section_data['data']['id'] ); $ttfmake_overlay_id = 'ttfmake-overlay-' . $id; ?>
		<?php get_template_part( '/inc/builder/core/templates/overlay', 'configuration' ); ?>
	<?php endif; ?>

	</div>
<?php if ( ! isset( $ttfmake_is_js_template ) || true !== $ttfmake_is_js_template ) : ?>
</div>
<?php endif; ?>