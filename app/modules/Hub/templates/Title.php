<div class="resource">
	<small class="subheader">
		Updated <span class="date" title="<?= $resource['updated_at'] ?>"><?= RedDate::prettyDate($resource['updated_at']) ?></span>.
<?php		if (count($resource['licences'])): ?>
<?php			foreach ($resource['licences'] as $license): ?>
		<a href="<?= $license['url'] ?>" class="license"><?= $license['title'] ?></a>
<?php			endforeach; ?>
<?php		endif; ?>
	</small>
	<h3>
		<a href="<?= $resource['url_view'] ?>" class="bookmark"><?= $resource['title'] ?></a>
	</h3>

	<div class="text">
		<?= $resource['description_html'] ?>
	</div>

<?php		if (count($resource['tags'])): ?>
		<ul class="tags">
<?php			foreach ($resource['tags'] as $val => $tag): ?>
			<li><a href="<?= $tag['url_resources'] ?>"><?= $tag['word'] ?></a></li>
<?php			endforeach; ?>
		</ul>
<?php		endif; ?>
</div>