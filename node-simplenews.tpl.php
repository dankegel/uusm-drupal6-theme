<?php
// PREP
// MAIN PIC
$thepic = '';
if($node->field_pictures[0]['filepath'] AND !$page) {
	$thepic = theme('imagecache', 'medium_pic', $node->field_pictures[0]['filepath'], $node->title, $node->title, array('align'=>'right','style'=>'margin: 0 0 10px 20px;'));
} elseif($node->field_pictures[0]['filepath']) {
	// $thepic = imagecache_create_url('hero_narrow', $node->field_pictures[0]['filepath']);
	// $trimtheproduct1 = strpos($theproduct, '.com/');
	// $theproducttrimmed1 = substr($theproduct, $trimtheproduct1+5);
	$thepic = theme('imagecache', 'story_pic', $node->field_pictures[0]['filepath'], $node->title, $node->title, array('align'=>'left','style'=>'margin: 0 25px 20px 0;'));
}
// DISPLAY
// echo "<div class='nodeTitle'>" . $node->title . '</div>';
if(!$page) { echo '<hr>'; }
echo "<div class='content'>";
	echo $thepic;
	echo "<h2 class='nodetitle'>" . $title . '</h2>';
	echo $node->content['body']['#value'];
	if ($node->nid == 105 OR $node->nid == 106) {
		echo "<ul class='file_links'>";
		foreach($files AS $thing) {
			if($thing->list) { echo "<li> <a target=_blank href='" . $thing->filepath . "'>" . $thing->description . '</a> (' . number_format($thing->filesize/1000) . 'kb)</li>'; }
		}
		echo '</ul>';
	}
	echo '<div class="clear"></div>';
echo '</div>';
/* 
if ($page AND $debugging) {
	echo $debug_dump;
}
*/
?>
