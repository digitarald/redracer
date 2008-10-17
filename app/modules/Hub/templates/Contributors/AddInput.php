<h3 class="red"><?= $title ?></h3>
<form action="<?= $rt->gen(null) ?>" method="post" id="form-edit">
	<fieldset>
		<legend>Confirm Adding</legend>

		<p>Do you want to add yourself as contributor to “<?= $resource['title'] ?>”?</p>

	</fieldset>

	<fieldset class="footer">

		<input type="submit" value="Confirm Adding" class="submit" />
		or <a href="<?= $resource['url'] ?>">Cancel</a>

	</fieldset>
</form>