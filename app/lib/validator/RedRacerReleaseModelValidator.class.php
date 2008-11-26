<?php

/**
 * RedRacerReleaseModelValidator
 *
 * @package    redracer
 *
 * @author     Harald Kirschner <mail@digitarald.de>
 */
class RedRacerReleaseModelValidator extends AgaviStringValidator
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

		$args = $this->getArguments();

		$peer = $this->context->getModel('Releases');
		$release = $peer->findOneByIdAndIdent($this->getData($args[0]), $this->getData($args[1]));

		if (!$release) {
			$this->throwError();
			return false;
		}

		$this->export($release, 'release');
		return true;
	}
}

?>