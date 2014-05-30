<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js no-svg lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]><html class="no-js no-svg lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js no-svg lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php wp_title( '|', true, 'right' ); ?> Washington State University</title>
	
	<!-- FAVICON -->
	<link rel="shortcut icon" href="//repo.wsu.edu/spine/1/favicon.ico" />
	
	<!-- RESPOND -->
	<meta name="viewport" content="width=device-width, user-scalable=yes">
	
	<!-- DOCS -->
	<link type="text/plain" rel="author" href="//repo.wsu.edu/spine/1/authors.txt" />
	<link type="text/html" rel="help" href="http://brand.wsu.edu/media/web" />
	
	<!-- ANALYTICS -->
	<!-- Your analytics code here -->

	<!-- SCRIPTS and STYLES -->
	<!-- Custom scripts and styles should be added with wp_enqueue_script() and wp_enqueue_style() -->
	<?php wp_head(); ?>

	<!-- COMPATIBILITY -->
	<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<noscript><style>#spine #spine-sitenav ul ul li { display: block !important; }</style></noscript>
</head>

<body <?php body_class(); ?>>

<?php if ( spine_get_option( 'open_sans' ) == 'true' ) {
		$opensans_included = " opensansy";
	} else {
		$opensans_included = "";
	}
?>

<div id="jacket" class="style-<?php echo esc_attr( spine_get_option( 'theme_style' ) ); echo $opensans_included; ?>">
<div id="binder" class="<?php echo esc_attr( spine_get_option( 'grid_style' ) ); echo esc_attr( spine_get_option( 'large_format' ) ); echo esc_attr( spine_get_option( 'broken_binding' ) ); ?>">