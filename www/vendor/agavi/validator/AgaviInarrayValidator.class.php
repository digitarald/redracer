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
 * AgaviInArrayValidator verifies whether an input is one of a set of values
 * 
 * Parameters:
 *   'values'  list of values that form the array
 *   'sep'     seperator of values in the list
 *   'case'    verifies case sensitive if true
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
 * @version    $Id: AgaviInarrayValidator.class.php 2259 2008-01-03 16:57:11Z david $
 */
class AgaviInarrayValidator extends AgaviValidator
{
	/**
	 * Validates the input.
	 * 
	 * @return     bool The value is in the array.
	 * 
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @author     Uwe Mesecke <uwe@mesecke.net>
	 * @since      0.11.0
	 */
	protected function validate()
	{
		$list = $this->getParameter('values');
		if(!is_array($list)) {
			$list = explode($this->getParameter('sep'), $list);
		}
		$value = $this->getData($this->getArgument());
		
		if(!$this->getParameter('case')) {
			$value = strtolower($value);
			$list = array_map(create_function('$a', 'return strtolower($a);'), $list);
		}
		
		if(!in_array($value, $list)) {
			$this->throwError();
			return false;
		}
		
		return true;
	}
}

?>