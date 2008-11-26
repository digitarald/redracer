<form action="<?= $rt->gen('account.login') ?>" method="post" id="form-login">

	<div class="span-12">
		<h3 class="red">Log in</h3>

		<fieldset>
			<label class="field">
				<span class="label">Your OpenID</span>
				<input type="text" name="openid_identifier" id="openid_identifier" size="55" value="http://" class="text custom openid" />
				<span class="hints">
					You need an <a href="http://openid.net/">OpenID</a> to sign up/log in!<br />
					To add an OpenID to an existing account, provide the same email-address in the profile.
				</span>
			</label>
			<label class="field">
				<input type="checkbox" name="login_remember" value="1" checked="checked" />
				<span class="label choice">Remember Login</span>
				<span class="hints">Saved for <?= AgaviConfig::get('org.redracer.config.account.autologin_lifetime') ?>.</span>
			</label>
		</fieldset>

		<fieldset class="footer">
			<input type="submit" value="Log in" />
		</fieldset>

	</div>
	<div class="span-6 last">

		<h3 class="purple">Why OpenID?</h3>
		<ul>
			<li>It's a single username and password that allows you to log in to any OpenID-enabled site.</li>
			<li>It works on thousands of websites.</li>
			<li>It's an open standard.</li>
			<li>... <a href="http://openid.net/what/">learn more</a> or <a href="http://openid.net/get/">get one!</a></li>
		</ul>

		<h3 class="purple">Example OpenIDs</h3>
		<p>
			<tt>
				http://openid.aol.com/<em>yourname</em><br />
				http://<em>yourname</em>.myopenid.com/<br />
				https://me.yahoo.com/<em>yourname</em> or http://yahoo.com/<br />
				http://claimid.com/<em>yourname</em><br />
				http://<em>yourname</em>.wordpress.com/<br />
				http://<em>yourname</em>.blogspot.com/<br />
				http://technorati.com/people/technorati/<em>yourname</em><br />
				http://<em>yourname</em>.pip.verisignlabs.com/<br />
				http://<em>yourname</em>.livejournal.com/<br />
				http://www.flickr.com/photos/<em>yourname</em>
			</tt>
		</p>
		<p>
			Please note that you must <em>enable OpenID support</em> with your preferred provider!
		</p>
	</div>
</form>
<script type="text/javascript" charset="utf-8" id="__openidselector" src="https://www.idselector.com/selector/7bf043a495ac6901e8b1caf78916ac3aa5f53628"></script>