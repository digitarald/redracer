<?php

class Hub_Contributors_Contributor_DeleteAction extends RedBaseAction
{
	/**
	 * @var		ContributorModel
	 */
	protected $contributor = null;

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		if (!$this->contributor->delete()) {
			$this->vm->setError('resource', 'Contributor was not deleted, but the programmer was too lazy to check!');
			return $this->executeRead($rd);
		}

		$this->setAttribute('contributor', $this->contributor->toArray(true) );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('contributor', $this->contributor->toArray(true) );

		return 'Input';
	}

	public function validateWrite(AgaviRequestDataHolder $rd)
	{
		return $this->validateRead($rd);
	}

	public function validateRead(AgaviRequestDataHolder $rd)
	{
			if (!$this->vm->hasError('resource')) {
			$resource = $rd->getParameter('resource');

			if (!$this->vm->hasError('contributor')) {
				$this->contributor = $resource['contributors'][$rd->getParameter('contributor')];

				if (!$this->contributor || !$this->contributor->exists()) {
					$this->contributor = null;
					$this->vm->setError('contributor', 'Contributor not found');
					return false;
				}
			}
		}

		return true;
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		if ($this->vm->hasError('resource') || !$this->contributor) {
			return 'Error';
		}

		return $this->executeRead($rd);
	}

	public function isSecure()
	{
		return true;
	}

}

?>