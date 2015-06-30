<footer class="spine-footer">

	<nav id="wsu-social-channels" class="spine-social-channels">
		<ul>
		<?php
			foreach( spine_social_options() as $socialite => $social_url ) {
				echo '<li class="' . esc_attr( $socialite ) . '-channel"><a href="' . esc_url( $social_url ) . '">' . esc_html( $socialite ) . '</a></li>'."\r\n";
			}
		?>
		</ul>
	</nav>

	<nav id="wsu-global-links" class="spine-global-links">
		<ul>
			<li class="zzusis-link"><a href="http://zzusis.wsu.edu/">Zzusis</a></li>
			<li class="access-link"><a href="http://access.wsu.edu/">Access</a></li>
			<li class="policies-link"><a href="http://policies.wsu.edu/">Policies</a></li>
			<li class="copyright-link"><a href="http://copyright.wsu.edu">&copy;</a></li>
		</ul>
	</nav>

</footer>