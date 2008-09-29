<?php	if (!count($resources) ): ?>
<p>
	No resources match your criteria. Refine your search or <a href="<?= $rt->gen('hub.index') ?>">browse</a> all available resources.
</p>
<?php	endif; ?>

<?php foreach ($resources as $resource): ?>
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
	</small>

	<div class="entry">
		<?= $resource['text_intro'] ?>
	</div>

	<p class="postmetadata">
<?php		if (count($resource['tags']) ): ?>
		Tagged as
<?php			foreach ($resource['tags'] as $tag): ?>
			<a href="<?= $tag['url'] ?>"><?= $tag['word_clear'] ?></a>
<?php			endforeach; ?>
<?php		else: ?>
		Untagged.
<?php		endif; ?>

		<a href="<?= $resource['url'] ?>#comments"><?= count($resource['comments']) ?> Comments</a>
	</p>
</div>
<?php endforeach; ?>