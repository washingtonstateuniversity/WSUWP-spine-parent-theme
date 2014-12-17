<?php global $ttfmake_overlay_id, $ttfmake_overlay_class, $ttfmake_overlay_title; ?>
<div class="ttfmake-overlay <?php if ( ! empty( $ttfmake_overlay_class ) ) echo $ttfmake_overlay_class; ?>"<?php if ( ! empty( $ttfmake_overlay_id ) ) echo ' id="' . $ttfmake_overlay_id . '"'; ?>>
	<div class="ttfmake-overlay-wrapper">
		<div class="ttfmake-overlay-header">
			<div class="ttfmake-overlay-window-head">
				<div class="ttfmake-overlay-title"><?php if ( ! empty( $ttfmake_overlay_title ) ) : echo $ttfmake_overlay_title; else : _e( 'Configuration', 'make' ); endif; ?></div>
				<span class="ttfmake-overlay-close ttfmake-overlay-close-action" aria-hidden="true"><?php _e( 'Done', 'make' ); ?></span>
			</div>
		</div>
		<div class="ttfmake-overlay-body">