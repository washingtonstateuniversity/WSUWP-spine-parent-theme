<?php /* Template Name: Wires */ ?>

<?php get_header(); ?>

<style>

.row .column {
	min-height: 198px;
	xbox-shadow: inset -2px -2px 1px rgba(137,137,137,0.1);
	box-shadow: inset 0px 0px 1px rgba(137,137,137,0.6);
	}
	
/* GRID */

/* ------------------- */
#siteID {
	display: none;
	}
#binder {
	position: relative;
	}
x#cropping {
	background-color: #424a4f;
	height: 400px;
	}
#grid {
	display: none;
	}
.grid #grid {
	position: absolute;
	width: 990px;
	left: 0;
	top: 0;
	display: block;
	height: 3000px;
	z-index: 99999;
	background: transparent url('http://nbj.me/wp-content/themes/dev/depot/images/elements/grid.png') repeat-y left top;
	}
.grid12 #grid {
	background: transparent url('http://nbj.me/wp-content/themes/dev/depot/images/elements/grid12.png') repeat-y left top;
	}
section.row::before {
	display: block;
	background: #5e6a71;
	padding: 0px 0px;
	text-indent: 5px;
	opacity: .5;
	position: absolute;
	right: 0px;
	width: 75px;
	z-index: 99;
	visibility: visible;
	height: auto;
	color: white;
	font-size: 10px;
	}
.row.eighths::before { content: "eighths"; }
.row.twelfths::before { content: "twelfths"; }
.row.quarters::before { content: "quarters"; }
.row.single::before { content: "single"; }
.row.halves::before { content: "halves"; }
.row.sidebar::before { content: "sidebar"; }
.row.triptych::before { content: "triptych"; }
.row.thirds::before { content: "thirds"; }
.row.margin::before { content: "margin"; }
	
/* Column Numbering */
.column::after { 
	color: white;
	background-color: #b6bcbf;
	padding: 5px 10px;
	position: absolute;
	right: 0; bottom: 0;
	}
.column.one::after { content:"1"; }
.column.two::after { content:"2"; }
.column.three::after { content:"3"; }
.column.four::after { content:"4"; }
.column.five::after { content:"5"; }
.column.six::after { content:"6"; }
.column.seven::after { content:"7"; }
.column.eight::after { content:"8"; }
.column.nine::after { content:"9"; }
.column.ten::after { content:"10"; }
.column.eleven::after { content:"11"; }
.column.twelve::after { content:"12"; }

/* Column Measurements */
.column::before { 
	color: black;
	padding: 2px 4px;
	position: absolute;
	opacity: .2;
	font-size: 10px;
	top: 0;	left: 0;
	width: 95%;
	margin: 0px auto;
	text-align: center;
	}
.single .column::before { content:"\2190 792 \2192"; }
.sidebar .column.one::before { content:"\2190 528 \2192"; }
.sidebar .column.two::before,
.thirds .column::before { content:"\2190 264 \2192"; }
.margin .column.one::before { content:"\2190 594 \2192"; }
.margin .column.two::before,
.quarters .column::before,
.triptych .column.one::before,
.triptych .column.three::before { content:"\2190 198 \2192"; }
.halves .column::before,
.triptych .column.two::before { content:"\2190 396 \2192"; }

.nested::before {
	display: block;
	background: #5e6a71;
	padding: 0px 0px;
	text-indent: 5px;
	opacity: .2;
	position: absolute;
	text-align: left;
	left: 0px;
	width: 75px;
	top: 60px;
	z-index: 99;
	visibility: visible;
	height: auto;
	color: white;
	font-size: 10px;
	content:"nested" !important;
	}
	
.sidebar .column.two ul {
	padding: 0px; margin: 0px;
	}
.sidebar .column.two li {
	list-style: none;
	padding: 10px 0px;
	margin: 0px;
	}
.sidebar .column.two li:last-of-type {
	border-bottom: none;
	}

</style>

<script>

	$(document).ready(function(){

	// 
	$('li#grid-behavior a').on('click', function() {
		var grid = $(this).attr('data-grid');
		$('#binder').removeClass('fluid fixed hybrid').addClass(grid);
		return false;
	});
	
	// Change Spine color
	$('li#color-samples a').on('click', function() {
		var color = $(this).attr('data-color');
		$('#spine').removeClass('white lightest lighter light gray dark darker darkest crimson transparent');
		$('#spine').addClass(color);
		return false;
	});
	
	// Folio Sizes
	$('li#folio-samples a').on('click', function() {
		var max = $(this).attr('data-max');
		$('#binder').removeClass('max-default max-1188 max-1386 max-1584 max-1782 max-1980');
		$('#binder').addClass('folio').addClass(max);
		return false;
	});
	
	// Change Campus
	$('li#campus-sigs a').on('click', function() {
		var campus = $(this).attr('data-campus');
			campus = campus + '-signature';
		$('#jacket').removeClass().addClass(campus);
		return false;
	});
	
	});

</script>

<span id="grid" onclick="$('#jacket').removeClass('grid').removeClass('grid12');"></span>

<main id="page" role="main" class="skeleton wireframe">



<!--<section class="row single gray-darker">
	<div class="column one"></div>
</section>-->

<section class="row sidebar">

	<div class="column one">

			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>
					<?php the_content(); ?>
				</article>
			<?php endwhile; // end of the loop. ?>
		
	</div><!--/column-->

	<div class="column two">
		
		<aside>
		<header>Features</header>
		<ul>
			<li>1) Minimal and 2) Flexible</li>
			<li>Large, Medium, Small</li>
			<li id="grid-behavior"><strong>Flexible Grid:</strong> <a href="#" data-grid="fluid">Fluid</a>, <a href="#" data-grid="fixed">Fixed</a>, <a href="#" data-grid="hybrid">Hybrid</a>; <a href="#" onclick="$('#jacket').addClass('grid').addClass('grid12');">Twelfths</a>, <a href="#" onclick="$('#jacket').addClass('grid');">Fifteenths</a>; Nested Columns; Gutterless</li>
			<li><strong>Minimal Resizing:</strong> Four layouts, three breaks, only two content sizes</li>
			<li><strong>Tools:</strong> Combined Search and Index (including navigation); Info; Share (addthis); Print using reponsive</li>
			<li><strong>Mobile Friendly Nav:</strong> Couplets, after testing "<a href="http://test.nbj.me/navigation/?option=plus-minus">Plus Minus</a>", "<a href="http://test.nbj.me/navigation/?option=leftie">Leftie</a>", "<a href="http://test.nbj.me/navigation/?option=onetwo">One Two</a>", "<a href="http://test.nbj.me/navigation/?option=right-o">Right-o</a>", and "<a href="http://test.nbj.me/navigation/?option=couplets">Couplets</a>"</li>
			<li><strong>Resolution Independence:</strong> Custom Symbolset; SVG Marks; <code>EM</code> based menu and enlarged for touch</li>
			<li id="color-samples"><strong>Colors:</strong> Preset <a href="#" data-color="white">Default</a>, <a href="#" data-color="lightest">Lightest</a>, <a href="#" data-color="lighter">Lighter</a>, <a href="#" data-color="light">Light</a>, <a href="#" data-color="gray">Gray</a>, <a href="#" data-color="dark">Dark</a>, <a href="#" data-color="darker">Darker</a>, <a href="#" data-color="darkest">Darkest</a>, <a href="#" data-color="crimson">Crimson</a>, <a href="#" data-color="transparent">Transparent</a></li>
			<li id="folio-samples"><strong>Large Formats: </strong>990 Container by <a href="#" data-max="max-default">default</a> with maximum widths of <a href="#" data-max="max-1188">1188</a>, <a href="#" data-max="max-1386">1386</a>, <a href="#" data-max="max-1584">1584</a>, <a href="#" data-max="max-1782">1782</a>, <a href="#" data-max="max-1980">1980</a></li>
			<li><strong>Homepage:</strong> A <a href="http://nbj.me/wp-content/themes/dev/depot/images/demo/croppedspine.png">cropped</a> <a href="http://nbj.me/wp-content/themes/dev/depot/images/demo/croppedspine2.png">Spine</a> <a href="#" onclick="$('#spine').removeClass('bleed').toggleClass('cropped');$('#jacket').prepend('<div id=cropping></div>');">*</a> for an open <a href="http://spine.nbj.me">canvas</a></li>
			<li><strong></strong>A "cracking" Spine, a "<a href="#" onclick="$('#spine').toggleClass('bloodless bleed');">bleeding</a>" spine </li>
			<li id="campus-sigs"><strong>Campuses:</strong> <a href="#" data-campus="extension">Extension</a>, <a href="#" data-campus="globalcampus">Global Campus</a>, <a href="#" data-campus="spokane">Spokane</a>, <a href="#" data-campus="tricities">Tri-Cities</a>, <a href="#" data-campus="vancouver">Vancouver</a></li>
			<!--<li><a href="">Emphasize Search</a></li>-->
		</ul>
		</aside>

	</div><!--/column-->
	
	<div class="column three three-fifteenths folio-only unequaled"></div>
	<div class="column four three-fifteenths folio-only unequaled"></div>
	<div class="column five three-fifteenths folio-only unequaled"></div>
	<div class="column six three-fifteenths folio-only unequaled"></div>
	<div class="column seven six-fifteenths folio-only unequaled"></div>
	<div class="column eight six-fifteenths folio-only unequaled"></div>

</section>

<section class="row thirds">
	<div class="column one"></div>
	<div class="column two"></div>
	<div class="column three"></div>
	<div class="column four six-fifteenths folio-only"></div>
</section>

<section class="row halves">
	<div class="column one"></div>
	<div class="column two"></div>
	<div class="column three three-fifteenths folio-only"></div>
	<div class="column four three-fifteenths folio-only"></div>
</section>

<section class="row quarters">
	<div class="column one"><img src="/wp-content/themes/spine/images/eg/1.png" class="fill-width"></div>
	<div class="column two"><img src="/wp-content/themes/spine/images/eg/2.png" class="fill-width"></div>
	<div class="column three"><img src="/wp-content/themes/spine/images/eg/3.png" class="fill-width"></div>
	<div class="column four"><img src="/wp-content/themes/spine/images/eg/4.png" class="fill-width"></div>
	<div class="column five folio-only"></div>
	<div class="column six folio-only"></div>
</section>

<section class="row margin">
	<div class="column one">
		<article>
		<code>

<textarea style="height: 150px;">
<section class="row quarters">
	<div class="column one"></div>
	<div class="column two"></div>
	<div class="column three"></div>
	<div class="column four"></div>
</section>
</textarea>

		</code>
		</article>
	</div>
	<div class="column two"></div>
</section>

<section class="row halves">
	<div class="column one halves nested">
		<div class="column one"></div>
		<div class="column two"></div>
	</div>
	<div class="column two"></div>
</section>

<section class="row halves">
	<div class="column one"></div>
	<div class="column two thirds nested">
		<div class="column one"></div>
		<div class="column two"></div>
		<div class="column three"></div>
	</div>
</section>

<section class="row quarters">
	<div class="column two thirds nested">
		<div class="column one"></div>
		<div class="column two"></div>
		<div class="column three"></div>
	</div>
	<div class="column two thirds nested">
		<div class="column one"></div>
		<div class="column two"></div>
		<div class="column three"></div>
	</div>
	<div class="column two thirds nested">
		<div class="column one"></div>
		<div class="column two"></div>
		<div class="column three"></div>
	</div>
	<div class="column two thirds nested">
		<div class="column one"></div>
		<div class="column two"></div>
		<div class="column three"></div>
	</div>
</section>

<section class="row halves">
	<div class="column one">
		<div class="column four-twelfths"></div>
		<div class="column two-twelfths"></div>
	</div>
	<div class="column two">
		<div class="column four-twelfths"></div>
		<div class="column two-twelfths"></div>
	</div>
</section>

<footer class="local">
	<br>	
</footer>

</main><!--/#page-->

<?php get_template_part( 'spine/body' ); ?>

<?php get_footer(); ?>
