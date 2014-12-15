<?php
/**
 * @package Make
 */

global $ttfmake_sections;
?>

<div class="ttfmake-stage<?php if ( empty( $ttfmake_sections ) ) echo ' ttfmake-stage-closed'?>" id="ttfmake-stage">
	<?php
	/**
	 * Execute code before the builder stage is displayed.
	 *
	 * @since 1.2.3.
	 */
	do_action( 'make_before_builder_stage' );