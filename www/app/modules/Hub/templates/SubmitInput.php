<form action="<?= $rt->gen(null) ?>" method="post" id="form-edit">
	<fieldset>
		<legend>Submit a Resource</legend>

		<label>
			<span class="label">Full Title:</span>
			<input type="text" name="title" size="50" maxlength="50" />
			<span class="small">Max. 50 characters</span>
		</label>

		<label>
			<span class="label">Short Name:</span>
			<input type="text" name="ident" size="32" maxlength="32" />
			<span class="small">Unique Identifier, max. 32 characters</span>
		</label>

		<label>
			<span class="label">Type:</span>
			<select name="type">
				<option value="0">Project</option>
				<option value="1">Article</option>
			</select>
		</label>

		<div class="label">
			<span class="label">Authorship:</span>
			<label for="fe-authorship-1" class="radio">
				<input type="radio" name="authorship" id="fe-authorship-1" value="1" />
				<span class="label">I am the Author</span>
			</label>
			<label for="fe-authorship-0" class="radio">
				<input type="radio" name="authorship" id="fe-authorship-0" value="1" />
				<span class="label">The Author is not me, but:</span>
			</label>
			<label>
				<input type="text" name="author" size="50" maxlength="255" />
				<span class="small">Please provide the full name, he can later claim his project.</span>
			</label>
		</div>

		<label>
			<span class="label">Full Description:</span>
			<textarea name="text" class="description" rows="5" cols="50"></textarea>
			<span class="small">{markdown}</span>
		</label>

		<label>
			<span class="label">Homepage:</span>
			<input type="text" name="homepage" size="50" maxlength="255" value="http://" />
			<span class="small">You can add additional links after submitting the resource.</span>
		</label>

		<p>
			You can add further information like description afterwards.
		</p>

		<div class="buttons">
			<input type="submit" value="Submit" class="submit" />
		</div>

	</fieldset>
</form>