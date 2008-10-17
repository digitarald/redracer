<h3 class="red"><?= $title ?></h3>
<form action="<?= $rt->gen(null) ?>" method="post" id="form-delete">
	<fieldset>
		<legend>Confirm Deletion</legend>

		<p>Do you really want to delete “<?= $contributor['user']['display_name'] ?>” from the list of contributors?</p>
	</fieldset>

	<fieldset class="footer">
		<input type="submit" value="Confirm Deletion" class="submit" />
		or <a href="<?= $url ?>">Cancel</a>

	</fieldset>
</form>