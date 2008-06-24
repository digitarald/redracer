<?php

/**
 * OurOpenIdValidator verifies if a parameter contains a value that qualifies
 * as an OpenId url.
 *
 * @package    our
 *
 * @author     Harald Kirschner <mail@digitarald.de>
 */
class OurOpenIdValidator extends OurUrlValidator
{
	/**
	 * Validates the input.
	 *
	 * @return     bool The input is a valid email address.
	 */
	protected function validate()
	{
		if (!parent::validate() )
		{
			return false;
		}

		$url =& $this->getData($this->getArgument());

		$url = SimpleOpenID::OpenID_Standarize($url);

		return true;
	}
}

?>