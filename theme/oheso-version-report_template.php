<?php
	$timeformat = 'Y-m-d H:i:s';
?>

<div class="wrap">
<h2 class="wp-heading-inline">Version Report</h2>
<hr class="wp-header-end">

<table class="form-table">
<tr>
	<th><label for="CheckPublicDirectory">Check Wordpress Public Directory</label></th>
	<td>
		<form id="oheso-version-report-check" action="" method="post">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Update Report"  />
		<input type="hidden" name="action" value="check_versions" />
		</form>
	</td>

</tr>
<!-- tr>
	<th><label for="CheckPublicDirectory">Mail To This Report</label></th>
	<td>
		<form id="oheso-version-report-mail" action="" method="post">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Mail To Admin"  />
		<input type="hidden" name="action" value="mail" />
		</form>
	</td>
</tr -->
</table>


<?php if(isset($saveddata) && $saveddata !== false) : ?>
<hr />
<div class="oheso-report">
<h3>Version Report Summary</h3>

<table>
<tr><th>Site Name</th><td><?= bloginfo('title'); ?></td></tr>
<tr><th>Site URL</th><td><?= bloginfo('url'); ?></td></tr>
<tr><th>Report Create Date</th><td class="createdate"><?= date($timeformat, $saveddate); ?> <?= date('T O'); ?></td></tr>
</table>

<h3>Update Summary</h3>
<table>

<tr><th>Core</th><td>
<?php if ($saveddata['core']['cur'] != $saveddata['core']['new']) : ?>
<table class="wp-list-table">
<tr><th>Ver</th></tr>
<tr><td><?= $saveddata['core']['cur']; ?> -> <?= $saveddata['core']['new']; ?></td></tr>
</table>
<?php else: ?>
<div>This is latest version.</div>
<?php endif; ?>
</td></tr>

<tr><th>Plugins</th><td>
<?php 
if (isset($saveddata['plugins'])
  && is_array($saveddata['plugins'])
  && count($saveddata['plugins']) > 0) : ?>
<table class="wp-list-table">
<tr>
<th>Name</th>
<th>Ver</th>
</tr>
<?php foreach ($saveddata['plugins'] as $k => $v) : ?>
<tr>
<td>[<?= $v['name']; ?>]</td>
<td><?= $v['cur']; ?> -> <?= $v['new']; ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<div>No plugins provided with updated versions.</div>
<?php endif; ?>
</td></tr>

<tr><th>Themes</th><td>
<?php
if (isset($saveddata['themes'])
  && is_array($saveddata['themes'])
  && count($saveddata['themes']) > 0) : ?>
<table class="wp-list-table">
<tr>
<th>Name</th>
<th>Ver</th>
</tr>
<?php foreach ($saveddata['themes'] as $k => $v) : ?>
<tr>
<td>[<?= $v['name']; ?>]</td>
<td><?= $v['cur']; ?> -> <?= $v['new']; ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<div>No themes provided with updated versions.</div>
<?php endif; ?>
</td></tr>

</table>


<p>&nbsp;</p>
<h3>Plugin Information</h3>
<?php
if (isset($saveddata['in_plugins'])
  && is_array($saveddata['in_plugins'])
  && count($saveddata['in_plugins']) > 0) : ?>
<table class="wp-list-table ">
<tr>
<th>Name</th>
<th>Ver</th>
<th>lastupdate<br>TZ: <?= date('T O'); ?></th>
<th>past days</th>
<?php if (is_multisite()) : ?><th>MU Use</th><?php endif; ?>
</tr>
<?php  foreach ($saveddata['in_plugins'] as $k => $v) : ?>
<tr scope="row">
<?php if ($v['lastupdated'] != null) :?>
<td <?php if (is_plugin_active($v['path'])) :?>class="active"<?php endif; ?>><a href="<?= $v['url']; ?>" target="_blank"><?= $v['name']; ?></a></td>
<?php else : ?>
<td <?php if (is_plugin_active($v['path'])) :?>class="active"<?php endif; ?>><?= $v['name']; ?></td>
<?php endif; ?>
<td><?= $v['ver']; ?></td>
<?php if ($v['lastupdated'] != null) :?>
<td class="lastupdate"><?= date($timeformat, $v['updated']); ?></td>
<td class="pastdays"><?= number_format((time() - $v['updated']) / 86400); ?></td>
<?php else : ?>
<td colspan="2">Not Found.<br>Probably unofficial or obsolete.</td>
<?php endif; ?>
<?php if (is_multisite()) : ?><td><?= is_plugin_active_for_network($v['path']) ? 'active' : 'inactive'; ?></td><?php endif; ?>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
</div>
<br class="clear" />
</div>
<?php endif; ?>
