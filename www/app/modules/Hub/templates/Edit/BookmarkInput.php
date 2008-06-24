<form action="<?= $rt->gen(null) ?>" method="post" id="form-edit">
	<fieldset>
		<legend>General</legend>

		<label>
			<span class="title">Title</span>
			<input type="text" name="title" size="50" maxlength="50" />
			<span class="description">Max. 50 characters</span>
		</label>

		<label>
			<span class="label">Unique Identifier</span>
			<input type="text" name="ident" size="32" maxlength="32" />
			<span class="small">Max. 32 characters</span>
		</label>

		<label>
			<span class="label">Text</span>
			<textarea rows="10" cols="50"></textarea>
		</label>

		<div class="buttons">
			<input type="submit" value="Submit" class="submit" />
		</div>

	</fieldset>
</form>