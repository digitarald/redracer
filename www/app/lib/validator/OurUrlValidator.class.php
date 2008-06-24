<?php

/**
 * OurUrlValidator verifies if a parameter contains a value that qualifies
 * as an http url.
 *
 * @package    our
 *
 * @author     Harald Kirschner <mail@digitarald.de>
 */
class OurUrlValidator extends AgaviValidator
{
	/**
	 * Validates the input.
	 *
	 * @return     bool The input is a valid email address.
	 */
	protected function validate()
	{
		$url =& $this->getData($this->getArgument());

		$url = trim($url);

		if (!preg_match('/^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)$/', $url, $m) )
		{
			$this->throwError();
			return false;
		}

		if (!$m[1])
		{
			$url = 'http://' . $url;
		}

		/**
		 * TODO: Parameter for valid protocols
		 */

		return true;
	}
}

?>