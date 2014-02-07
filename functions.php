<?php

function brand_scripts() {
	wp_enqueue_script(
		'brand.js',
		get_stylesheet_directory_uri() . '/scripts/brand.js',
		array( 'jquery' )
	);
}

add_action( 'wp_enqueue_scripts', 'brand_scripts' );

?>