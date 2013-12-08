<!doctype html public "-//w3c//dtd html 3.2 final//en">
<html class='popup'>
<head>
<title></title>
</head>
<body id="login_page">
<?php
echo $head;
echo $styles;
echo $scripts;
?>
<div id='login'>
	<?php
	// echo "<br><div class='tabs'>" . $tabs . '</div>';
	echo "<div class='pagetitle'><h1>" . $title . '</h1></div>';
	// echo $help;
	// echo $messages;
	echo '<center>';
	if($user->uid) {
		echo "<p>You are already logged in to your member account.</p>";
	} else {
		echo $content;
	}
	echo '</center>';
	/* 
	$debugging = ($_SERVER['REMOTE_ADDR'] == variable_get('zzz_debuggers_ip', '')) ? TRUE : FALSE;
	if($debugging) {
		$msg = "<textarea cols=80 rows=30 style='font-size: 10px;'>";
		$msg .= htmlentities(print_r($content,1));
		$msg .= '</textarea>';
		echo $msg;
		// drupal_set_message($msg);
	}
	*/
	?>
</div>
</body>
</html>
