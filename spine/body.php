<?php

$spine_classes = array();
$spine_classes[] = esc_attr( spine_get_option( 'spine_color' ) );
$spine_classes[] = esc_attr( 'search-' . spine_get_option( 'search_state' ) );

if ( true === spine_get_option( 'crop' ) && is_front_page() ) {
	$spine_classes[] = 'cropped';
}

$spine_classes[] = esc_attr( spine_get_option( 'bleed' ) );
$spine_classes = implode( ' ', $spine_classes );
?>

<div id="spine" class="spine-column <?php echo esc_attr( $spine_classes ); ?> shelved">
<div id="glue" class="spine-glue">

<?php get_template_part( 'spine/header' ); ?>

	<section id="spine-navigation" class="spine-navigation">

		<?php get_template_part( 'spine/site-navigation' ); ?>

		<?php get_template_part( 'spine/offsite-navigation' ); ?>

	</section>

<?php get_template_part( 'spine/footer' ); ?>

</div><!--/glue-->
</div><!--/spine-->
