<h2>Editing „<?= $resource['title'] ?>“</h2>
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

		<label>
			<span class="label">Full Description:</span>
			<textarea name="text" rows="5" cols="50"></textarea>
			<span class="small">{markdown}</span>
		</label>

	</fieldset>

	<fieldset>
		<legend>Contributors</legend>

<?php	foreach ($resource['contributors'] as $id => $contributor): ?>
		<div class="span-6 first">
			<a href="<?= $contributor['user']['url'] ?>"><?= $contributor['user']['fullname'] ?></a> (<a href="#">Remove</a>)
		</div>
		<div class="span-10 last">
			<label>
				<input type="text" name="contributor[<?= $id ?>][title]" size="50" maxlength="50" />
				<span class="small">Job Title</span>
			</label>
			<label>
				<textarea name="contributor[<?= $id ?>][text]" rows="2" cols="40"></textarea>
				<span class="small">Job Description</span>
			</label>
		</div>
<?php	endforeach; ?>

		<label>
			<span class="label">Add New Contributor:</span>
			<input type="text" name="contributor_add" size="32" maxlength="32" />
			<span class="small">Search registered members by fullname or nickname.</span>
		</label>

		<ul>
			<li><a href="people/Harald_Kirschner">Harald Kirschner</a></li>
			<li><a href="people/Tom_Occhino">Add Tom Occhino</a></li>
		</ul>

	</fieldset>

	<fieldset>

		<div class="buttons">
			<input type="submit" value="Save Changes" class="submit" />
			or <a href="<?= $url ?>">Cancel</a>
		</div>

	</fieldset>
</form>