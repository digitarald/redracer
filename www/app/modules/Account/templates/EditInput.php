<form action="<?= $rt->gen(null) ?>" method="post" id="form-edit">
	<div class="span-8 first">
		<fieldset>
			<legend>OpenID</legend>

			<p>
				The following data is read from your OpenID identity.
			</p>

			<label>
				<span>Nickname:</span>
				<input type="text" name="nickname" readonly="readonly" />
			</label>
			<label>
				<span>E-Mail:</span>
				<input type="text" name="email" readonly="readonly" />
			</label>
			<label>
				<span>Fullname:</span>
				<input type="text" name="fullname" readonly="readonly" />
			</label>
			<label>
				<span>Birthday:</span>
				<input type="text" name="dob" readonly="readonly" />
			</label>
			<label>
				<span>Country:</span>
				<input type="text" name="country" readonly="readonly" />
			</label>
			<label>
				<span>Postcode:</span>
				<input type="text" name="postcode" readonly="readonly" />
			</label>
			<label>
				<span>Language:</span>
				<input type="text" name="language" readonly="readonly" />
			</label>
			<label>
				<span>Timezone:</span>
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

		</fieldset>
	</div>

</form>