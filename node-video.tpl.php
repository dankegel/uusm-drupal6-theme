<?php
// PREP
// MAIN PIC
$thepic = '';
if($node->field_images[0]['filepath']) {
	// $thepic = imagecache_create_url('hero_narrow', $node->field_images[0]['filepath']);
	// $trimtheproduct1 = strpos($theproduct, '.com/');
	// $theproducttrimmed1 = substr($theproduct, $trimtheproduct1+5);
	$thepic = theme('imagecache', 'story_pic', $node->field_images[0]['filepath'], $node->title, $node->title, array('align'=>'left','style'=>'margin: 0 25px 20px 0;'));
}
// DISPLAY
// echo "<div class='nodeTitle'>" . $node->title . '</div>';
echo "<div class='content'>";
	echo "<h2 class='nodetitle'>" . $title . '</h2>';
	echo $thepic;
	echo $node->content['body']['#value'];
	if ($node->field_emvideo[0]['value']) {
		echo "<ul class='file_links'>";
		foreach($node->field_emvideo AS $thing) {
			if($thing['status']) {
				// echo "<a href='/emvideo/modal/" . $node->nid . "/430/355/field_emvideo/youtube/" . $thing['value'] . "/index.php' rel='shadowbox[video];width=430;height=355'>[X] watch</a>";
				echo $thing['view'];
			}
		}
		echo '</ul>';
	}
	echo '<div class="clear"></div>';
echo '</div>';
if ($page AND $debugging) {
	echo $debug_dump;
}
?>