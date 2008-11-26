<form action="<?= $rt->gen(null) ?>" method="post" id="form-edit">

	<fieldset>
		<legend>OpenID</legend>

		<p>
			The following profile is imported from your <a href="<?= $user['user_auths'][0]['identifier'] ?>">OpenID identity</a>.
		</p>

		<label class="field">
			<span class="label">Nickname:</span>
			<input type="text" name="nickname" />
		</label>
		<label class="field">
			<span class="label">E-Mail:</span>
			<input type="text" name="email" />
		</label>
		<label class="field">
			<span class="label">Fullname:</span>
			<input type="text" name="fullname" />
		</label>
		<label class="field">
			<span class="label">Birthday:</span>
			<input type="text" name="dob" />
		</label>
		<label class="field">
			<span class="label">Country:</span>
			<input type="text" name="country" />
		</label>
		<label class="field">
			<span class="label">Postcode:</span>
			<input type="text" name="postcode" />
		</label>
		<label class="field">
			<span class="label">Language:</span>
			<input type="text" name="language" />
		</label>
		<label class="field">
			<span class="label">Timezone:</span>
			<input type="text" name="timezone" />
		</label>
	</fieldset>

	<fieldset>
		<legend>Additional data Profile Sugar</legend>

		<p>
			Add more profile data here, to gain coolness.
		</p>

		<label class="field">
			<span class="label">Github username:</span>
			<input type="text" name="github_user" size="25" maxlength="64" class="text" />
			<span class="hints">Your <a href="http://github.com/">github</a> username, to track commits.</span>
		</label>

		<label class="field">
			<span class="label">Paypal Email address:</span>
			<input type="text" name="paypal_user" size="25" maxlength="64" class="text" />
			<span class="hints">Your <a href="https://www.paypal.com/">paypal</a> email address, so people can donate for your contribution.</span>
		</label>

	</fieldset>

	<fieldset class="footer">
		<input type="submit" value="Save Changes" class="submit" />
		or <a href="<?= $rt->gen('index') ?>">Cancel</a>
	</fieldset>

</form>