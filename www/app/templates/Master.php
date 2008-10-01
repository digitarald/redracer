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
	<link href="http://mootools.net/assets/styles/blog.css" rel="stylesheet" type="text/css" media="screen" />
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

	<div id="search-box">
		<div class="container">
			<div class="span-19 first">
<?php	if (!$user): ?>
				<div class="side-login">
					To contribute <a href="<?= $rt->gen('account.login') ?>">Sign up / Log in</a>
				</div>
<?php	else: ?>
				<div class="side-profile">
					Welcome back, <a href="<?= $rt->gen('index') ?>"><?= $user['fullname'] ?></a>.
					<a href="<?= $rt->gen('account.logout') ?>">Logout</a>
				</div>
<?php	endif; ?>
			</div>
			<div class="span-5 last">
				<form action="<?= $rt->gen('hub.index') ?>" method="get" id="form-search" title="Just a placeholder!">
					<div>
						<input type="search" name="term" id="search-q" results="4" class="search" placeholder="search" value="" readonly="readonly" />
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="wrapper">
		<div id="container" class="container">

			<div class="span-5 colborder" id="main-menu">
				<div class="filter" title="Filter by resource type">
<?php	foreach ($filters['type'] as $val): ?>
					<a href="<?= $val['url'] ?>" class="<?= $val['class'] ?>"><?= $val['title'] ?></a>
<?php	endforeach; ?>
				</div>
				<div class="filter" title="Sort by">
<?php	foreach ($filters['sort'] as $val): ?>
					<a href="<?= $val['url'] ?>" class="<?= $val['class'] ?>"><?= $val['title'] ?></a>
<?php	endforeach; ?>
				</div>
				<div class="filter last" title="Filter by tag">

<?php	foreach ($filters['tag'] as $val): ?>
					<span><a href="<?= $val['url'] ?>" class="<?= $val['class'] ?>"><?= $val['title'] ?></a> (<?= $val['count'] ?>)</span>
<?php	endforeach; ?>
				</div>

				<h4>Account</h4>
<?php	if (!$user): ?>
				<div><a href="<?= $rt->gen('account.login') ?>">Sign up/Log in</a></div>
<?php	else: ?>
				<div><a href="<?= $rt->gen('index') ?>">Dashboard</a></div>
				<div><a href="<?= $rt->gen('account.edit') ?>">Edit Profile</a></div>
				<div><a href="<?= $rt->gen('account.submit') ?>">Add Resource</a></div>
				<div><a href="<?= $rt->gen('account.logout') ?>">Logout</a></div>
<?php	endif; ?>

				<h4>About the Repository</h4>
				<div><a href="http://groups.google.com/group/mootools-forge">Discuss @ Google Group</a></div>
				<div><a href="irc://irc.freenode.net/#mootools-dev">Chat @ #mootools-dev</a></div>
				<div><a href="http://github.com/digitarald/our/tree/master">Fork @ github</a></div>
				<div><a href="<?= $rt->gen('page', array('name' => 'readme')) ?>">ReadMe</a></div>
				<div><a href="<?= $rt->gen('page', array('name' => 'changelog')) ?>">Changelog</a></div>
				<div><a href="<?= $rt->gen('page', array('name' => 'todo')) ?>">Todo</a></div>
				<div><a href="<?= $rt->gen('page', array('name' => 'thanks')) ?>">Thanks</a></div>
				<div><a href="<?= $rt->gen('page', array('name' => 'license')) ?>">License</a></div>
			</div>

			<div class="span-18 last" id="main">
<?php	if ($us->hasAttribute('messages', 'our.flash') ): ?>
				<ul id="flash">
<?php		foreach ($us->removeAttribute('messages', 'our.flash') as $val):
				$val = (array) $val; ?>
					<li class="<?= isset($val[1]) ? $val[1] : '' ?>"><?= $val[0] ?></li>
<?php		endforeach; ?>
				</ul>
<?php	endif; ?>
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