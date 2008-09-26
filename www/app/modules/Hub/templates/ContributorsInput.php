<h2>Editing Contributors „<?= $resource['title'] ?>“</h2>

<?php	foreach ($contributors as $id => $contributor): ?>
	<form action="<?= $rt->gen(null) ?>" method="post">
		<div class="span-6 first">
			<input type="hidden" name="id" value="id" />
			<a href="<?= $contributor['user']['url'] ?>"><?= $contributor['user']['fullname'] ?></a>
		</div>
		<div class="span-10 last">
			<label>
				<input type="text" name="title" size="50" maxlength="50" />
				<span class="small">Job Title</span>
			</label>
			<label>
				<textarea name="text" rows="2" cols="40"></textarea>
				<span class="small">Job Description</span>
			</label>
		</div>

		<div class="buttons">
			<input type="submit" value="Save Changes" class="submit" />
			or <a href="<?= $rt->gen(null, array('delete' => $id) ) ?>">Delete</a>
		</div>

	</form>
<?php	endforeach; ?>

<fieldset>
	<legend>Add Contributor</legend>

	<label>
		<span class="label">Search:</span>
		<input type="text" name="contributor_add" size="32" maxlength="32" />
		<span class="small">Search registered members by fullname or nickname.</span>
	</label>

	<ul>
		<li><a href="people/Harald_Kirschner">Harald Kirschner</a></li>
		<li><a href="people/Tom_Occhino">Add Tom Occhino</a></li>
	</ul>

</fieldset>