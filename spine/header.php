<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js no-svg lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]><html class="no-js no-svg lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js no-svg lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"><!--<![endif]-->

<head>

	<meta charset="UTF-8">
	<title><?php wp_title( '|', true, 'right' ); ?> | Washington State University</title>
	
	<!-- FAVICON -->
	<link rel="shortcut icon" href="http://images.wsu.edu/favicon.ico" />
	
	<!-- STYLESHEETS -->
	<link href="http://images.wsu.edu/spine/styles.css" rel="stylesheet" type="text/css" />
	<!-- Your theme stylesheet here -->
	<link href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" rel="stylesheet" type="text/css" />
	
	<!-- RESPOND -->
	<meta name="viewport" content="width=device-width, user-scalable=yes">
	
	<!-- SCRIPTS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js" type="text/javascript"></script>
	<script src="http://images.wsu.edu/spine/scripts.js" type="text/javascript"></script>
	<!-- Your supplementary scripts here -->
	
	<!-- DOCS -->
	<link type="text/plain" rel="author" href="http://images.wsu.edu/spine/humans.txt" />
	<link type="text/html" rel="docs" href="http://identity.wsu.edu" />
	
	<!-- ANALYTICS -->
	<!-- Your analytics code here -->
	
</head>

<body <?php body_class($class); ?>>

<?php if ( $_GET["grid"] != "" ) { $grid = $_GET["grid"]; } else {  $grid = "fixed"; } ?>
<?php // $palette = get_post_meta( $post->ID, 'palette', true ); ?>
<?php $palette = get_post_meta( get_queried_object_id(), 'palette', true ); ?>
<?php if ( $_GET["color"] != "" ) { $color = $_GET["color"]; } elseif ( $palette == "" ) {  $color = "white"; } ?>
<?php if ( $_GET["variant"] != "" ) { $variant = $_GET["variant"]; } else {  $variant = "defacto"; } ?>

<div id="jacket" class="<? echo $color."-spine"; echo " ".$palette."-palette"; echo " ".$variant; ?>">
<div id="binder" class="<? echo $grid; ?>">