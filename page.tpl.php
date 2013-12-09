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
function roll(obj, state){
	var thing = document.getElementById(obj);
	if(state == 'dim') {
		thing.style.backgroundPosition = '50% top';
	} else {
		thing.style.backgroundPosition = '50% -30px';
	}
}
</script>
</head>
<body <?php if($pageid) { echo ' id="' . $pageid . '"'; } ?>>
	<div id="container">
		<div id="wrapper">
			<div id="header">
				<?php
				echo "<div id='logo'>";
					echo "<a href='/'><img src='/" . $directory . "/images/main-logo.png' alt='' border=0 vspace='4' width='250' height='81''></a>";
					echo '<br>' . $mission;
				echo '</div>';
				echo "<div id='quicklinks'>";
					if($pagetop_1) { echo "<div id='pagetop_1'>" . $pagetop_1 . '</div>'; }
					if($pagetop_2) { echo "<div id='pagetop_2'>" . $pagetop_2 . '</div>'; }
				echo '</div>';
				?>
			</div>
			<div id="nav">
				<?php
				echo theme('links', $primary_links, array('class' => 'links primary-links'));
				/* 
				if (isset($secondary_links)) {
					echo theme('links', $secondary_links, array('class' => 'links secondary-links'));
				}
				*/
				if($pageid == 'term-15') {
					// MEMBERS ONLY
					if($user->uid) {
						$suckerfish = uusm_block_object('nice_menus',$suckermenu);
						echo $suckerfish->content;
					} else {
						echo theme('links', $secondary_links, array('class' => 'links secondary-links'));
					}
				} else {
					$suckerfish = uusm_block_object('nice_menus',$suckermenu);
					echo $suckerfish->content;
				}
				if($hd_img) {
					echo "<div class='interior_header'>" . $hd_img . "</div>";
				/* 
				} else {
					echo "<div class='interior_header'><img src='" . $directory . "/images/head_image_default.jpg' border=0></div>";
					*/
				}
				?>
			</div>
            
			
				<?php
				if($sidebar) { 
					echo "<div id='sidebar'>".$sidebar.$feed_icons."</div><div id='main'>"; 
				}else{
					echo "<div id='nosidebar'></div><div id='full-main'>";
				}
				?>
			
				<?php
				if($messages) { echo $messages; }
				if($slideshow) { echo $slideshow; }
				if($breadcrumb) { echo $breadcrumb; }
				if($content_top) { echo $content_top; }
				echo "<div id='content'>";
					if($tabs) { echo $tabs; }
					if ($tabs2) { echo '<ul class="tabs secondary">'. $tabs2 .'</ul>'; }
					if(!$is_node) { echo "<h2 class='pagetitle'>" . $title . $pagetype . $editlink . '</h2>'; }
					if(arg(2) == 'delete') { echo "<h2 class='pagetitle'>" . $title . '</h2>'; }
					if (arg(0) == 'taxonomy' AND arg(1) != 'vocabulary' AND arg(2)) {
						$description_block = taxonomy_get_term(arg(2));
						if($description_block->description) {
							echo '<p>' . nl2br($description_block->description) . '</p>';
						}
						if($anon_message) {
							echo '<p>' . $anon_message . '</p>';
						}
					}
					print $help;
					if(!$is_term OR $show_list) { echo $content; }
				echo '</div>';
				if($content_bottom) { echo '<hr>' . $content_bottom; }
				if($debugging) {
					echo $debug_dump;
				}
				?>
			</div>
			<div class="clear"></div>
			
			<?php
				if($sidebar) { 
					echo "<div id='watermark-wide'></div>"; 
				}else{
					echo "<div id='watermark'></div>";
				}
				
				?>
		</div>
		<div id="frame_bottom"></div>
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
<?php 
echo $closure;
?>
</body>
</html>
