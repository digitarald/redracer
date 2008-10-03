<h3 class="red">Editing „<?= $resource['title'] ?>“</h3>
<form action="<?= $rt->gen(null) ?>" method="post" id="form-edit">
	<fieldset>
		<h3 class="green">Basic</h3>

		<label class="required">
			<span class="label">Full Title:</span>
			<input type="text" name="title" size="50" maxlength="50" />
			<span class="small">Max. 50 characters</span>
		</label>

		<label class="required">
			<span class="label">Short Name:</span>
			<input type="text" name="ident" size="32" maxlength="32" readonly="readonly" />
			<span class="small">Unique Identifier, max. 32 characters</span>
		</label>

		<label class="required">
			<span class="label">Type:</span>
			<select name="type">
				<option value="0">Project</option>
				<option value="1">Article</option>
				<option value="2">Code Snippet</option>
			</select>
		</label>

		<div class="label required">
			<span class="label">MooTools version:</span>
			<div class="span-3">
				<label>
					<select name="core_min">
						<option value="">-</option>
						<option value="1.00">1.0</option>
						<option value="1.10">1.10</option>
						<option value="1.11">1.11</option>
						<option value="1.2b1">1.2b1</option>
						<option value="1.2b2">1.2b2</option>
						<option value="1.2">1.2</option>
						<option value="1.2wip">1.2wip</option>
					</select>
					<span class="small">Minimal ...</span>
				</label>
			</div>
			<div class="span-3 last">
				<label>
					<select name="core_max">
						<option value="">-</option>
						<option value="1.00">1.0</option>
						<option value="1.10">1.10</option>
						<option value="1.11">1.11</option>
						<option value="1.2b1">1.2b1</option>
						<option value="1.2b2">1.2b2</option>
						<option value="1.2">1.2</option>
						<option value="1.2wip">1.2wip</option>
					</select>
					<span class="small">... Maximal</span>
				</label>
			</div>
		</div>

		<label>
			<span class="label">Claimed:</span>
			<input type="checkbox" name="claimed" value="1" />
		</label>

		<label>
			<span class="label">Copyright Owner:</span>
			<input type="text" name="author" size="50" maxlength="255" />
			<span class="small">Please provide the full name, if it is not a registered contributor!</span>
		</label>

		<label>
			<span class="label">License:</span>
			<select name="license">
				<option value="">...</option>
<?php	foreach ($licenses as $var => $val): ?>
				<option value="<?= $val ?>"><?= $var ?></option>
<?php	endforeach; ?>
			</select>
			<span class="small">Choose an available license (<a href="http://www.opensource.org/licenses/category">Open Source Licenses</a>), or create your own ...</span>
		</label>
		<label>
			<input type="text" name="license_text" size="50" maxlength="255" />
			<span class="small">Name</span>
		</label>
		<label>
			<input type="text" name="license_url" size="50" maxlength="255" />
			<span class="small">URL</span>
		</label>

		<div class="label required">
			<span class="label">Tags:</span>
<?php	foreach ($tags as $tag): ?>
			<label class="radio">
				<input type="checkbox" name="tag_ids[]" value="<?= $tag['id'] ?>" />
				<span class="label"><?= $tag['word_clear'] ?></span>
			</label>
<?php	endforeach; ?>
		</div>

		<label class="required">
			<span class="label">Body:</span>
			<textarea name="text" class="description" rows="5" cols="50"></textarea>
			<span class="small"><a href="http://daringfireball.net/projects/markdown/syntax">Markdown Syntax</a></span>
		</label>
	</fieldset>

	<fieldset>
		<legend>Project</legend>

		<label>
			<span class="label">Feed URL:</span>
			<input type="text" name="url_feed" size="50" maxlength="255" />
			<span class="small">RSS-feed for changelog or news.</span>
		</label>

		<label>
			<span class="label">Repository URL:</span>
			<input type="text" name="url_repository" size="50" maxlength="255" />
			<span class="small">
				<a href="http://github.com/">github</a> preferred
				(<em>http://github.com/username/my-plugin</em> or with subfolder <em>http://github.com/username/plugins/tree/master/my-plugin</em>)
			</span>
		</label>
	</fieldset>

	<fieldset class="footer">
		<div>
			<a href="<?= $resource['url_link'] ?>">Add Link</a>,
			<a href="<?= $resource['url_releases'] ?>">Add Release</a>
		</div>

		<input type="submit" value="Save Changes" class="submit" />
		or <a href="<?= $url ?>">Cancel</a>
	</fieldset>
</form>