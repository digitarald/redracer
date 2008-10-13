<?php	if (!count($resources) ): ?>
<p class="empty">
	No resources match your criteria. Refine your search or <a href="<?= $rt->gen('resources.index') ?>">browse</a> all available resources.
</p>
<?php	endif; ?>

<?php foreach ($resources as $resource): ?>
<div class="resource">
	<small class="subheader">
		Updated <?= RedDate::prettyDate($resource['updated_at']) ?>.
<?php		if ($resource['license_text'] && $resource['license_url']): ?>
		<a href="<?= $resource['license_url'] ?>" class="license"><?= $resource['license_text'] ?></a>
<?php		else: ?>
		Unclassified License
<?php		endif; ?>
	</small>
	<h3>
		<a href="<?= $resource['url'] ?>" class="bookmark"><?= $resource['title'] ?></a>
	</h3>

	<div class="text">
		<?= $resource['text_intro'] ?>

		<p class="footer">
<?php		if (count($resource['tags']) ):
				$start = 0; ?>
		Tagged as
<?php			foreach ($resource['tags'] as $val => $tag): ?>
			<?= ($start++) ? ', ' : '' ?><a href="<?= $tag['url'] ?>"><?= $tag['word_clear'] ?></a>
<?php			endforeach; ?>.
<?php		else: ?>
		Untagged.
<?php		endif; ?>

<?php		if ($resource['core_min'] && $resource['core_max']): ?>
		MooTools <a href="http://mootools.net/download"><?= $resource['core_min'] ?> - <?= $resource['core_max'] ?></a>
<?php		elseif ($resource['core_min']): ?>
		MooTools <a href="http://mootools.net/download"><?= $resource['core_min'] ?> +</a>
<?php		endif; ?>
		</p>
	</div>

</div>
<?php endforeach; ?>