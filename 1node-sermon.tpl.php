<?php
// PREP
$medialinks = '';
if(substr_count($node->field_datetime[0]['view']," (All day)")) {
	$date = str_replace(' (All day)', '', $node->field_datetime[0]['view']);
} else {
	$date = $node->field_datetime[0]['view'];
}
if(substr($title, 0, 1) != '"') {
	$title = '"' . $title . '"';
}
if($node->field_textfile[0]['fid']) {
	$medialinks .= "<li class='medialink textfile'><a href='/" . $node->field_textfile[0]['filepath'] . "'>Read the text</a></li>";
}
if($node->field_audio[0]['fid']) {
	$medialinks .= "<li class='medialink audiofile'<a href='/" . $node->field_audio[0]['filepath'] . "'>Listen</a></li>";
}
if($node->field_emvideo[0]['value']) {
	$medialinks .= "<li class='medialink videofile'<a href='" . $node->field_emvideo[0]['data']['flash']['url'] . "' rel='shadowbox'>Watch</a></li>";
}
// DISPLAY
// echo "<div class='nodeTitle'>" . $node->title . '</div>';
if($page) {
	echo "<div class='node'>";
		echo '<div class="submitted">' . $date . '</div>';
		echo '<h2>' . $title . '</h2>';
		echo $node->content['body']['#value'];
		if($medialinks) { echo '<div class="medialinks">' . $medialinks . '</div>'; }
		if($page) { echo $links; }
		if($debugging) {
			echo $debug_dump;
		}
	echo '</div>';
} else {
	echo "<div class='node'>";
		echo '<div class="submitted">' . $date . '</div>';
		echo '<div class="title"><a href="' . $node_url . '" title="' . $title . '">' . $title . '</a></div>';
		echo '<em>' . $node->field_minister[0]['value'] . '</em>';
		$bodytext = node_teaser($node->content['body']['#value'], 2, 250);
		if(strlen($node->content['body']['#value']) > 250) { $bodytext .= '... <a href="' . $node_url . '"><em>Read&nbsp;More</em></a>'; }
		echo $bodytext;
		if($medialinks) { echo '<div><ul class="medialinks">' . $medialinks . '</ul></div>'; }
		/* 
		$debugging = ($_SERVER['REMOTE_ADDR'] == '72.193.166.131') ? 1 : 0;
		if($debugging AND $node->nid == 100) {
			$msg = "<textarea cols=80 rows=30 style='font-size: 10px;'>";
			$msg .= htmlentities(print_r($node,1));
			$msg .= '</textarea>';
			// echo $msg;
			drupal_set_message($msg);
		}
		*/
	echo '</div>';
}
?>
<div class='clear'></div>