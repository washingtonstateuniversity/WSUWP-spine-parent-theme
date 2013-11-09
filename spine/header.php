<header>
	<a id="logo" class="title" href="http://www.wsu.edu/">Washington State University</a>
	<button id="shelve"></button>
</header>
<section id="wsu-actions" class="clearfix" role="actions">
	<ul id="wsu-tabs-actions" class="wsuicons clearfix">
		<li id="wsu-tab-search" class="closed" label="Search"><button><span>Search</span></button></li>
		<li id="wsu-tab-contact" class="closed" label="Contact"><button><span>Contact</span></button></li>
		<li id="wsu-tab-share" class="closed" label="Share"><button><span>Share</span></button></li>
		<li id="wsu-tab-print" class="closed" label="Print">
			<span class="print-button"><button><span>Print</span></button></span>
			<span class="print-controls">
				<button id="print-invoke"><span>Print</span></button>
				<button id="print-cancel"><span>Cancel</span></button>
			</span>
		</li>
	</ul>
	<section id="wsu-search" class="tools closed">
		<form name="search" id="search">
            <input id="searchterm" name="term" type="text" value="" placeholder="search">
            <button id="submit-search"><span>Submit</span></button>
         </form>
		<div id="results">
			<ul class="tabs cf">
				<li id="tab-site-search" class="active"><a href="#"><button>Site</button></li>
				<li id="tab-wsu-search" class="inactive"><button>WSU</button></li>
				<li id="tab-staff-search" class="inactive"><button>Faculty/Staff</button></li>
				<li id="tab-student-search" class="inactive"><button>Students</button></li>
			</ul>
			<div id="resulting">
				<button id="shut-results" class="shut"><span>Shut</span></button>
				<?php include 'search.php'; ?>
			</div>
		</div>
         <menu>
         	<?php include 'azlist.php'; ?>
         </menu>
	</section>
	<section id="wsu-contact" class="tools closed">
		<button id="shut-contact" class="shut"><span>Close</span></button>
		<!-- This following  address uses microformat classes to enable automated contact recognition -->
		<address class="hcard">
			<div class="organization-unit fn org"><a href="http://example.wsu.edu/" class="url">College of Engineering & Architecture</a></div>
			<div class="organization-name">Washington State University</div>
			<div class="adr">
				<div class="street-address">PO Box 642714</div>
				<div class="area">
					<span class="locality">Pullman</span>, 
					<abbr class="region">WA</abbr>
					<span class="postal-code">99164</span>
				</div>
			</div>
			<div class="tel"><i class="wsu-icon"></i>888-468-6978</div>
			<div class="email" rel="email"><a href="mailto:contact@wsu.edu"><i class="wsu-icon"></i>Email us</a></div>
			<div class="more"><a href="http://about.wsu.edu/contact/"><i class="wsu-icon"></i>More Contacts</a></div>
		</address>
	</section>
	<section id="wsu-share" class="tools closed">
		<button id="shut-share" class="shut"><span>Close</span></button>
		<ul><!-- addthisdotcom -->
			<li class="by-facebook"><a href="#" class="addthis_button_facebook"><i class="wsu-icon"></i>Facebook</a></li>
			<li class="by-twitter"><a href="#" class="addthis_button_twitter"><i class="wsu-icon"></i>Twitter</a></li>
			<li class="by-email"><a href="#" class="addthis_button_email"><i class="wsu-icon"></i>Email</a></li>
			<li class="by-other"><a href="#" class="addthis_button_compact">More Options ...</a></li>
			<style>.addthis_button_compact > .at16nc { background: none !important; height: 0px; overflow: hidden; }</style>
		</ul>
		<script type="text/javascript">var addthis_config = { data_ga_property: 'UA-17815664-1', data_ga_social: true }; var addthis_config = { ui_cobrand: "WSU" }</script>
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
	</section>
</section><!--/#wsu-actions-->