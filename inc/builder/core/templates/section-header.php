<?php
/**
 * @package Make
 */

global $ttfmake_section_data, $ttfmake_is_js_template;

$links = array(
	100 => array(
	'href'  => '#',
	'class' => 'ttfmake-section-remove',
	'label' => __( 'Delete section', 'make' ),
	'title' => __( 'Delete section', 'make' ),
) );

if ( ! empty( $ttfmake_section_data['section']['config'] ) ) {
	$id = ( true === $ttfmake_is_js_template ) ? '{{{ id }}}' : esc_attr( $ttfmake_section_data['data']['id'] );
	$links[25] = array(
		'href'  => '#',
		'class' => 'ttfmake-section-configure ttfmake-overlay-open',
		'label' => __( 'Configure section', 'make' ),
		'title' => __( 'Configure section', 'make' ),
		'other' => 'data-overlay="#ttfmake-overlay-' . $id . '"'
	);
}

/**
 * Deprecated: Filter the definitions for the links that appear in each Builder section's footer.
 *
 * This filter is deprecated. Use make_builder_section_links instead.
 *
 * @since 1.0.7.
 *
 * @param array    $links    The link definition array.
 */
$links = apply_filters( 'ttfmake_builder_section_footer_links', $links );
/**
 * Filter the definitions for the buttons that appear in each Builder section's header.
 *
 * @since 1.4.0.
 *
 * @param array    $links    The button definition array.
 */
$links = apply_filters( 'make_builder_section_links', $links );
ksort( $links );
?>

<?php if ( ! isset( $ttfmake_is_js_template ) || true !== $ttfmake_is_js_template ) : ?>
<div class="ttfmake-section <?php if ( isset( $ttfmake_section_data['data']['state'] ) && 'open' === $ttfmake_section_data['data']['state'] ) echo 'ttfmake-section-open'; ?> ttfmake-section-<?php echo esc_attr( $ttfmake_section_data['section']['id'] ); ?>" id="<?php echo 'ttfmake-section-' . esc_attr( $ttfmake_section_data['data']['id'] ); ?>" data-id="<?php echo esc_attr( $ttfmake_section_data['data']['id'] ); ?>" data-section-type="<?php echo esc_attr( $ttfmake_section_data['section']['id'] ); ?>">
<?php endif; ?>
	<?php
	/**
	 * Execute code before the section header is displayed.
	 *
	 * @since 1.2.3.
	 */
	do_action( 'make_before_section_header' );
	?>
	<div class="ttfmake-section-header">
		<?php $header_title = ( isset( $ttfmake_section_data['data']['label'] ) ) ? $ttfmake_section_data['data']['label'] : ''; ?>
		<h3>
			<span class="ttfmake-section-header-title"><?php echo esc_html( $header_title ); ?></span><em><?php echo ( esc_html( $ttfmake_section_data['section']['label'] ) ); ?></em>
		</h3>
		<div class="ttf-make-section-header-button-wrapper">
			<?php foreach ( $links as $link ) : ?>
				<?php
				$href  = ( isset( $link['href'] ) ) ? ' href="' . esc_url( $link['href'] ) . '"' : '';
				$id    = ( isset( $link['id'] ) ) ? ' id="' . esc_attr( $link['id'] ) . '"' : '';
				$label = ( isset( $link['label'] ) ) ? esc_html( $link['label'] ) : '';
				$title = ( isset( $link['title'] ) ) ? ' title="' . esc_html( $link['title'] ) . '"' : '';
				$other = ( isset( $link['other'] ) ) ? ' ' . $link['other'] : '';

				// Set up the class value with a base class
				$class_base = ' class="ttfmake-builder-section-link';
				$class      = ( isset( $link['class'] ) ) ? $class_base . ' ' . esc_attr( $link['class'] ) . '"' : '"';
				?>
				<a<?php echo $href . $id . $class . $title . $other; ?>>
					<span>
						<?php echo $label; ?>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
		<a href="#" class="ttfmake-section-toggle" title="<?php esc_attr_e( 'Click to toggle', 'make' ); ?>">
			<div class="handlediv"></div>
		</a>
	</div>
	<div class="clear"></div>
	<div class="ttfmake-section-body">
		<input type="hidden" value="<?php echo $ttfmake_section_data['section']['id']; ?>" name="<?php echo ttfmake_get_section_name( $ttfmake_section_data, $ttfmake_is_js_template ); ?>[section-type]" />
