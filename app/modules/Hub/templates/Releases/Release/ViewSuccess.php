<h3>
	<a href="<?= $resource['url_view'] ?>"><?= $resource['title'] ?></a>
</h3>

<h3>
	<a href="<?= $release['url_view'] ?>">Release <?= $release['version'] ?> <small>(<?= $release['stability_text'] ?>)</small></a>
</h3>

<?php	if (isset($release['notes_html'])): ?>
	<?= $release['notes_html'] ?>
<?php	else: ?>
	<p><em>No notes</em></p>
<?php	endif; ?>

<?php	if (isset($release['changelog_html'])): ?>
	<h4>Changelog</h4>
	<pre><?= $release['changelog_html'] ?></pre>
<?php	endif; ?>

<?php			if ($us->hasCredential('resources.edit') || $resource['is_contributor']): ?>
	<p class="footer">
		<a href="<?= $release['url_edit'] ?>">Edit</a>
	</p>
<?php			endif; ?>

<?php	if (count($release['dependencies'])): ?>
<h3 class="green">Dependencies</h3>
	<div class="dependencies">
		<ul>
<?php		foreach ($release['dependencies'] as $dep): ?>
			<li>
				<a href="#file-<?= $dep['target_file_id'] ?>"><?= $dep['ident'] ?></a>
			</li>
<?php		endforeach; ?>
	</div>
<?php	endif; ?>

<?php	if (count($release['files'])): ?>
<h3 class="green">Files</h3>

<p>
	You can select single files or add the complete package.
	The required and optional dependencies are added to your <a href="<?= $rt->gen('cart.index') ?>">download basket</a> automatically.
</p>

<form action="<?= $rt->gen('cart.index') ?>" method="post" id="form-download">
	<ul class="files<?= ($complete) ? ' complete' : '' ?>">
<?php	$level = 1;
			$limit_roles = array(2, 3);

			$hidden_files = 0;

			foreach ($release['files'] as $file):
				if ($file['level'] < $level):
					echo str_repeat('</ul></li>', $level - $file['level']);
				endif;
				$level = $file['level'];

				$hidden = (!in_array($file['role'], $limit_roles));
				if ($hidden) {
					$hidden_files++;
				}

				$target = array();
				foreach ($file['dependencies'] as $dep) {
					$target[] = $dep['target'];
				}

				$dependencies = 'without dependencies';
				if (count($target)) {
					$dependencies = 'depends on ' . implode(', ', $target);
				}
?>
		<li class="file<?= ($hidden) ? ' hidden' : '' ?>">
			<label id="file-<?= $file['id'] ?>" class="title role-<?= $file['role'] ?><?= $file['folder'] ? ' folder': '' ?>" title="<?= $file['role_text'] ?>, <?= $dependencies ?>">
				<input type="checkbox" name="file_ids" value="<?= $file['id'] ?>" />
				<a href="<?= $file['url'] ?>" class="name"><?= $file['name'] ?></a>
<?php		if ($file['description']): ?>
					<span><?= $file['description'] ?></span>
<?php		endif; ?>
			</label>
<?php		if ($file['folder']): ?>
			<ul class="sub">
<?php		else: ?>
		</li>
<?php		endif; ?>
<?php	endforeach; ?>
		<?= str_repeat('</ul>', $file['level']) ?>
	</ul>

	<fieldset class="footer">
		<div>
			<a href="<?= $rt->gen(null, array('complete' => null)) ?>" id="release-show-0" class="<?= ($complete) ? '' : 'hidden' ?>">Hide auxiliary files (<?= $hidden_files ?>)</a>
			<a href="<?= $rt->gen(null, array('complete' => '1')) ?>" id="release-show-1" class="<?= ($complete) ? 'hidden' : '' ?>">Show auxiliary files (<?= $hidden_files ?>)</a>
		</div>
		<input type="submit" value="Download Selected" class="submit" />
		or <a href="<?= $rt->gen('cart.index', array('release_id' => $release['id'])) ?>">Download whole release</a>
	</fieldset>
</form>

<?php	endif; ?>