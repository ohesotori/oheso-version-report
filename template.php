<style>
.oheso-report {
line-height: 1.2em;
}
.oheso-report table th,
.oheso-report table td {
padding: 2px;
border-bottom: 1px solid #ccc;
}
.oheso-report table td.active {
border-left: 4px solid #00a0d2;
}
.oheso-report .createdate {
display:inline-block;
font-weight: bold;
font-size: 1.2em;
padding: 5px;
background-color: #fff;
border: 1px solid #ccc;
color: #009e9f;
}
</style>
<div class="wrap">
<h2 class="wp-heading-inline">Version Report</h2>
<hr class="wp-header-end">

<?php
?>

<table class="form-table">
<tr>
	<th><label for="CheckPublicDirectory">Check Wordpress Public Directory</label></th>
	<td>
		<form id="oheso-version-report-check" action="" method="post">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Check!"  />
		<input type="hidden" name="action" value="check" />
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

<hr />
<div class="oheso-report">
<h3><?= bloginfo('title'); ?></h3>
<div><?= bloginfo('url'); ?></div>
<div class="createdate">create date : <?= date('Y-m-d H:i:s', $saveddate); ?></div>
<table class="wp-list-table">
<tr>
<th>Name</th>
<th>Ver</th>
</tr>
<?php if ($saveddata['core']['cur'] != $saveddata['core']['new']) : ?>
<tr>
<td>[Core]</td>
<td><?= $saveddata['core']['cur']; ?> -> <?= $saveddata['core']['new']; ?></td>
</tr>
<?php endif; ?>
<tr>
<th>Plugins</th>
</tr>
<?php 
if (isset($saveddata['plugins'])
  && is_array($saveddata['plugins'])
  && count($saveddata['plugins']) > 0) : ?>
<?php foreach ($saveddata['plugins'] as $k => $v) : ?>
<tr>
<td>[<?= $v['name']; ?>]</td>
<td><?= $v['cur']; ?> -> <?= $v['new']; ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<td>No data</td>
<td></td>
</tr>
<?php endif; ?>

<tr>
<th>Themes</th>
</tr>
<?php
if (isset($saveddata['themes'])
  && is_array($saveddata['themes'])
  && count($saveddata['themes']) > 0) : ?>
<?php foreach ($saveddata['themes'] as $k => $v) : ?>
<tr>
<td>[<?= $v['name']; ?>]</td>
<td><?= $v['cur']; ?> -> <?= $v['new']; ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<td>No data</td>
<td></td>
</tr>
<?php endif; ?>
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
<th>lastupdate</th>
<th>past days</th>
<?php if (is_multisite()) : ?><th>MU Use</th><?php endif; ?>
</tr>
<?php  foreach ($saveddata['in_plugins'] as $k => $v) : ?>
<tr scope="row">
<td <?php if (is_plugin_active($v['path'])) :?>class="active"<?php endif; ?>><a href="<?= $v['url']; ?>" target="_blank"><?= $v['name']; ?></a></td>
<td><?= $v['ver']; ?></td>
<?php if ($v['lastupdated'] != null) :?>
<td><?= $v['lastupdated']; ?></td>
<td><?= round((time() - $v['updated']) / 86400); ?></td>
<?php else : ?>
<td>Not Found.</td>
<td></td>
<?php endif; ?>
<?php if (is_multisite()) : ?><td><?= is_plugin_active_for_network($v['path']) ? 'active' : 'inactive'; ?></td><?php endif; ?>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
</div>
<br class="clear" />
</div>
