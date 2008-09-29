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
 * AgaviEmailValidator verifies if a parameter contains a value that qualifies
 * as an email address.
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
 * @version    $Id: AgaviEmailValidator.class.php 2258 2008-01-03 16:54:04Z david $
 */
class AgaviEmailValidator extends AgaviValidator
{
	/**
	 * Validates the input.
	 * 
	 * @return     bool The input is a valid email address.
	 * 
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @author     Uwe Mesecke <uwe@mesecke.net>
	 * @since      0.11.0
	 */
	protected function validate()
	{
		$extraChars = preg_quote('!#$%&\'*+-/=?^_`{|}~', '/');
		if(!preg_match('/^[a-z0-9' . $extraChars . ']+(\.[a-z0-9' . $extraChars . ']+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.[a-z]{2,6}$/iD', $this->getData($this->getArgument()))) {
			$this->throwError();
			return false;
		}
		
		return true;
	}
}

?>