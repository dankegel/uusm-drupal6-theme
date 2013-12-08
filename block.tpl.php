<div class='block block-<?php print $block->module; ?>' id='block-<?php print $block->module; ?>-<?php print $block->delta; ?>'>
	<?php
	if($block->module == 'block' AND $block->delta == 2) {
		echo "<div class='content'>";
			echo $block->content;
		echo '</div>';
		/* 
		if($debugging) {
			echo "<textarea cols=30 rows=20 style='font-size: 9px;'>";
			echo htmlentities(print_r($block,1));
			echo '</textarea>';
		}
		*/	
	} else {
		echo "<h2 class='title'>" . $block->subject . '</h2>';
		echo "<div class='content'>" . $block->content . '</div>';
	}
	?>
</div>