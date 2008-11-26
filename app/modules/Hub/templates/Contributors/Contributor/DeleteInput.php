<h3 class="red"><?= $title ?></h3>
<form action="<?= $rt->gen(null) ?>" method="post" id="form-delete" class="styled">
	<fieldset class="footer">

		<p>Do you really want to delete “<?= $contributor['user']['display_name'] ?>” from the list of contributors?</p>
		<input type="submit" value="Confirm Deletion" class="submit" />
		or <a href="<?= $rt->gen('resources.resource.contributors.contributor.edit') ?>">Cancel</a>

	</fieldset>
</form>