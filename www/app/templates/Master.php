<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title><?php if ($title) echo $title . ' » '; ?>MooTools Community Forge</title>

	<!-- Blueprint -->

	<link rel="stylesheet" type="text/css" href="/css/blueprint/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="/css/blueprint/print.css" media="print" />
	<!--[if IE]>
	<link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection" />
	<![endif]-->

	<!-- MooTools.net -->

	<link href="http://mootools.net/assets/styles/layout.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="http://mootools.net/assets/styles/main.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="http://mootools.net/assets/styles/docs.css" rel="stylesheet" type="text/css" media="screen" />

	<!-- Custom -->

	<link rel="stylesheet" type="text/css" href="/css/our.css" />

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

	<div id="google-search-box">
		<div id="google-search" class="container">
			<div class="span-19 first">
<?php	if (!$user): ?>
				<div class="side-login">
					To contribute <a href="<?= $rt->gen('account.login') ?>">Sign up / Log in</a>
				</div>
<?php	else: ?>
				<div class="side-profile">
					Welcome back, <a href="<?= $rt->gen('index') ?>"><?= $user['fullname'] ?></a>.
					<a href="<?= $rt->gen('account.logout') ?>">Log Out</a>
				</div>
<?php	endif; ?>
			</div>
			<div class="span-5 last">
				<form action="<?= $rt->gen('hub.index') ?>" method="get" id="form-search" title="Just a placeholder!">
					<div>
						<input type="search" name="term" id="google-input" results="4" class="place-holder" placeholder="search" value="" readonly="readonly" />
					</div>
				</form>
			</div>
		</div>
		<div id="google-search-results"></div>
	</div>

	<div id="wrapper">
		<div id="container" class="container">

			<div class="span-5 first colborder" id="main-menu">
				<ul class="filter" title="Filter by resource type">
<?php	foreach ($filters['type'] as $val): ?>
					<li><a href="<?= $val['url'] ?>" class="<?= $val['class'] ?>"><?= $val['title'] ?><?= $val['selected'] ? ' [X]' : '' ?></a> (<?= $val['count'] ?>)</li>
<?php	endforeach; ?>
				</ul>
				<ul class="filter" title="Sort by">
<?php	foreach ($filters['sort'] as $val): ?>
					<li><a href="<?= $val['url'] ?>" class="<?= $val['class'] ?>"><?= $val['title'] ?></a></li>
<?php	endforeach; ?>
				</ul>
				<ul class="filter last" title="Filter by tag">

<?php	foreach ($filters['tag'] as $val): ?>
					<li>
						<a href="<?= $val['url'] ?>" class="<?= $val['class'] ?>"><?= $val['title'] ?><?= $val['selected'] ? ' [X]' : '' ?></a>
						(<?= $val['count'] ?>)
					</li>
<?php	endforeach; ?>
				</ul>

				<h4><a href="<?= $rt->gen('index') ?>">Dashboard</a></h4>
				<ul>
<?php	if (!$user): ?>
					<li><a href="<?= $rt->gen('account.login') ?>">Sign up / Log in</a></li>
<?php	else: ?>
					<li><a href="<?= $rt->gen('account.edit') ?>">Edit your Profile</a></li>
					<li><a href="<?= $rt->gen('account.submit') ?>">Submit a Resource</a></li>
					<li><a href="<?= $rt->gen('account.logout') ?>">Log Out</a></li>
<?php	endif; ?>
				</ul>

				<h4><a href="<?= $rt->gen('pages', array('name' => 'readme')) ?>">About the Forge</a></h4>
				<ul>
					<li><a href="http://groups.google.com/group/mootools-forge">Discuss @ Google Groups</a></li>
					<li><a href="irc://irc.freenode.net/#mootools">Chat @ #mootools</a></li>
					<li><a href="http://github.com/digitarald/our/tree/master">Fork @ github.com</a></li>
					<li><a href="<?= $rt->gen('pages', array('name' => 'readme')) ?>">ReadMe</a></li>
					<li><a href="<?= $rt->gen('pages', array('name' => 'changelog')) ?>">Changelog</a></li>
					<li><a href="<?= $rt->gen('pages', array('name' => 'todo')) ?>">Todo</a></li>
					<li><a href="<?= $rt->gen('pages', array('name' => 'thanks')) ?>">Thanks</a></li>
					<li><a href="<?= $rt->gen('pages', array('name' => 'license')) ?>">License</a></li>
				</ul>
			</div>

			<div class="span-18 last" id="main">
				<ul id="flash">
<?php		$vm = $container->getValidationManager();

			if ($vm->hasErrors() ): ?>
					<li class="error">
						The following errors occurred, any related input fields are marked red:
						<ul>
<?php		foreach ($vm->getErrorMessages() as $message): ?>
							<li title="For the following fields: <?= implode(', ', $message['errors']); ?>"><?= $message['message'] ?></li>
<?php		endforeach; ?>
						</ul>
					</li>
<?php	endif; ?>
<?php	if ($us->hasAttribute('messages', 'our.flash') ): ?>
<?php		foreach ($us->removeAttribute('messages', 'our.flash') as $val):
				$val = (array) $val; ?>
					<li class="<?= isset($val[1]) ? $val[1] : '' ?>"><?= $val[0] ?></li>
<?php		endforeach; ?>
<?php	endif; ?>
				</ul>
				<?= $inner ?>
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