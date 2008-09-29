<div class="post">
	<h2>
		<a href="<?= $resource['url'] ?>" class="bookmark"><?= $resource['title'] ?></a>
	</h2>

	<small>
<?php		if ($resource['license_text']): ?>
		Licensed under
<?php			if ($resource['license_url']): ?>
		<a href="<?= $resource['license_url'] ?>" class="license"><?= $resource['license_text'] ?></a>.
<?php			else: ?>
		<?= $resource['license_text'] ?>.
<?php			endif; ?>
<?php		endif; ?>
		Updated <?= OurDate::prettyDate($resource['updated_at']) ?>.
		<?= count($resource['contributors']) ?> Contributors.
		<a href="<?= $resource['url_edit'] ?>">Edit</a>
	</small>
</div>

<?= $resource['text_html'] ?>

<hr />

<div class="block span-8 colborder">
	<h3 class="blue">Read More</h3>

<?php	if (count($resource['links']) ): ?>
<?php		foreach ($resource['links'] as $link): ?>
	<div>
		<h4>
			<a href="<?= $link['url'] ?>"><?= $link['title'] ?></a>
			<small><a href="<?= $link['url_edit'] ?>" class="right">Edit</a></small>
		</h4>
<?php			if ($link['text']): ?>
		<p><?= $link['text'] ?></p>
<?php			endif; ?>
		<hr />
	</div>
<?php		endforeach; ?>
<?php	else: ?>
	<p>
		No Link added yet!
	</p>
<?php	endif; ?>

	<p>
		<a href="<?= $resource['url_link'] ?>">Add Link</a>
	</p>
</div>

<div class="span-8 last">
	<h3 class="blue">Contributors</h3>

<?php	if (!$resource['claimed']): ?>
	<p>
		<em>Unregistered Author</em>: <?= $resource['author'] ?>
	</p>
	<p>
		This Resource was not submitted by the author, it needs to be claimed.
		If you are one of the contributors, <a href="<?= $resource['url_claim'] ?>">claim it</a> now!
	</p>
<?php	endif; ?>

<?php	if (count($resource['contributors']) ): ?>
<?php		foreach ($resource['contributors'] as $contributor): ?>
	<hr class="dev" />
	<div class="dev">
		<div class="icon">
			<img src="<?= $contributor['user']['url_avatar'] ?>" />
		</div>
		<h2 class="dev">
			<a href="<?= $contributor['user']['url'] ?>" title="View Profile"><?= $contributor['user']['fullname'] ?></a>
			<span>(<?= $contributor['user']['nickname'] ?>)</span>
			<small><a href="<?= $contributor['url_edit'] ?>" class="right">Edit</a></small>
		</h2>
<?php			if ($contributor['title']): ?>
		<p>
			<em title="Job Title"><?= $contributor['title'] ?></em>
		</p>
<?php			endif; ?>

<?php			if ($contributor['text']): ?>
		<p>
			<?= $contributor['text'] ?>
		</p>
<?php			endif; ?>
	</div>
<?php		endforeach; ?>
<?php	else: ?>
	<p>
		No Contributor registered yet!
	</p>
<?php	endif; ?>
	<p>
		<a href="<?= $resource['url_contributor'] ?>">I am a Contributor</a>
	</p>
</div>
<hr class="clear" />

<?php	if ($resource['type'] == 0): ?>

<h3 class="purple">Releases</h3>

<?php		if (count($resource['releases']) ): ?>
<?php			foreach ($resource['releases'] as $release): ?>
	<ul>
		<h2>
			<a href="<?= $release['url'] ?>"><?= $release['version'] ?></a>
		</h2>
		<a href="<?= $release['url_edit'] ?>" class="right">Edit</a>

<?php				if ($release['text']): ?>
		<p>
			<?= $release['text'] ?>
		</p>
<?php				endif; ?>
	</div>
<?php			endforeach; ?>
<?php		else: ?>
	<p>
		No Releases yet!
	</p>
<?php		endif; ?>
	<p>
		<a href="<?= $resource['url_releases'] ?>">Add Release</a>
	</p>
<?php	endif; ?>