<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print "-sticky"; } ?><?php if (!$status) { print " node-unpublished"; } ?>">
	<?php
	echo '<h2>';
	if(!$page) {
		echo '<a href="' . $node_url . '" title="' . $title . '">';
	}
	echo $title;
	if(!$page) {
		echo '</a>';
	}
	echo $pagetype . $editlink;
	echo '</h2>';
	if($submitted) { echo "<div class='submitted'>" . $submitted . '</div>'; }
	if(isset($_GET['fid'])) {
		$picid = $_GET['fid'];
		$piccount = count($node->field_pictures);
		$g = count($node->field_pictures);
		if($g) {
			$i = 0;
			foreach($node->field_pictures AS $thing) {
				$previtem = $i-1;
				$nextitem = $i+1;
				$finalpic = $g-1;
				$thedesc = $thing['alt'];
				$thepath = $thing['filepath'];
				if($thing['fid'] == $picid) {
					$thispic = $thing['filepath'];
					echo '<center>' . theme('imagecache', 'main_pic', $thing['filepath'], $alt, $thetitle, array('style'=>'margin: 5px auto;'));
					echo "<br><span class='footer'>$thedesc</span>";
					echo '</center>';
					if($i == 0) {
						$prevpic = $node->field_pictures[$finalpic];
						$nextpic = $node->field_pictures[$nextitem];
					} elseif($i == $finalpic) {
						$prevpic = $node->field_pictures[$previtem];
						$nextpic = $node->field_pictures[0];
					} else {
						$prevpic = $node->field_pictures[$previtem];
						$nextpic = $node->field_pictures[$nextitem];
					}
				}
				$i++;
			}
		}
	} else {
		$g = count($node->field_pictures);
		if($g) {
			$i = 0;
			foreach($node->field_pictures AS $thing) {
				$previtem = $i-1;
				$nextitem = $i+1;
				$finalpic = $g-1;
				$thedesc = $thing['alt'];
				$thepath = $thing['filepath'];
				if($i == 0) {
					$thispic = $thing['filepath'];
					echo '<center>' . theme('imagecache', 'main_pic', $thing['filepath'], $alt, $thetitle, array('style'=>'margin: 5px auto;'));
					echo "<br><span class='footer'>$thedesc</span>";
					echo '</center>';
					if($i == 0) {
						$prevpic = $node->field_pictures[$finalpic];
						$nextpic = $node->field_pictures[$nextitem];
					} elseif($i == $finalpic) {
						$prevpic = $node->field_pictures[$previtem];
						$nextpic = $node->field_pictures[0];
					} else {
						$prevpic = $node->field_pictures[$previtem];
						$nextpic = $node->field_pictures[$nextitem];
					}
				}
				$i++;
			}
		}
	}
	if(substr($thispic, 0, 1) == '/') {
		$thispic = substr($thispic, 1);
	}
	// if($node->body) {
		echo $node->content['body']['#value'];
	// }
	echo "<br><table width=670 cellspacing='0' cellpadding='0' border='0'><tr><td width=50% align=left><a class='prevnext' href='" . $node_url . '?fid=' . $prevpic['fid'] . "'>&laquo;&nbsp;PREV</a></td>";
	// echo "<td align=center> <a href='/zoom?gid=" . $node->nid . "&pic=" . $thispic . "'>ZOOM</a> </td>";
	echo "<td width=50% align=right><a class='prevnext' href='" . $node_url . '?fid=' . $nextpic['fid'] . "'>NEXT&nbsp;&raquo;</a></td></tr></table>";
	$i = 0;
	echo "<center><br><div class='thumbtable'>";
	foreach($node->field_pictures AS $thing) {
		$thetitle = $thing['title'];
		$alt = $node->title;
		echo "<span class='gallery'><a href='" . $node_url . '?fid=' . $thing['fid'] . "'>";
		print theme('imagecache', 'thumb', $thing['filepath'], $alt, $thetitle, array('style'=>'margin: 5px;'));
		echo '</a></span>';
		$i++;
		if($i % 6 == 0) { echo '<br clear=all>'; }
	}
	echo "</div></center><br clear=both>";
	echo '<br>';
	echo "<div class='service-links'>";
		if ($links) { echo "<span class='sharethis'>" . $links . '</span>'; }
		if ($terms) { echo "<span class='right'>Tags: " . $terms . '</span>'; }
	echo '</div>';
	/* 
	if($debugging) {
		$msg = "<textarea cols=80 rows=30 style='font-size: 10px;'>";
		$msg .= htmlentities(print_r($node,1));
		$msg .= '</textarea>';
		// echo $msg;
		drupal_set_message($msg);
	}
	*/
echo "</div>";
?>
