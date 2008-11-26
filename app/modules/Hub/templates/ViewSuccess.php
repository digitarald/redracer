<?php	require('Title.php'); ?>

<hr />

<div class="span-9 colborder">
	<h3 class="green">Read More</h3>

<?php	if (count($resource['links']) ): ?>
	<dl class="links">
<?php		foreach ($resource['links'] as $link): ?>
		<dt>
			<a href="<?= $link['url'] ?>"><?= $link['title'] ?></a>
			<em>(<?= $link['parsed']['host'] ?>)</em>
<?php			if ($us->hasCredential('resources.edit') || $resource['is_contributor']): ?>
			<small class="right">
				<a href="<?= $link['url_edit'] ?>">Edit</a>
			</small>
<?php			endif; ?>
		</dt>
		<dd>
<?php			if (isset($link['description_html'])): ?>
			<?= $link['description_html'] ?>
<?php			endif; ?>
		</dd>
<?php		endforeach; ?>
	</dl>
<?php	else: ?>
	<p class="empty">
		No Links added yet!
	</p>
<?php	endif; ?>

	<p class="footer">
		<a href="<?= $resource['url_link'] ?>">Add a Link</a>
	</p>
</div>

<div class="span-8 last">
	<h3 class="red">Meet the Contributors</h3>

<?php	if (count($resource['contributors']) ): ?>
	<dl class="devs">
<?php		foreach ($resource['contributors'] as $contributor): ?>
		<dt>
			<img src="<?= $contributor['user']['url_avatar'] ?>" />
			<a href="<?= $contributor['user']['url_view'] ?>" title="View Profile"><?= $contributor['user']['fullname'] ?></a>
			<em>(<?= $contributor['user']['nickname'] ?>)</em>
<?php			if ($us->hasCredential('resources.edit') || $resource['is_contributor']): ?>
			<small class="right">
				<a href="<?= $contributor['url_edit'] ?>">Edit</a>
			</small>
<?php			endif; ?>
		</dt>
		<dd>
<?php			if (!$contributor['active']): ?>
			<em>(inactive)</em>
<?php			endif; ?>
			<?= $contributor['role_text'] ?>
		</dd>
<?php		endforeach; ?>
	</dl>
<?php	else: ?>
	<p class="empty">
		No Contributor registered yet!
	</p>
<?php	endif; ?>
	<p class="footer">
<?php	if (!$resource['is_contributor']): ?>
		<a href="<?= $resource['url_contributor'] ?>">Add me as Contributor</a>
<?php	endif; ?>
	</p>
</div>
<hr class="clear" />

<?php	if ($resource['readme']): ?>
	<?= $resource['readme_html'] ?>
<?php	endif; ?>

<?php	if ($resource['category'] == 0): ?>

<h3 class="purple">Releases</h3>

<?php		if (count($resource['releases'])): ?>
	<dl>
<?php			foreach ($resource['releases'] as $release): ?>
		<dt>
			<a href="<?= $release['url_view'] ?>"><?= $release['version'] ?></a>
			<em>(<?= $release['stability_text'] ?>)</em>
<?php				if ($us->hasCredential('resources.edit') || $resource['is_contributor']): ?>
			<small class="right">
				<a href="<?= $release['url_edit'] ?>">Edit</a>
			</small>
<?php				endif; ?>
		</dt>
		<dd>
<?php				if ($release['notes']): ?>
		<?= $release['notes'] ?>
<?php				endif; ?>
		</dd>
<?php			endforeach; ?>
	</dl>
<?php		else: ?>
	<p class="empty">
		No Releases yet!
	</p>
<?php		endif; ?>

<?php	if ($us->hasCredential('resources.edit') || $resource['is_contributor']): ?>
	<p class="footer">
		<a href="<?= $resource['url_release'] ?>">Add Release</a>
	</p>

<?php	endif; ?>
<?php	endif; ?>