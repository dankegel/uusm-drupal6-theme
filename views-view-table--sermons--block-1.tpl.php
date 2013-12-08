<?php
// $Id: views-view-table.tpl.php,v 1.8 2009/01/28 00:43:43 merlinofchaos Exp $
/**
 * @file views-view-table.tpl.php
 * Template to display a view as a table.
 *
 * - $title : The title of this group of rows.	May be empty.
 * - $header: An array of header labels keyed by field id.
 * - $fields: An array of CSS IDs to use for each field id.
 * - $class: A class or classes to apply to the table, based on settings.
 * - $row_classes: An array of classes to apply to each row, indexed by row
 *	 number. This matches the index in $rows.
 * - $rows: An array of row items. Each row is an array of content.
 *	 $rows are keyed by row number, fields within rows are keyed by field ID.
 * @ingroup views_templates
 */
	/* 
	$debugging = ($_SERVER['REMOTE_ADDR'] == variable_get('zzz_debuggers_ip', 0)) ? 1 : 0;
	if($debugging) {
		$msg = "<textarea cols=80 rows=20 style='font-size: 10px;'>";
		$msg .= htmlentities(print_r($rows,1));
		$msg .= '</textarea>';
		// echo $msg;
		drupal_set_message($msg);
	}
	*/
?>
<table class="<?php print $class; ?>">
	<?php if (!empty($title)) : ?>
		<caption><?php print $title; ?></caption>
	<?php endif; ?>
	<thead>
		<tr>
			<?php foreach ($header as $field => $label): ?>
				<th class="views-field views-field-<?php print $fields[$field]; ?>">
					<?php print $label; ?>
				</th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php
		$thismo = '';
		$moarray = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		$monthsarray = array('January','February','March','April','May','June','July','August','September','October','November','December');
		foreach ($rows as $count => $row) {
			$thedate = strip_tags($row['field_datetime_value']);
			$chunks = explode(' ',$thedate);
			$themo = $chunks[0];
			if($themo != $thismo) {
				$which = ($thismo) ? 'monthname' : 'firstmonthname';
				$monum = array_search($themo, $moarray);
				$themonth = $monthsarray[$monum];
				echo "<tr><td class='" . $which .  "' colspan='2'>" . $themonth . "</td></tr>";
			}
			$thismo = $themo;
			?>
			<tr class="<?php print implode(' ', $row_classes[$count]); ?>">
				<?php foreach ($row as $field => $content): ?>
					<td class="views-field views-field-<?php print $fields[$field]; ?>">
						<?php print $content; ?>
					</td>
				<?php endforeach; ?>
			</tr>
		<?php } ?>
	</tbody>
</table>
