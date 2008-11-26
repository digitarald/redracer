<h3 class="red"><?= $title ?></h3>
<form action="<?= $rt->gen(null) ?>" method="post" id="form-edit" class="styled">
	<fieldset>

		<label class="field required">
			<span class="label">URL</span>
			<span class="hints">The full URL for this link</span>
			<input type="text" name="url" size="50" maxlength="250" class="text" />
		</label>

		<label class="field required">
			<span class="label">Title</span>
			<span class="hints">Something descriptive in 50 characters</span>
			<input type="text" name="title" size="50" maxlength="250" class="text" />
		</label>

		<label class="field">
			<span class="label">Description</span>
			<span class="hints">
				<a href="http://daringfireball.net/projects/markdown/syntax">Markdown Syntax</a> enabled
			</span>
			<textarea name="description" rows="5" cols="50" class="maxlength(500)"></textarea>
		</label>

	</fieldset>

	<fieldset class="footer">
<?php	if (isset($link)): ?>
		<div>
			<a href="<?= $rt->gen('resources.resource.links.link.delete') ?>">Delete</a>
		</div>

		<input type="submit" value="Save Changes" class="submit" />
		or <a href="<?= $rt->gen('resources.resource.view') ?>">Cancel</a>
<?php	else: ?>
		<p>Do you want to add this link “<?= $resource['title'] ?>”?</p>

		<input type="submit" value="Confirm Adding" class="submit" />
		or <a href="<?= $rt->gen('resources.resource.view') ?>">Cancel</a>
<?php	endif; ?>

	</fieldset>
</form>