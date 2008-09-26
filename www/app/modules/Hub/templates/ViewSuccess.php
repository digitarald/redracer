<h3 class="green"><?= $resource['title'] ?></h3>

<div class="entry">
	<?= $resource['text_html'] ?>
</div>

<hr />

<div class="block span-8 colborder">
	<h3 class="blue">Links</h3>

<?php	if (count($resource['links']) ): ?>
<?php		foreach ($resource['links'] as $link): ?>
	<div>
		<h4><a href="<?= $link['url'] ?>"><?= $link['title'] ?></a></h4>
		<a href="<?= $link['url_edit'] ?>" class="right">Edit</a>
<?php			if ($link['text']): ?>
		<p><?= $link['text'] ?></p>
<?php			endif; ?>
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

<?php	if ($resource['unclaimed']): ?>
	<p>
		This Resource was not submitted by the author, it needs to be claimed.
		If you are one of the contributors, <a href="<?= $resource['claim'] ?>">claim it</a> now!
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
			<a href="<?= $contributor['url'] ?>" title="View Profile"><?= $contributor['user']['fullname'] ?></a>
			<span>(<?= $contributor['user']['nickname'] ?>)</span>
		</h2>
		<a href="<?= $contributor['url_edit'] ?>" class="right">Edit</a>
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
		<a href="<?= $resource['url_contributor'] ?>">Add Contributor</a>
	</p>
</div>
<hr class="clear" />

<h3 class="purple">Discussion</h3>