<?php
echo "<div class='content'>";
	echo "<h2 class='nodetitle'>" . $title . $pagetype . $editlink . '</h2>';
	echo $body;
	echo '<div class="clear"></div>';
echo '</div>';
if ($page AND $debugging) {
	echo $debug_dump;
}
?>