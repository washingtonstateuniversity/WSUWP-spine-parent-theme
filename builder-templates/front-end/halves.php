<?php
global $ttfmake_section_data, $ttfmake_sections;

$data_columns = spine_get_two_column_data( $ttfmake_section_data );
$count = 'one';
?>
<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>" class="row halves">
	<?php
	if ( ! empty( $data_columns ) ) {
		foreach( $data_columns as $column ) {
			?>
			<div class="column <?php echo $count; $count = 'two'; ?>">
				<article>

			<?php if ( '' !== $column['title'] ) : ?>
				<header>
					<h2><?php echo apply_filters( 'the_title', $column['title'] ); ?></h2>
				</header>
			<?php endif; ?>

			<?php if ( '' !== $column['content'] ) : ?>
				<?php ttfmake_get_builder_save()->the_builder_content( $column['content'] ); ?>
			<?php endif; ?>
				</article>
			</div>
			<?php
		}
	}
	?>
</section>
