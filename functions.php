<?php




add_action( 'init', 'spine_menus' );
function spine_menus() {
	register_nav_menus(
		array(
		'site' => __( 'Site' ),
		'offsite' => __( 'Offsite' )
		)
	);
}

?>