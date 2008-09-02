<form action="<?= $rt->gen('account.login') ?>" method="post" id="form-login">
	<fieldset>
		<label>
			<span class="label">Your OpenID:</span>
			<input type="text" name="openid_identity" id="login-openid_identity" value="http://" class="text openid" />
			<span class="small">
				Examples:<br />
				http://claimid.com/username<br />
				http://username.myopenid.com
			</span>
		</label>
		<label>
			<span class="label">Remember Login:</span>
			<input type="checkbox" name="login_remember" id="login-login_remember" value="1" checked="checked" />
			<span class="small">Saved for <?= AgaviConfig::get('core.remember_expire') ?>.</span>
		</label>
	</fieldset>
	<fieldset class="footer">
		<input type="hidden" name="login_from" value="<?= $rq->getUrl() ?>" />
		<input type="submit" value="Log in via OpenID" />
	</fieldset>
</form>