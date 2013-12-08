<?php
// PREP
// MAIN PIC
$thepic = '';
	if($page OR (arg(0) == 'art-wall' AND !arg(2))) {
		$preset = 'main_pic';
	} else {
		$preset = 'medium_pic';
		$title = "<a href='" . $node_url . "'>" . $title . '</a>';
	}
	if($node->field_picture[0]['filepath']) {
		$thepic = theme('imagecache', $preset, $node->field_picture[0]['filepath'], $node->title, $node->title, array('align'=>'left','style'=>'margin: 0 25px 20px 0;'));
	}
// DISPLAY
// echo "<div class='nodeTitle'>" . $node->title . '</div>';
echo "<div class='content'>";
	echo "<h2 class='nodetitle'>" . $node->field_moyr[0]['view'] . '</h2>';
	echo "<h2 class='nodetitle'>" . $title . $pagetype . $editlink . '</h2>';
	echo $thepic;
	if($page) {
		echo $node->content['body']['#value'];
	} else {
		echo $node->content['body']['#value'];
	}
	if (count($node->files)) {
		echo "<ul class='file_links'>";
		foreach($files AS $thing) {
			if($thing->list) { echo "<li> <a target=_blank href='" . $thing->filepath . "'>" . $thing->description . '</a> (' . number_format($thing->filesize/1000) . 'kb)</li>'; }
		}
		echo '</ul>';
	}
	echo '<div class="clear"></div>';
echo '</div>';
echo '<hr>';
/* 
if ($page AND $debugging) {
	echo $debug_dump;
}
*/
?>