<?php

/**
 * RedRacerResourceModelValidator
 *
 * @package    redracer
 *
 * @author     Harald Kirschner <mail@digitarald.de>
 */
class RedRacerResourceModelValidator extends AgaviStringValidator
{
	/**
	 * Validates the input.
	 *
	 * @return     bool
	 */
	protected function validate()
	{
		$ret = parent::validate();

		if (!$ret) {
			return false;
		}

		$ident =& $this->getData($this->getArgument());

		$peer = $this->context->getModel('Resources');
		$resource = $peer->findOneByIdent($ident);

		if (!$resource) {
			$this->throwError();
			return false;
		}

		$this->export($resource, 'resource');
		return true;
	}
}

?>