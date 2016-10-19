<?php
get_header();

?>
<main id="wsuwp-main">

	<?php get_template_part( 'parts/headers' ); ?>

	<section class="row single gutter pad-ends">

		<div class="column one" id="tribe-events-pg-template">
			<?php tribe_events_before_html(); ?>
			<?php tribe_get_view(); ?>
			<?php tribe_events_after_html(); ?>
		</div>
	</section>

	<?php get_template_part( 'parts/footers' ); ?>

</main>
<?php

get_footer();
