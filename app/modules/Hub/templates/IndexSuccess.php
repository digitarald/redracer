<?php	if (!count($resources) ): ?>
<p class="empty">
	No resources match your criteria. Refine your search or <a href="<?= $rt->gen('resources.index') ?>">browse</a> all available resources.
</p>
<?php	endif; ?>

<?php foreach ($resources as $resource): ?>

<?php		require('Title.php'); ?>

<?php endforeach; ?>