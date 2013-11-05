<? $color = $_GET["color"] ?>

<div id="spine" class="<? echo $color; ?> shelved">
	<div id="glue" class="clearfix">
		<?php get_template_part('spine/header'); ?>
		<nav id="navigation">
			<nav id="site">
			<?php
			$site = array(
				'theme_location'  => '',
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
			<nav id="offsite">
			<?php 
			$offsite = array(
				'menu'            => 'offsite',
				'container'       => false,
				'container_class' => false,
				'container_id'    => false,
				'menu_class'      => null,
				'menu_id'         => null,
				'echo'            => true,
				'items_wrap'      => '<ul id="%1$s">%3$s</ul>',
				'depth'           => 3,
				'walker'          => ''
			);
			wp_nav_menu( $offsite );
			?>
			</nav>
		</nav>
		<footer>
			<?php get_template_part('spine/footer'); ?>
		</footer>
	</div><!--/glue-->
</div><!--/spine-->
