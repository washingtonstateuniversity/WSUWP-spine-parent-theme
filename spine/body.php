<?php // Settings
	if ( !isset($cropping) ) { $cropping = ''; } else { $cropping = ' '.$cropping; }

?>
	
<div id="spine" class="<?php echo esc_attr( spine_get_option( 'spine_color' ) ); echo esc_attr( spine_get_option( 'bleed' ) ); ?> shelved">
<div id="glue" class="clearfix">

<?php get_template_part('spine/header'); ?>

<nav id="spine-navigation">
	<nav id="spine-sitenav">
	<?php
	$site = array(
		'theme_location'  => 'site',
		'menu'            => 'site',
		'container'       => false,
		'container_class' => false,
		'container_id'    => false,
		'menu_class'      => null,
		'menu_id'         => null,
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'items_wrap'      => '<ul>%3$s</ul>',
		'depth'           => 3,
		'walker'          => ''
		);
	wp_nav_menu( $site );
	?>
	</nav>
	<nav id="spine-offsitenav">
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
		'items_wrap'      => '<ul id="%1$s">%3$s</ul>',
		'depth'           => 3,
		'walker'          => ''
	);
	wp_nav_menu( $offsite );
	?>
	</nav>
</nav>
		
<?php get_template_part('spine/footer'); ?>

</div><!--/glue-->
</div><!--/spine-->