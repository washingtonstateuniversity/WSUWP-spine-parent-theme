<?php // Settings

	$spine_options = get_option( 'spine_options' );

	$social = array();
	
	if ( isset($spine_options['social_spot_one_type']) && $spine_options['social_spot_one_type'] != "none" ) {
		$key = $spine_options['social_spot_one_type']; $social[$key] = $spine_options['social_spot_one'];
		}
	// else { $url = "http://facebook.com/wsupullman"; }
	if ( isset($spine_options['social_spot_two_type']) && $spine_options['social_spot_two_type'] != "none" ) {
		 $key = $spine_options['social_spot_two_type']; $social[$key] = $spine_options['social_spot_two'];
		 }
	// else { $url = "http://facebook.com/wsupullman"; }
	if (
		isset($spine_options['social_spot_three_type']) && $spine_options['social_spot_three_type'] != "none" ) {
		$key = $spine_options['social_spot_three_type']; $social[$key] = $spine_options['social_spot_three'];
		}
	// else { $social[0] = "youtube"; $url = "http://youtube.com/washingtsonstateuniv"; }
	if ( isset($spine_options['social_spot_four_type']) && $spine_options['social_spot_four_type'] != "none" ) {
		$key = $spine_options['social_spot_four_type']; $social[$key] = $spine_options['social_spot_four'];
		}
	// else { $social[0] = "facebook"; $url = "http://facebook.com/wsupullman"; }

?>

<footer>

<nav id="social">
	
	<ul>
	<?php 
		// var_dump($social);
		foreach($social as $socialite=>$url) {
		echo '<li id="'.$socialite.'" class="'.$socialite.'"><a href="'.$url.'">'.$socialite.'</a></li>';
	} ?>

	</ul>
</nav>
<nav id="global">
	<ul>
		<li id="zzusis"><a href="https://zzusis.wsu.edu/">Zzu<strong>sis</strong></a></li>
		<li id="access"><a href="http://access.wsu.edu/">Access</a></li>
		<li id="policies"><a href="http://policies.wsu.edu/">Policies</a></li>
		<li id="copyright"><a href="http://copyright.wsu.edu">&copy;</a></li>
	</ul>
</nav>	

</footer>