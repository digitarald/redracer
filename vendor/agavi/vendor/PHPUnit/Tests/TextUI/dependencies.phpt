--TEST--
phpunit --verbose DependencyTestSuite ../_files/DependencyTestSuite.php
--FILE--
<?php
$_SERVER['argv'][1] = '--verbose';
$_SERVER['argv'][2] = 'DependencyTestSuite';
$_SERVER['argv'][3] = dirname(dirname(__FILE__)) . '/_files/DependencyTestSuite.php';

require_once dirname(dirname(dirname(__FILE__))) . '/TextUI/Command.php';
?>
--EXPECTF--
PHPUnit %s by Sebastian Bergmann.

Test Dependencies
 DependencySuccessTest
 ...

 DependencyFailureTest
 FSS

Time: %d seconds

There was 1 failure:

1) testOne(DependencyFailureTest)
%s/dependencies.php:6

There were 2 skipped tests:

1) testTwo(DependencyFailureTest)
This test depends on "DependencyFailureTest::testOne" to pass.
%s/dependencies.php:6

2) testThree(DependencyFailureTest)
This test depends on "DependencyFailureTest::testTwo" to pass.
%s/dependencies.php:6

FAILURES!
Tests: 6, Assertions: 0, Failures: 1, Skipped: 2.
