<?php if ( true == spine_get_option( 'crop' ) && is_front_page() ) {
		$cropping = ' cropped';
	} else {
		$cropping = '';
	}
?>

<div id="spine" class="spine-column <?php echo esc_attr( spine_get_option( 'spine_color' ) ); echo $cropping; echo esc_attr( spine_get_option( 'bleed' ) ); ?> shelved">
<div id="glue" class="spine-glue">

<?php get_template_part('spine/header'); ?>

<section id="spine-navigation" class="spine-navigation">

	<nav id="spine-sitenav" class="spine-sitenav">
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
		'depth'           => 5,
		'walker'          => ''
		);
	wp_nav_menu( $site );
	?>
	</nav>
	
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
	
</section>
		
<?php get_template_part('spine/footer'); ?>

</div><!--/glue-->
</div><!--/spine-->