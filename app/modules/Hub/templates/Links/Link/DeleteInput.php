<h3 class="red"><?= $title ?></h3>
<form action="<?= $rt->gen(null) ?>" method="post" id="form-delete" class="styled">
	<fieldset class="footer">

		<p>Do you really want to delete “<?= $link['title'] ?>” from the list of links?</p>
		<input type="submit" value="Confirm Deletion" class="submit" />
		or <a href="<?= $rt->gen('resources.resource.links.link.edit') ?>">Cancel</a>

	</fieldset>
</form>