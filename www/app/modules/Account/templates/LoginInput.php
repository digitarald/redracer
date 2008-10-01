<form action="<?= $rt->gen('account.login') ?>" method="post" id="form-login">

	<div class="span-10">
		<h3 class="red">Log in</h3>

		<fieldset>
			<label>
				<span class="label">Your OpenID:</span>
				<input type="text" name="openid_identity" id="openid_identifier" value="http://" class="text openid" />
				<span class="small">
					You need an <a href="http://openid.net/">OpenID</a> to sign up/log in. <a href="http://openid.net/what/">Learn more.</a> <a href="http://openid.net/get/">Get one.</a>!
				</span>
			</label>
			<label>
				<span class="label">Remember Login:</span>
				<input type="checkbox" name="login_remember" value="1" checked="checked" />
				<span class="small">Saved for <?= AgaviConfig::get('core.remember_expire') ?>.</span>
			</label>
		</fieldset>

		<fieldset class="footer">
			<!--
			<input type="hidden" name="login_from" value="<?= $rq->getUrl() ?>" />
			-->
			<input type="submit" value="Log in" />
		</fieldset>

	</div>
	<div class="span-8 last">

		<h3 class="purple">Why OpenID?</h3>
		<ul>
			<li>It's a single username and password that allows you to log in to any OpenID-enabled site.</li>
			<li>It works on thousands of websites.</li>
			<li>It's an open standard.</li>
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