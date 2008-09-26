<h3 class="red">Editing „<?= $resource['title'] ?>“</h3>
<form action="<?= $rt->gen(null) ?>" method="post" id="form-edit">
	<fieldset>
		<legend>Basics</legend>

		<label>
			<span class="label">Full Title:</span>
			<input type="text" name="title" size="50" maxlength="50" />
			<span class="small">Max. 50 characters</span>
		</label>

		<label>
			<span class="label">Short Name:</span>
			<input type="text" name="ident" size="32" maxlength="32" readonly="readonly" />
			<span class="small">Unique Identifier, max. 32 characters</span>
		</label>

		<label>
			<span class="label">Licence:</span>
			<input type="text" name="licence_text" size="50" maxlength="255" />
			<span class="small">Name</span>
		</label>
		<label>
			<input type="text" name="licence_url" size="50" maxlength="255" />
			<span class="small">URL</span>
		</label>

		<div class="label">
			<span class="label">Tags:</span>
<?php	foreach ($tags as $tag): ?>
			<label class="radio">
				<input type="checkbox" name="tag[<?= $tag['id'] ?>]" value="1" />
				<span class="label"><?= $tag['word'] ?></span>
			</label>
<?php	endforeach; ?>
		</div>

		<label>
			<span class="label">Full Description:</span>
			<textarea name="text" class="description" rows="5" cols="50"></textarea>
			<span class="small">{markdown}</span>
		</label>

	</fieldset>

	<fieldset>

		<div class="buttons">
			<input type="submit" value="Save Changes" class="submit" />
			or <a href="<?= $url ?>">Cancel</a>
		</div>

	</fieldset>
</form>