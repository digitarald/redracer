<form action="<?= $rt->gen('account.login') ?>" method="post" id="form-login">

	<p>
		You need an <a href="http://openid.net/">OpenID</a> to log in (<a href="http://openid.net/what/">What is it?</a>).
		If you don't have one, sign up free on <a href="https://www.myopenid.com/signup?affiliate_id=19425&openid.sreg.optional=email,nickname">myOpenID</a>!
	</p>

	<fieldset>
		<label>
			<span class="label">Your OpenID:</span>
			<input type="text" name="openid_identity" id="login-openid_identity" value="http://" class="text openid" />
			<script type="text/javascript" charset="utf-8" id="__openidselector" src="https://www.idselector.com/selector/7bf043a495ac6901e8b1caf78916ac3aa5f53628"></script>
			<span class="small">
				Examples: <em>http://claimid.com/username</em>, <em>http://username.myopenid.com</em>
			</span>
		</label>
		<label>
			<span class="label">Remember Login:</span>
			<input type="checkbox" name="login_remember" id="login-login_remember" value="1" checked="checked" />
			<span class="small">Saved for <?= AgaviConfig::get('core.remember_expire') ?>.</span>
		</label>
	</fieldset>


	<fieldset class="footer">
		<!--
		<input type="hidden" name="login_from" value="<?= $rq->getUrl() ?>" />
		-->
		<input type="submit" value="Log in via OpenID" />
	</fieldset>
</form>