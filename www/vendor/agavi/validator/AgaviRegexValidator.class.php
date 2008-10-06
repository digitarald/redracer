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
 * AgaviRegexValidator allows you to match a value against a regular expression
 * pattern.
 * 
 * Parameters:
 *   'pattern'  PCRE to be used in preg_match
 *   'match'    input should match or not
 * 
 * @package    agavi
 * @subpackage validator
 *
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @author     Uwe Mesecke <uwe@mesecke.net>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.11.0
 *
 * @version    $Id: AgaviRegexValidator.class.php 2259 2008-01-03 16:57:11Z david $
 */
class AgaviRegexValidator extends AgaviValidator
{
	/**
	 * Validates the input.
	 * 
	 * @return     bool True if input matches the pattern in 'match'.
	 * 
	 * @author     Uwe Mesecke <uwe@mesecke.net>
	 * @since      0.11.0
	 */
	protected function validate()
	{
		$result = preg_match($this->getParameter('pattern'), $this->getData($this->getArgument()));
		
		if($result != $this->getParameter('match')) {
			$this->throwError();
			return false;
		}
		
		return true;
	}
}

?>