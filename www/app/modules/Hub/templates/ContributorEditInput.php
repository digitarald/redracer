<h3 class="red"><?= $title ?></h3>
<form action="<?= $rt->gen(null) ?>" method="post" id="form-edit">
	<fieldset>
		<legend>Contributor Information</legend>

		<label>
			<span class="label">Position Title:</span>
			<input type="text" name="title" size="50" maxlength="50" />
		</label>

		<label>
			<span class="label">Description:</span>
			<textarea name="text" rows="5" cols="50"></textarea>
			<span class="small">Max. 500 characters</span>
		</label>

	</fieldset>

	<fieldset>
<?php	if ($url_delete): ?>
		<div class="alignright">
			<a href="<?= $url_delete ?>">Delete</a>
		</div>
<?php	endif; ?>

		<input type="submit" value="Save Changes" class="submit" />
		or <a href="<?= $url ?>">Cancel</a>

	</fieldset>
</form>