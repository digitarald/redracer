<h3 class="red"><?= $title ?></h3>
<form action="<?= $rt->gen(null) ?>" method="post" id="form-delete" class="styled">
	<fieldset class="footer">

		<p>Do you really want to delete “<?= $release['version'] ?>” from the list of releases?</p>
		<input type="submit" value="Confirm Deletion" class="submit" />
		or <a href="<?= $rt->gen('resources.resource.releases.release.edit') ?>">Cancel</a>

	</fieldset>
</form>