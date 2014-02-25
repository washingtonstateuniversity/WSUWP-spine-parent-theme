<?php get_template_part( 'spine' ); ?>

</div><!--/cover-->
</div><!--/jacket-->

<?php get_template_part('parts/head','contact'); ?> 

<?php wp_footer(); ?>

<?php // Temporary experiments with toolbar position

global $blog_id;

	if( (is_admin_bar_showing()) && ($blog_id == 2)  ) : ?>

<style>

	html { margin-top: 32px !important; }
	* html body { margin-top: 32px !important; }
	#glue > header { top:32px !important; }
@media screen and ( max-width: 782px ) {
    html { margin-top: 46px !important; }
    * html body { margin-top: 46px !important; }
    #glue > header { top:46px !important; }
	}

</style>

<?php else : ?>

<style>

body.admin-bar {
    margin-top: -32px !important;
    padding-bottom: 32px !important;
}
#wpadminbar {
    top: auto !important;
    bottom: 0 !important;
    position: fixed;
}
#wpadminbar .quicklinks>ul>li {
    position:relative;
}
#wpadminbar .ab-top-menu>.menupop>.ab-sub-wrapper {
    bottom:32px;
    box-shadow: none;
}   
@media (max-width: 989px) {
body.admin-bar {
    margin-top: -32px !important;
    padding-bottom: 32px !important;
}
#wpadminbar {
    top: auto !important;
    bottom: 0 !important;
    position: fixed;
}
#wpadminbar .quicklinks>ul>li {
    position:relative;
}
#wpadminbar .ab-top-menu>.menupop>.ab-sub-wrapper {
    bottom:32px;
    box-shadow: none;
}
}
@media (max-width: 780px) {

body.admin-bar {
    margin-top: -46px !important;
    padding-bottom: 46px !important;
}
#wpadminbar .ab-top-menu>.menupop>.ab-sub-wrapper {
    bottom:46px;
}

}

</style>

<?php endif; ?>

</body>
</html>