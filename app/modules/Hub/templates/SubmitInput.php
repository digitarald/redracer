<form action="<?= $rt->gen(null) ?>" method="post" id="form-edit">
	<fieldset>
		<legend>Submit a Resource</legend>

		<label class="required">
			<span class="label">Full Title:</span>
			<input type="text" name="title" size="50" maxlength="50" />
			<span class="small">
				Try to be descriptive, technical correct and creative (in max. 50 characters)!<br />
				<strong>Don't</strong> include words like <em>MooTools</em>, <em>Extension</em> or other <em title="All of these resources are more or less MooTools extensions!">obvious</em> words.
			</span>
		</label>

		<label class="required">
			<span class="label">Short Name:</span>
			<input type="text" name="ident" size="32" maxlength="32" />
			<span class="small">
				Unique Identifier for URIs (only letters, digits, - and _, max. 32 characters). If an ident is already taken, you can prepend your nickname.<br />
				<strong>Good</strong>: <em>element-unlink</em>, <em>fx-explode</em>, <em>fluid-gallery</em>, <em>swiff-sound</em><br />
				<strong>Bad</strong>: <em title="Be descriptive">cool-element_extensions</em>, <em title="Be descriptive">my-mootools-plugins</em>, <em title="Avoid version numbers!">pagination-1-2</em>
			</span>
		</label>

		<label class="required">
			<span class="label">Type:</span>
			<select name="type">
				<option value="0">Project</option>
				<option value="1">Article</option>
				<option value="2">Code Snippet</option>
			</select>
			<span class="small">
				You can change that later on, if your snippet grows to a full project.
			</span>
		</label>

		<div class="label">
			<label for="fe-authorship-1" class="radio">
				<input type="radio" name="authorship" id="fe-authorship-1" value="1" checked="checked" />
				<span class="label">I am the Author</span>
			</label>
			<label for="fe-authorship-0" class="radio">
				<input type="radio" name="authorship" id="fe-authorship-0" value="0" />
				<span class="label">The Author is not me, but:</span>
			</label>
			<label>
				<input type="text" name="author" size="50" maxlength="255" />
				<span class="small">Please provide the full name, he can later claim his project.</span>
			</label>
		</div>

		<p>
			When you submitted the resource, you can add more information like links and tags.
		</p>

		<div class="buttons">
			<input type="submit" value="Submit" class="submit" />
		</div>

	</fieldset>
</form>