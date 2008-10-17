<?php

/**
 * RedResourceModelValidator
 *
 * @package    our
 *
 * @author     Harald Kirschner <mail@digitarald.de>
 */
class RedResourceModelValidator extends AgaviStringValidator
{
	/**
	 * Validates the input.
	 *
	 * @return     bool The input is a valid email address.
	 */
	protected function validate()
	{
		$ret = parent::validate();

		if (!$ret) {
			return false;
		}

		$ident =& $this->getData($this->getArgument());

		$table = Doctrine::getTable('ResourceModel');
		$resource = $table->findOneByIdent($ident);

		if (!$resource) {
			$this->throwError();
			return false;
		}

		$this->export($resource, 'resource');
		return true;
	}
}

?>