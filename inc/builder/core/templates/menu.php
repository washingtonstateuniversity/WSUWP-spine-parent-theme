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
		</ul>
	</div>
	<div class="ttfmake-menu-tab">
		<a href="#" class="ttfmake-menu-tab-link">
			<span><?php _e( 'Add New Section', 'make' ); ?></span>
		</a>
	</div>
</div>