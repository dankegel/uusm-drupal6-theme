<?php
// PREP
$thepic = '';
if($node->field_picture[0]['filepath']) {
	// $thepic = imagecache_create_url('hero_narrow', $node->field_images[0]['filepath']);
	// $trimtheproduct1 = strpos($theproduct, '.com/');
	// $theproducttrimmed1 = substr($theproduct, $trimtheproduct1+5);
	$thepic = theme('imagecache', 'medium_pic', $node->field_picture[0]['filepath'], $node->title, $node->title, array('align'=>'left','style'=>'margin: 0 15px 10px 0;'));
}
// DISPLAY
// echo "<div class='nodeTitle'>" . $node->title . '</div>';
echo "<div class='content'>";
	echo "<h2 class='nodetitle'>" . $title . $pagetype . $editlink . '</h2>';
	echo $thepic;
	echo $node->content['body']['#value'];
	echo '<div class="clear"></div>';
echo '</div>';
if ($page AND $debugging) {
	echo $debug_dump;
}
?>