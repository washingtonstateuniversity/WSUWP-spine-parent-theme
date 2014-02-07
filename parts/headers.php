<?php

	global $post;
	
	$site_name = get_bloginfo('name');
	$site_tagline = get_bloginfo('description');
	$first_category = get_the_category();
	$section_title = get_the_category();
	
	echo '<header>';
	echo '<hgroup>';
	echo '	<div class="site"><a href="'.home_url().'" title="'.$site_name.'" rel="home">'.$site_name.'</a></div>';
	echo '	<div class="tagline"><a href="'.home_url().'" title="'.$site_tagline.'" rel="home">'.$site_tagline.'</a></div>';
	if (is_subpage()) { echo '	<div class="section">'.section_title().'</div>'; }
	if (is_category() || is_single() || is_archive() ) { echo '	<div class="category">'.$first_category[0]->cat_name.'</div>'; }
	if (is_page()) { echo '	<div class="page">'.get_the_title().'</div>'; }
	if (is_single()) { echo '	<div class="post">'.get_the_title().'</div>'; }
	echo '</hgroup>';
	echo '</header>';
	
	
	
?>