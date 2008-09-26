<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title><?php if ($title) echo $title . ' » '; ?>OUR</title>

	<!-- Blueprint -->

	<link rel="stylesheet" type="text/css" href="/css/blueprint/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="/css/blueprint/print.css" media="print" />
	<!--[if IE]>
	<link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection" />
	<![endif]-->

	<!-- MooTools.net -->

	<link href="http://mootools.net/assets/styles/layout.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="http://mootools.net/assets/styles/main.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="http://mootools.net/assets/styles/blog.css" rel="stylesheet" type="text/css" media="screen" />

	<!-- Custom -->

	<link rel="stylesheet" type="text/css" href="/css/structure.css" />

</head>
<body>
	<div id="header">

		<div class="container">
			<a href="http://mediatemple.net" id="mediatemple"><span>in partnership with mediatemple</span></a>

			<div id="logo">

				<h1><a href="/"><span>MooTools</span></a></h1>
				<h2><span>a compact javascript framework</span></h2>
			</div>

			<div id="navigation">
				<a href="http://mootools.net" class="first">Home</a>
				<a href="/" class="active">Plugins</a>
			</div>

		</div>

	</div>

	<div id="search-box">
		<div class="container">
			<div class="span-19 first">
<?php	if (!$user): ?>
				<div class="side-login">
					Be Part of It! <a href="<?= $rt->gen('account.login') ?>">Sign up/Log in</a>!
				</div>
<?php	else: ?>
				<div class="side-profile">
					Welcome back, <a href="<?= $rt->gen('account.index') ?>"><?= $user['fullname'] ?></a>.
					<a href="<?= $rt->gen('account.logout') ?>">Logout</a>
				</div>
<?php	endif; ?>
			</div>
			<div class="span-5 last">
				<form action="<?= $rt->gen('search') ?>" method="get" id="form-search">
					<div>
						<input type="search" name="q" id="search-q" results="4" class="search" placeholder="search" value="" />
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="wrapper">
		<div id="container" class="container">

			<div class="span-18 colborder first" id="main">
<?php	if ($us->hasAttribute('messages', 'our.flash') ): ?>
				<ul id="flash">
<?php		foreach ($us->removeAttribute('messages', 'our.flash') as $val):
				$val = (array) $val; ?>
					<li><?= $val[0] ?></li>
<?php		endforeach; ?>
				</ul>
<?php	endif; ?>
			<?= $inner ?>
			</div>

			<div class="span-5 last" id="sidebar">

<?php	if (false && isset($resource) ): ?>
				<h4><span><?= $resource['title'] ?></span></h4>
				<ul>
					<li><a href="<?= $resource['url'] ?>">Home</a></li>
					<li><a href="<?= $resource['url_docs'] ?>">Documentation</a></li>
					<li><a href="<?= $resource['url_edit'] ?>">Edit</a></li>
				</ul>
<?php	endif; ?>


				<h4><span>Resources</span></h4>
				<ul>
					<li><a href="<?= $rt->gen('hub.index') ?>">Browse</a> (<a href="<?= $rt->gen('hub.index+feed') ?>">RSS</a>)</li>
					<li><a href="<?= $rt->gen('account.submit') ?>">Add Resource</a></li>
				</ul>

				<h4><span>Account</span></h4>
				<ul>
<?php	if (!$user): ?>
					<li><a href="<?= $rt->gen('account.login') ?>">Sign up/Log in</a>
<?php	else: ?>
					<li><a href="<?= $rt->gen('account.index') ?>">Dashboard</a></li>
					<li><a href="<?= $rt->gen('account.edit') ?>">Edit Profile</a></li>
					<li><a href="<?= $rt->gen('account.logout') ?>">Logout</a></li>
<?php	endif; ?>
				</ul>
			</div>

		</div>
	</div>
	<div id="footer">

		<div class="container">
			<p class="copy"><a href="http://mad4milk.net" id="mucca"></a></p>
			<p>copyright © 2006-2008 <a href="http://mad4milk.net">Valerio Proietti</a></p>
			<p>OUR is copyright © 2008 <a href="http://digitarald.de">Harald Kirschner</a></p>
		</div>

	</div>

</body>
</html>