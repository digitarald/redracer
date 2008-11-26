<h3 class="red"><?= $title ?></h3>
<form action="<?= $rt->gen(null) ?>" method="post" id="form-edit" class="styled">
	<fieldset>
		<h3 class="green">Basic</h3>

		<label class="field required">
			<span class="label">Title</span>
			<span class="hints">
				Be descriptive, technical correct and creative (in max. 50 characters)!<br />
				<strong>Don't</strong> include words like <em>MooTools</em>, <em>Extension</em> or other <em title="All of these resources are more or less MooTools extensions!">obvious</em> words.
			</span>
			<input type="text" name="title" size="50" maxlength="50" class="text project-title" />
		</label>

		<label class="field required">
			<span class="label">Unique Identifier</span>
			<span class="hints">
				Short name for URIs (only letters, digits and -). If an ident is already taken, prepend your nickname or organization.<br />
				<strong>Good</strong>: <em>element-unlink</em>, <em>fx-explode</em>, <em>fluid-gallery</em>, <em>swiff-sound</em><br />
				<strong>Bad</strong>: <em title="Be descriptive">cool-element-extensions</em>, <em title="Be descriptive">my-mootools-plugins</em>, <em title="Avoid version numbers!">pagination-1-2</em>
			</span>
			<input type="text" name="ident" size="30" maxlength="30" <?php	if (isset($resource)): ?>readonly="readonly"<?php endif; ?> class="text custom project-ident" />
		</label>

		<label class="field required">
			<span class="label">Category</span>
			<span class="hints">
				Projects have releases and tickets, snippets and articles are only text and code.
			</span>
			<select name="category">
<?php	foreach (Resource::$categories as $idx => $text): ?>
				<option value="<?= $idx ?>"><?= $text ?></option>
<?php	endforeach; ?>
			</select>
		</label>

		<label class="field">
			<span class="label">Copyright Owner</span>
			<span class="hints">The full name of the author or the organisation, don't add the year.</span>
			<input type="text" name="copyright" size="50" maxlength="50" class="text" />
		</label>

		<div class="field">
			<span class="label">Licences</span>
			<span class="hints">Select at least one open-source licence.</span>
			<select name="licence_ids[]" multiple="multiple">
<?php	foreach ($licences as $licence): ?>
				<option value="<?= $licence['id'] ?>"><?= $licence['title'] ?></option>
<?php	endforeach; ?>
			</select>
		</div>

		<div class="field required">
			<span class="label">Tags</span>
			<span class="hints">Select one or more keyword that describe this project.</span>
			<select name="tag_ids[]" multiple="multiple">
<?php	foreach ($tags as $tag): ?>
				<option value="<?= $tag['id'] ?>"><?= $tag['word'] ?></option>
<?php	endforeach; ?>
			</select>
		</div>

		<label class="field required">
			<span class="label">Short Description</span>
			<span class="hints">
				<a href="http://daringfireball.net/projects/markdown/syntax">Markdown Syntax</a> enabled
			</span>
			<textarea name="description" rows="5" cols="50" class="maxlength(500)"></textarea>
		</label>

		<label class="field required">
			<span class="label">Essay</span>
			<span class="hints">
				About documentation, installation, example code, FAQ, WTF, etc.
				<a href="http://daringfireball.net/projects/markdown/syntax">Markdown Syntax</a> enabled.
			</span>
			<textarea name="readme" class="large_field" rows="5" cols="50"></textarea>
		</label>

	</fieldset>

	<fieldset class="for-projects">
		<h3 class="green">Project Details</h3>

		<label class="field">
			<span class="label">Stability</span>
			<span class="hints">Adding releases automatically override that entry.</span>
			<select name="stability">
<?php	foreach (Resource::$stabilities as $idx => $text): ?>
				<option value="<?= $idx ?>"><?= $text ?></option>
<?php	endforeach; ?>
			</select>
		</label>

		<label class="field">
			<span class="label">Homepage URL</span>
			<input type="text" name="url_homepage" size="50" maxlength="250" class="text" />
		</label>

		<label class="field">
			<span class="label">Download URL</span>
			<span class="hints">Only shown if the resource does not provide releases.</span>
			<input type="text" name="url_download" size="50" maxlength="250" class="text" />
		</label>

		<label class="field">
			<span class="label">Demo URL</span>
			<input type="text" name="url_demo" size="50" maxlength="250" class="text" />
		</label>

		<label class="field">
			<span class="label">Feed URL</span>
			<span class="hints">RSS-feed for changelog or news.</span>
			<input type="text" name="url_feed" size="50" maxlength="250" class="text" />
		</label>

		<label class="field">
			<span class="label">Repository/Source URL</span>
			<span class="hints">
				<a href="http://github.com/">github</a> preferred
				(<em>http://github.com/username/my-plugin</em> or with subfolder <em>http://github.com/username/plugins/tree/master/my-plugin</em>)
			</span>
			<input type="text" name="url_source" size="50" maxlength="250" class="text" />
		</label>

		<label class="field">
			<span class="label">Discussion URL</span>
			<span class="hints">If you don't want to use the local comment system, provide a URL to your forum or mailing list.</span>
			<input type="text" name="url_discussion" size="50" maxlength="250" class="text" />
		</label>

		<label class="field">
			<span class="label">Support URL</span>
			<span class="hints">If you don't want to use the local ticket system, provide a URL to your ticket system or contact form.</span>
			<input type="text" name="url_support" size="50" maxlength="250" class="text" />
		</label>

<?php	if ($us->hasCredential('resources.flag')): ?>
		<div class="field">
			<label class="label">Flags</label>
<?php		foreach (Resource::$flags as $idx => $text): ?>
			<input type="checkbox" name="flag_mask[]" id="flag_mask-<?= $idx ?>" value="<?= $idx ?>" />
			<label class="label choice" for="flag_mask-<?= $idx ?>"><?= $text ?></label><br />
<?php		endforeach; ?>
		</div>
<?php	endif; ?>

	</fieldset>

<?php	if (!isset($resource)): ?>
	<fieldset>
		<h3 class="green">Before Submitting</h3>

		<label class="field">
			<input type="checkbox" name="manager" value="1" />
			<span class="label choice">I own or manage this project.</span>
		</label>

		<label class="field">
			<input type="checkbox" name="terms" value="1" />
			<span class="label choice">This resource is free and open-source (<a href="http://www.opensource.org/docs/osd">The Open Source Definition</a>).</span>
		</label>

	</fieldset>
<?php	endif; ?>

	<fieldset class="footer">
<?php	if (isset($resource)): ?>
		<input type="submit" value="Save Changes" class="submit" />
		or <a href="<?= $resource['url_view'] ?>">Cancel</a>
<?php	else: ?>
		<input type="submit" value="Submit new Resource" class="submit" />
		or <a href="<?= $rt->gen('account.index') ?>">Cancel</a>
<?php	endif; ?>
	</fieldset>
</form>