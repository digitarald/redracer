<form action="<?= $rt->gen('account.login') ?>" method="post" id="form-login">
	<fieldset>
		<label>
			Your OpenID:
			<input name="openid_identity" id="login-openid_identity" value="http://" class="text openid" />
			<span>
				Examples:<br />
				http://claimid.com/username<br />
				http://username.myopenid.com
			</span>
		</label>
	</fieldset>
	<fieldset class="footer">
		<input type="hidden" name="login_from" value="<?= $rq->getUrl() ?>" />
		<input type="submit" value="Log in via OpenID" />
	</fieldset>
</form>