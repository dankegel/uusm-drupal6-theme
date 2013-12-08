<?php
/**
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 * - $admin_links: A rendered list of administrative links
 * - $admin_links_raw: A list of administrative links suitable for theme('links')
 */
$x = uusm_block_object('block','3');
$hours = "<div class='hours'>" . $x->content . '</div>';
$imagepath = 'sites/default/files/uuccsm.png';
$pic = theme('imagecache','medium_pic',$imagepath,'','',array('align'=>'right', 'style'=>'margin-left: 20px;'));
$debugging = ($_SERVER['REMOTE_ADDR'] == variable_get('zzz_debuggers_ip', 0)) ? 1 : 0;
/* 
if($debugging) {
	$msg = "<textarea cols=80 rows=30 style='font-size: 10px;'>";
	$msg .= htmlentities(print_r($rows,1));
	$msg .= '</textarea>';
	// echo $msg;
	drupal_set_message($msg);
}
*/
?>
<?php echo $pic; ?>
<?php echo $hours; ?>
<div class="<?php print $classes; ?>">
  <?php echo "<h2>Sunday Services</h2>"; ?>
  <?php if ($admin_links): ?>
    <div class="views-admin-links views-hide">
      <?php print $admin_links; ?>
    </div>
  <?php endif; ?>
  <?php if ($header): ?>
    <div class="view-header">
      <?php print $header; ?>
    </div>
  <?php endif; ?>
  <?php if ($rows): ?>
    <div class="view-content">
      <?php print $rows; ?>
    </div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <?php print $empty; ?>
    </div>
  <?php endif; ?>
  <?php if ($footer): ?>
    <div class="view-footer">
      <?php print $footer; ?>
    </div>
  <?php endif; ?>
</div>
<div class='clear'></div>