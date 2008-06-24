<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php if ($title) echo $title . ' » '; ?>OUR</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="our" />
	<meta name="description" content="our" />

	<link rel="stylesheet" type="text/css" href="/css/blueprint/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="/css/blueprint/print.css" media="print" />
	<!--[if IE]>
	<link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection" />
	<![endif]-->
</head>
<body>
	<div class="clear">
		<div class="container">
			<h1><a href="/">OUR</a></h1>
		</div>
	</div>
	<div class="container" id="container">
		<div class="span-6 column colborder" id="sidebar">
<?php	if ($user): ?>
			<p class="side-profile">
				Welcome back, <a href="<?= $rt->gen('account.index') ?>"><?= $user['fullname'] ?></a>.
				<a href="<?= $rt->gen('account.logout') ?>">Logout</a>
			</p>
<?php	else: ?>
			<p class="side-login">
				Don`t be shy: <a href="<?= $rt->gen('account.login') ?>">Sign up/Log in</a>!
			</p>
<?php	endif; ?>
			{sidebar}
		</div>
		<div class="span-16 column" id="content">
<?php	if ($us->hasAttribute('messages', 'our.flash') ): ?>
			<ul>
<?php		foreach ($us->removeAttribute('messages', 'our.flash') as $val):
				$val = (array) $val; ?>
				<li><?= $val[0] ?></li>
<?php		endforeach; ?>
			</ul>
<?php	endif; ?>
			<?= $inner ?>
		</div>
	</div>
</body>
</html>