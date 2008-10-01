<?php	if (!count($resources) ): ?>
<p class="empty">
	No resources match your criteria. Refine your search or <a href="<?= $rt->gen('hub.index') ?>">browse</a> all available resources.
</p>
<?php	endif; ?>

<?php foreach ($resources as $resource): ?>
<div>
	<small class="subheader">
		Updated <?= OurDate::prettyDate($resource['updated_at']) ?>.
<?php		if ($resource['license_text'] && $resource['license_url']): ?>
		<a href="<?= $resource['license_url'] ?>" class="license"><?= $resource['license_text'] ?></a> .
<?php		else: ?>
		Unclassified License .
<?php		endif; ?>
<?php		if (count($resource['tags']) ): ?>
		Tagged as
<?php			foreach ($resource['tags'] as $tag): ?>
			<a href="<?= $tag['url'] ?>"><?= $tag['word_clear'] ?></a>
<?php			endforeach; ?>.
<?php		endif; ?>
<?php		if ($resource['core_min'] && $resource['core_max']): ?>
		<a href="http://mootools.net/download/">MooTools <?= $resource['core_min'] ?> - <?= $resource['core_max'] ?></a>
<?php		elseif ($resource['core_min']): ?>
		<a href="http://mootools.net/download/">MooTools <?= $resource['core_min'] ?> +</a>
<?php		endif; ?>
	</small>
	<h3>
		<a href="<?= $resource['url'] ?>" class="bookmark"><?= $resource['title'] ?></a>
	</h3>

	<div class="entry">
		<?= $resource['text_intro'] ?>
	</div>

</div>
<?php endforeach; ?>