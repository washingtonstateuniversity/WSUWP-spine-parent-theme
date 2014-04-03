<header class="spine-bookmark main-header">
	<div class="header-group hgroup">
		<?php
		
		global $post;
		$site_name      = get_bloginfo('name','display');
		$site_tagline   = get_bloginfo('description','display');
		$page_title = get_the_title();
		$post_title = get_the_title();
		$first_category = get_the_category();
		$section_title  = get_the_category();
		$posts_page_title = get_the_title( get_option('page_for_posts', true) );
		//
		$sup_header_default = '';
		$sub_header_default = '';
		$sup_header_alternate = '';
		$sub_header_alternate = '';
	
		/*if ( spine_is_subpage() ) {
			
			}*/
		
		// Posts
		if ( ( is_category() || is_single() ) && ! empty( $first_category ) && isset( $first_category[0]->cat_name ) ) {
			$category_title = $first_category[0]->cat_name;
			}
		if ( ( is_category() ) && ! empty( $first_category ) && isset( $first_category[0]->cat_name ) ) {
			$sup_header_default = $posts_page_title;
			$sub_header_default = single_cat_title('', false);
			}
		if ( is_archive() && !is_category() ) {
			if ( is_day() ) : $sub_header_default = get_the_date();
				elseif ( is_month() ) : $sub_header_default = get_the_date( 'F Y' );
				elseif ( is_year() )  : $sub_header_default = get_the_date( 'Y' );
				else : $sub_header_default = 'Archives';
				endif;
			$sup_header_default = $posts_page_title;
			}
		if ( is_single() ) {
			$post_title = get_the_title();
			$sup_header_default = $site_name;
			$sub_header_default = $posts_page_title;
			}
		
		// Pages
		if ( is_page() ) {
			$page_title = $page_title;
			$sub_header_default = $page_title;
			}
		if ( is_front_page() && is_page() ) {
			$sup_header_default = $site_name;
			$sub_header_default = $site_tagline;
			$page_title = $posts_page_title;
			}
		if ( is_home() && !is_front_page() ) {
			$sup_header_default = $site_name;
			$sub_header_default = $posts_page_title;
			$page_title = $posts_page_title;
			}
			
		// Search and 404
		if ( is_search() ) {
			$sup_header_default = 'Search';
			$sub_header_default = get_search_query();
			}
		if ( is_404() ) {
			$sup_header_default = '<a href="'.home_url('/').'" rel="home">'.$site_name.'</a>';
			$sub_header_default = 'Page not found';
			}
		
		// Custom Field Overrides
		$sup_override = get_post_meta($post->ID, 'sup-header', TRUE);
		$sub_override = get_post_meta($post->ID, 'sub-header', TRUE);
		if ( $sup_override != '' ) { $sup_header_default = $sup_override; }
		if ( $sub_override != '' ) { $sub_header_default = $sub_override; }
		
		?>
		
		<sup class="sup-header" data-pagetitle="<?php $page_title; ?>" data-posttitle="<?php echo $post_title; ?>" data-default="<?php echo esc_attr($sup_header_default);; ?>" data-alternate="<?php echo esc_attr($sup_header_alternate); ?>"><span class="sup-header-default"><?php echo $sup_header_default; ?></span></sup>
		<sub class="sub-header" data-sitename="<?php echo $site_name; ?>" data-pagetitle="<?php echo $page_title; ?>" data-posttitle="<?php echo $post_title; ?>" data-default="<?php echo esc_attr($sub_header_default); ?>" data-alternate="<?php echo esc_attr($sub_header_alternate); ?>"><span class="sub-header-default"><?php echo $sub_header_default; ?></span></sub>
		
	</div>
</header>