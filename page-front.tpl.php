<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $language->language ?>" xml:lang="<?php echo $language->language ?>">
<head>
	<?php
	echo '<title>' . $head_title . '</title>';
	echo $head;
	echo $styles;
	echo $scripts;
	?>
	<script type="text/javascript">
	function buttonroll(obj, state){
		var thing = document.getElementById(obj);
		if(state == 'dim') {
			thing.style.backgroundPosition = 'left top';
		} else {
			thing.style.backgroundPosition = 'left -69px';
		}
	}
	</script>
</head>
<body id="front">
<div id="container">
	<div id="wrapper">
		<div id='sidebar'>
			<?php
			echo "<a href='/'><img src='/" . $directory . "/images/front-logo.png' alt='' vspace='17' border=0></a>";
			echo "<div id='front-address'>" . $mission . '</div>';
			if($sidebar) { echo $sidebar; }
			?>
		</div>
		<div id="main">
			<!-- <table width='620' height='490' cellspacing='0' cellpadding='0' border='0'><tr><td> -->
				<?php
				if($messages) { echo $messages; }
				echo "<div id='slideshow'>";
					echo $slideshow;
				echo '</div>';
				
				echo "<div id='quicklinks'>";
				echo '</div>';
				
				echo "<div id='overlaytext'>";
					if($pagetop_1) { echo "<div id='pagetop_1'>" . $pagetop_1 . '</div>'; }
					if($pagetop_2) { echo "<div id='pagetop_2'>" . $pagetop_2 . '</div>'; }
					echo "<div class='tagline'>We are a <a href='/about-our-church/mission-vision/welcoming-congregation'><img align=absmiddle src='/" . $directory . "/images/welcoming.gif' alt='rainbow flag' border=0 /></a><a class='taglink' href='/about-our-church/mission-vision/welcoming-congregation'>Welcoming Congregation</a> and a designated <a href='/about-our-church/mission-vision/peace-site'><img align=absmiddle src='/" . $directory . "/images/peace_site.gif' alt='peace dove' border=0 /></a><a class='taglink' href='/about-our-church/mission-vision/peace-site'>Peace Site</a></div>";
				echo '</div>';
				
				echo "<div id='content_top'>";
					echo $content_top;
				echo '</div>';
				echo "<div id='content'>" . $content . '</div>';
				echo "<div id='content'>" . $feed_icons . '</div>';
				if($content_bottom) { echo "<div id='content_bottom'>" . $content_bottom . '</div>'; }
				/* 
				if($debugging) {
					echo $debug_dump;
				}
				*/
				?>
			<!-- </td></tr></table> -->
		<?php
		if($extra) { echo "<div id='extra'>" . $extra . '</div>'; }
		?>
		</div>
		<div class="clear"></div>
	</div>
	<div id="lower_wrapper"></div>
	<div id="footer_wrapper">
	<?php
	echo "<div class='footer_menu'>" . $footer_1 . '</div>';
	echo "<div class='footer_menu'>" . $footer_2 . '</div>';
	echo "<div class='footer_menu'>" . $footer_3 . '</div>';
	echo "<div class='footer_menu'>" . $footer_4 . '</div>';
	echo "<div class='footer_menu'>" . $footer_5 . '</div>';
	echo "<div class='footer_menu'>" . $footer_6 . '</div>';
	?>
	</div>
    
</div>
<div class="clear"></div>
<div id="footer"><?php echo $footer_message; ?><?php echo $footer; ?></div>
<?php echo $closure; ?>
</body>
</html>
