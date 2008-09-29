<form action="<?= $rt->gen(null) ?>" method="post" id="form-edit">
	<div class="span-8 first">
		<fieldset>
			<legend>OpenID</legend>

			<p>
				The following data is imported from your OpenID identity.
				You have to change it in your <a href="<?= $data['open_ids'][0]['url'] ?>">OpenID account</a>.
			</p>

			<label>
				<span class="label">Nickname:</span>
				<input type="text" name="nickname" readonly="readonly" />
			</label>
			<label>
				<span class="label">E-Mail:</span>
				<input type="text" name="email" readonly="readonly" />
			</label>
			<label>
				<span class="label">Fullname:</span>
				<input type="text" name="fullname" readonly="readonly" />
			</label>
			<label>
				<span class="label">Birthday:</span>
				<input type="text" name="dob" readonly="readonly" />
			</label>
			<label>
				<span class="label">Country:</span>
				<input type="text" name="country" readonly="readonly" />
			</label>
			<label>
				<span class="label">Postcode:</span>
				<input type="text" name="postcode" readonly="readonly" />
			</label>
			<label>
				<span class="label">Language:</span>
				<input type="text" name="language" readonly="readonly" />
			</label>
			<label>
				<span class="label">Timezone:</span>
				<input type="text" name="timezone" readonly="readonly" />
			</label>
		</fieldset>
	</div>
	<div class="span-8 last">
		<fieldset>
			<legend>Profile Sugar</legend>

			<p>
				Add your data here to gain coolness.
			</p>

			<label>
				<span class="label">Github username:</span>
				<input type="text" name="github_user" size="25" maxlength="64" />
				<span class="small">Your <a href="http://github.com/">github</a> username, to track commits.</span>
			</label>

			<label>
				<span class="label">Paypal Email address:</span>
				<input type="text" name="paypal_user" size="25" maxlength="64" />
				<span class="small">Your <a href="https://www.paypal.com/">paypal</a> email address, so people can donate for your contribution.</span>
			</label>

		</fieldset>

		<fieldset class="footer">
			<input type="submit" value="Save Changes" class="submit" />
			or <a href="<?= $url ?>">Cancel</a>
		</fieldset>
	</div>

</form>