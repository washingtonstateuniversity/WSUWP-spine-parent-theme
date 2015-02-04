<?php
/**
 * @package Make
 */

$class = ( 'c' === get_user_setting( 'ttfmakemt' . get_the_ID() ) ) ? 'closed' : 'opened';
?>

<div class="ttfmake-menu ttfmake-menu-<?php echo esc_attr( $class ); ?>" id="ttfmake-menu">
	<div class="ttfmake-menu-pane">
		<ul class="ttfmake-menu-list">
			<?php foreach ( ttfmake_get_sections_by_order() as $key => $item ) : ?>
				<li class="ttfmake-menu-list-item">
					<a href="#" title="<?php esc_attr_e( 'Add', 'make' ); ?>" class="ttfmake-menu-list-item-link" id="ttfmake-menu-list-item-link-<?php echo esc_attr( $item['id'] ); ?>" data-section="<?php echo esc_attr( $item['id'] ); ?>">
						<div class="ttfmake-menu-list-item-link-icon-wrapper clear">
							<span class="ttfmake-menu-list-item-link-icon"></span>
						</div>
						<div class="section-type-description">
							<h4>
								<?php echo esc_html( $item['label'] ); ?>
							</h4>
							<p>
								<?php echo esc_html( $item['description'] ); ?>
							</p>
						</div>
					</a>
				</li>
			<?php endforeach; ?>
			<?php if ( ! ttfmake_is_plus() && in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) : ?>
				<li class="ttfmake-menu-list-item make-plus-products">
					<div class="ttfmake-menu-list-item-link-icon-wrapper clear" style="background-image: url('<?php echo addcslashes( esc_url_raw( get_template_directory_uri() . '/inc/builder/sections/css/images/woocommerce.png' ), '"' ); ?>');">
						<span class="ttfmake-menu-list-item-link-icon "></span>
					</div>
					<div class="section-type-description">
						<h4>
							<?php _e( 'Products', 'make' ); ?>
						</h4>
						<p>
							<?php
							printf(
								__( '%s and feature your WooCommerce products in a grid layout.', 'make' ),
								sprintf(
									'<a href="%1$s" target="_blank">%2$s</a>',
									esc_url( ttfmake_get_plus_link( 'woocommerce-section' ) ),
									sprintf(
										__( 'Upgrade to %s', 'make' ),
										'Make Plus'
									)
								)
							);
							?>
						</p>
					</div>
				</li>
			<?php endif; ?>
			<?php if ( ! ttfmake_is_plus() && in_array( 'easy-digital-downloads/easy-digital-downloads.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) : ?>
				<li class="ttfmake-menu-list-item make-plus-products">
					<div class="ttfmake-menu-list-item-link-icon-wrapper clear" style="background-image: url('<?php echo addcslashes( esc_url_raw( get_template_directory_uri() . '/inc/builder/sections/css/images/woocommerce.png' ), '"' ); ?>');">
						<span class="ttfmake-menu-list-item-link-icon "></span>
					</div>
					<div class="section-type-description">
						<h4>
							<?php _e( 'Downloads', 'make' ); ?>
						</h4>
						<p>
							<?php
							printf(
								__( '%s and feature your Easy Digital Downloads products in a grid layout.', 'make' ),
								sprintf(
									'<a href="%1$s" target="_blank">%2$s</a>',
									esc_url( ttfmake_get_plus_link( 'edd-section' ) ),
									sprintf(
										__( 'Upgrade to %s', 'make' ),
										'Make Plus'
									)
								)
							);
							?>
						</p>
					</div>
				</li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="ttfmake-menu-tab">
		<a href="#" class="ttfmake-menu-tab-link">
			<span><?php _e( 'Add New Section', 'make' ); ?></span>
		</a>
	</div>
</div>