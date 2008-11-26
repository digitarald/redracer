<h3 class="red"><?= $title ?></h3>
<form action="<?= $rt->gen(null) ?>" method="post" id="form-edit" class="styled">
	<fieldset>
		<label class="field">
			<span class="label">Role</span>
			<select name="role">
<?php	foreach (Contributor::$roles as $idx => $role): ?>
				<option value="<?= $idx ?>"><?= $role ?></option>
<?php	endforeach; ?>
			</select>
			<span class="hints">Choose one maintainer position (ordered by importance).</span>
		</label>
	</fieldset>

	<fieldset class="footer">
<?php	if (isset($contributor)): ?>
		<div>
			<a href="<?= $rt->gen('resources.resource.contributors.contributor.delete') ?>">Delete</a>
		</div>

		<input type="submit" value="Save Changes" class="submit" />
		or <a href="<?= $rt->gen('resources.resource.view') ?>">Cancel</a>
<?php	else: ?>
		<p>Do you want to add yourself as contributor to “<?= $resource['title'] ?>”?</p>

		<input type="submit" value="Confirm Adding" class="submit" />
		or <a href="<?= $rt->gen('resources.resource.view') ?>">Cancel</a>
<?php	endif; ?>

	</fieldset>
</form>