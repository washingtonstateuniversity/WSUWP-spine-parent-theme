<?php // Settings
	$spine_options = get_option( 'spine_options' );
	
	if ( isset($spine_options['social_spot_one_type']) ) { $social_spot_one_type = $spine_options['social_spot_one_type']; } else { $social_spot_one_type = 'facebook'; }
	if ( isset($spine_options['social_spot_one']) && $spine_options['social_spot_one'] != "" ) { $social_spot_one = $spine_options['social_spot_one']; } else { $social_spot_one = 'http://facebook.com/wsupullman'; }
	
	if ( isset($spine_options['social_spot_two_type']) ) { $social_spot_two_type = $spine_options['social_spot_two_type']; } else { $social_spot_two_type = 'twitter'; }
	if ( isset($spine_options['social_spot_two']) && $spine_options['social_spot_two'] != "" ) { $social_spot_two = $spine_options['social_spot_two']; } else { $social_spot_two = 'http://twitter.com/wsupullman'; }
	
	if ( isset($spine_options['social_spot_three_type']) ) { $social_spot_three_type = $spine_options['social_spot_three_type']; } else { $social_spot_three_type = 'youtube'; }
	if ( isset($spine_options['social_spot_three']) && $spine_options['social_spot_three'] != "") { $social_spot_three = $spine_options['social_spot_three']; } else { $social_spot_three = 'http://youtube.com/washingtonstateuniv'; }
	
	if ( isset($spine_options['social_spot_four_type']) ) { $social_spot_four_type = $spine_options['social_spot_four_type']; } else { $social_spot_four_type = 'directory'; }
	if ( isset($spine_options['social_spot_four']) && $spine_options['social_spot_four'] != "") { $social_spot_four = $spine_options['social_spot_four']; } else { $social_spot_four = 'http://social.wsu.edu'; }
?>

<footer>

<nav id="social">
	<ul>
		<li id="<?php echo $social_spot_one_type; ?>" class="<?php echo $social_spot_one_type; ?>"><a href="<?php echo $social_spot_one; ?>"><?php echo $social_spot_one_type; ?></a></li>
		<li id="<?php echo $social_spot_two_type; ?>" class="<?php echo $social_spot_two_type; ?>"><a href="<?php echo $social_spot_two; ?>"><?php echo $social_spot_two_type; ?></a></li>
		<li id="<?php echo $social_spot_three_type; ?>" class="<?php echo $social_spot_three_type; ?>"><a href="<?php echo $social_spot_three; ?>"><?php echo $social_spot_three_type; ?></a></li>
		<li id="<?php echo $social_spot_four_type; ?>" class="<?php echo $social_spot_four_type; ?>"><a href="<?php echo $social_spot_four; ?>"><?php echo $social_spot_two_type; ?></a></li>
	</ul>
</nav>
<nav id="global">
	<ul>
		<li id="zzusis"><a href="https://zzusis.wsu.edu/">Zzu<strong>sis</strong></a></li>
		<li id="access"><a href="http://access.wsu.edu/">Access</a></li>
		<li id="policies"><a href="http://policies.wsu.edu/">Policies</a></li>
		<li id="copyright"><a href="http://copyright.wsu.edu">&nbsp;&copy;</a></li>
	</ul>
</nav>	
<!-- <small id="copyright"><a href="http://copyright.wsu.edu">&copy; Washington State University</a></small> -->

</footer>