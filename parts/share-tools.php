<?php
$post_share_url = esc_url( get_permalink() );
$post_share_title = rawurlencode( trim( wp_title( '', false ) ) );
$spine_social_options = spine_social_options();
if ( ! empty( $spine_social_options['twitter'] ) ) {
	$twitter_array = explode( '/', $spine_social_options['twitter'] );
	$twitter_handle = esc_attr( array_pop( $twitter_array ) );
} else {
	$twitter_handle = 'wsupullman';
}
?>
<div class="social-share-bar">
	<ul>
		<li class="by-facebook">
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_share_url; ?>" target="_blank"><span class="channel-title">Share on Facebook</span></a>
		</li>
		<li class="by-twitter">
			<a href="https://twitter.com/intent/tweet?text=<?php echo $post_share_title; ?>&amp;url=<?php echo $post_share_url; ?>&amp;via=<?php echo $twitter_handle; ?>" target="_blank"><span class="channel-title">Share on Twitter</span></a>
		</li>
		<li class="by-googleplus">
			<a href="https://plus.google.com/share?url=<?php echo $post_share_url; ?>" target="_blank"><span class="channel-title">Share on Google+</span></a>
		</li>
		<li class="by-linkedin">
			<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $post_share_url; ?>&amp;summary=<?php echo $post_share_title; ?>&amp;source=undefined" target="_blank"><span class="channel-title">Share on Linkedin</span></a>
		</li>
		<li class="by-email">
			<a href="mailto:?subject=<?php echo $post_share_title; ?>&amp;body=<?php echo $post_share_url; ?>"><span class="channel-title">Email this post</span></a>
		</li>
	</ul>
</div>
