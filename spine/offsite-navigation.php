<nav id="spine-offsitenav" class="spine-offsitenav">
	<?php
	$offsite = array(
		'theme_location'  => 'offsite',
		'menu'            => 'offsite',
		'container'       => false,
		'container_class' => false,
		'container_id'    => false,
		'menu_class'      => null,
		'menu_id'         => null,
		'echo'            => true,
		'fallback_cb'     => false,
		'items_wrap'      => '<ul>%3$s</ul>',
		'depth'           => 3,
		'walker'          => ''
	);
	wp_nav_menu( $offsite );
	?>
</nav>