<?php
// $Id: views-view-unformatted.tpl.php,v 1.6 2008/10/01 20:52:11 merlinofchaos Exp $
/**
 * @file views-view-unformatted.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<?php if (!empty($title)): ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>
<?php foreach ($rows as $id => $row): ?>
  <div class="<?php print $classes[$id]; ?>">
    <?php
    print $row;
    /* 
	$debugging = ($_SERVER['REMOTE_ADDR'] == variable_get('zzz_debuggers_ip', 0)) ? 1 : 0;
	if($debugging) {
		$msg = "<textarea cols=80 rows=5 style='font-size: 10px;'>";
		$msg .= htmlentities(print_r($classes,1));
		$msg .= '</textarea>';
		// echo $msg;
		drupal_set_message($msg);
	}
	*/
    ?>
  </div>
<?php endforeach; ?>
