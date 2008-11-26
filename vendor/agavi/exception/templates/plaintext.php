<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Agavi package.                                   |
// | Copyright (c) 2005-2008 the Agavi Project.                                |
// |                                                                           |
// | For the full copyright and license information, please view the LICENSE   |
// | file that was distributed with this source code. You can also view the    |
// | LICENSE file online at http://www.agavi.org/LICENSE.txt                   |
// |   vi: set noexpandtab:                                                    |
// |   Local Variables:                                                        |
// |   indent-tabs-mode: t                                                     |
// |   End:                                                                    |
// +---------------------------------------------------------------------------+

/**
 * Plain text exception template
 *
 * @package    agavi
 * @subpackage exception
 *
 * @author     Veikko Mäkinen <mail@veikkomakinen.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.11.0
 *
 * @version    $Id: plaintext.php 3194 2008-10-26 13:53:47Z felix $
 */

if (!headers_sent()) {
	header('Content-Type: text/plain');	
}

// fix stack trace in case it doesn't contain the exception origin as the first entry
$fixedTrace = $e->getTrace();
if(isset($fixedTrace[0]['file']) && !($fixedTrace[0]['file'] == $e->getFile() && $fixedTrace[0]['line'] == $e->getLine())) {
	$fixedTrace = array_merge(array(array('file' => $e->getFile(), 'line' => $e->getLine())), $fixedTrace);
}

?>
===============<?php echo str_repeat('=', strlen(get_class($e))); ?>

  Exception: <?php echo get_class($e); ?>

===============<?php echo str_repeat('=', strlen(get_class($e))); ?>


<?php if($e instanceof AgaviException): ?>
This is an internal Agavi exception. Please consult the documentation for
assistance with solving this issue.
<?php endif; ?>

An exception of type *<?php echo get_class($e); ?>* was thrown, but did not get
caught during the execution of the request. You will find information provided
by the exception along with a stack trace below.

  Message
===========
<?php echo wordwrap(html_entity_decode($e->getMessage()), 80, "\n"); ?>


  Stack Trace
===============
<?php
foreach ($fixedTrace as $no => $trace) {
	echo "$no: ";
	if(isset($trace['file'])) { echo $trace['file']; }
	else { echo "Unknown file" ; }

	if(isset($trace['line'])) { echo " (line: " .$trace['line'] .')'; }
	else { echo "(Unknown line)"; }
	echo "\n";
}
?>


  Version Information
=======================
Agavi:     <?php echo AgaviConfig::get('agavi.version'); ?>

PHP:       <?php echo phpversion(); ?>

System:    <?php echo php_uname(); ?>

Timestamp: <?php echo gmdate(DATE_ISO8601); ?>


