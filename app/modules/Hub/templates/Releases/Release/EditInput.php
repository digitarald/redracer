<h3 class="red"><?= $title ?></h3>
<form action="<?= $rt->gen(null) ?>" method="post" id="form-edit" class="styled">
	<fieldset>

		<label class="field required">
			<span class="label">Version identifier</span>
			<span class="hints">
				<em>major.minor[.maintenance[.build]]</em>, keep it consistent with previous releases!
				<strong>Avoid adding</strong> an extra stability identifier (<em>beta</em>, <em>dev</em>, etc), use the following field.
			</span>
			<input type="text" name="version" size="10" maxlength="10" class="text custom" />
		</label>

		<label class="field">
			<span class="label">Stability</span>
			<select name="stability">
<?php	foreach (Resource::$stabilities as $idx => $text): ?>
				<option value="<?= $idx ?>"><?= $text ?></option>
<?php	endforeach; ?>
			</select>
		</label>

		<label class="field">
			<input type="checkbox" name="recommended" value="1" />
			<span class="label choice">Recommended Release</span>
			<span class="hints">There should be one (and can be only one) recommended release.</span>
		</label>

		<label class="field">
			<span class="label">Repository/Source URL</span>
			<span class="hints">
				URL to the source repository, if available. To load dependencies, it should have a <em>Source</em> directory and must have a <em>scripts.json</em>.<br />
				<em>Examples:</em><br />
				<a href="http://github.com/mootools/mootools-more/tree/master">http://github.com/mootools/mootools-more/tree/master</a><br />
				<a href="http://www.clientcide.com/cnet.gf/svn/">http://www.clientcide.com/cnet.gf/svn/</a><br />
			</span>
			<input type="text" name="url_source" size="50" maxlength="250" class="text" />
		</label>

		<label class="field">
			<span class="label">Download URL</span>
			<span class="hints">Only shown if you don't add files.</span>
			<input type="text" name="url_download" size="50" maxlength="250" class="text" />
		</label>

		<label class="field">
			<span class="label">Announcement URL</span>
			<span class="hints">Official announcement page.</span>
			<input type="text" name="url_notes" size="50" maxlength="250" class="text" />
		</label>

		<label class="field">
			<span class="label">Release Date</span>
			<span class="hints">In <a href="http://en.wikipedia.org/wiki/ISO_8601">ISO-8601</a> date format (<em>YYYY-MM-DD</em>).</span>
			<input type="text" name="released_at" size="10" maxlength="10" class="text custom date" />
		</label>

		<label class="field">
			<span class="label">Notes</span>
			<span class="hints">
				<a href="http://daringfireball.net/projects/markdown/syntax">Markdown Syntax</a> enabled
			</span>
			<textarea name="notes" rows="5" cols="50" class="maxlength(5000)"></textarea>
		</label>

		<label class="field">
			<span class="label">Changelog</span>
			<span class="hints">
				<a href="http://daringfireball.net/projects/markdown/syntax">Markdown Syntax</a> enabled
			</span>
			<textarea name="changelog" rows="5" cols="50" class="maxlength(5000)"></textarea>
		</label>

	</fieldset>

	<fieldset class="footer">
<?php	if (isset($release)): ?>
		<div>
			<a href="<?= $rt->gen('resources.resource.releases.release.edit', array('do' => 'import')) ?>">Import Files</a>
			<a href="<?= $rt->gen('resources.resource.releases.release.edit', array('do' => 'resolve')) ?>">Import Dependencies</a>
			<a href="<?= $rt->gen('resources.resource.releases.release.delete') ?>">Delete</a>
		</div>

		<input type="submit" value="Save Changes" class="submit" />
		or <a href="<?= $rt->gen('resources.resource.releases.release.view') ?>">Cancel</a>
<?php	else: ?>
		<p>Do you want to add this release “<?= $resource['title'] ?>”?</p>

		<input type="submit" value="Confirm Adding" class="submit" />
		or <a href="<?= $rt->gen('resources.resource.view') ?>">Cancel</a>
<?php	endif; ?>

	</fieldset>
</form>