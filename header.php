<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js no-svg lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?> ><![endif]-->
<!--[if IE 7]><html class="no-js no-svg lt-ie9 lt-ie8" <?php language_attributes(); ?> ><![endif]-->
<!--[if IE 8]><html class="no-js no-svg lt-ie9" <?php language_attributes(); ?> ><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" <?php language_attributes(); ?> ><!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php wp_title( '|', true, 'right' ); ?> | Washington State University</title>
	
	<!-- FAVICON -->
	<link rel="shortcut icon" href="http://images.wsu.edu/favicon.ico" />
	
	<!-- STYLESHEETS -->
	<!-- TARGET <link href="http://images.wsu.edu/spine/1/styles.css" rel="stylesheet" type="text/css" /> -->
	<!-- TEMP --><link href="http://nbj.me/spine/1/styles.css" rel="stylesheet" type="text/css" /><!--  -->
	<!-- PCKG <link href="styles/styles.css" rel="stylesheet" type="text/css" />-->
	<!-- Your custom stylesheets here -->
	
	<!-- RESPOND -->
	<meta name="viewport" content="width=device-width, user-scalable=yes" />

	<!-- SCRIPTS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
	<!-- TARGET <script src="http://images.wsu.edu/spine/1/scripts.js" type="text/javascript"></script>-->
	<!-- TEMP --><script src="http://nbj.me/spine/1/scripts-dev.js" type="text/javascript"></script><!-- -->
	<!-- PCKG <script src="scripts/scripts.js" type="text/javascript"></script>-->
	<!-- Your supplementary scripts here -->
	<link href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" rel="stylesheet" type="text/css" />
	
	<!-- DOCS -->
	<link type="text/plain" rel="author" href="http://images.wsu.edu/spine/humans.txt" />
	<link type="text/html" rel="docs" href="http://identity.wsu.edu" />
	
	<!-- ANALYTICS -->
	<!-- Your analytics code here -->

	<?php wp_head(); ?>
</head>

<body <?php body_class($class); ?>>

<div id="jacket" class="palette">
<div id="binder" class="hybrid">