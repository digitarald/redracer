<h3 class="red"><?= $title ?></h3>
<form action="<?= $rt->gen(null) ?>" method="post" id="form-edit">
	<fieldset>
		<legend>Link Information</legend>

		<label>
			<span class="label">Title:</span>
			<input type="text" name="title" size="50" maxlength="50" />
			<span class="small">Max. 50 characters</span>
		</label>

		<label>
			<span class="label">URL:</span>
			<input type="text" name="url" size="50" maxlength="255" value="http://" />
		</label>

		<label>
			<span class="label">Description:</span>
			<textarea name="text" rows="5" cols="50"></textarea>
			<span class="small">Max. 500 characters</span>
		</label>

		<label>
			<span class="label">Priority:</span>
			<select name="priority">
				<option value="0">High - Official</option>
				<option value="1" selected="selected">Normal - Community</option>
				<option value="2">Low - Individual</option>
			</select>
			<span class="small">For the ranking on the project page.</span>
		</label>

	</fieldset>

	<fieldset>

		<div class="alignright">
			<a href="<?= $url_delete ?>">Delete</a>
		</div>

		<div class="buttons">
			<input type="submit" value="Save Changes" class="submit" />
			or <a href="<?= $url ?>">Cancel</a>
		</div>

	</fieldset>
</form>