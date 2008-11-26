<?php

class Hub_Releases_Release_DeleteAction extends RedBaseAction
{
	/**
	 * @var		ReleaseModel
	 */
	protected $release = null;

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		if (!$this->release->delete()) {
			$this->vm->setError('release', 'Release was not deleted, but the programmer was too lazy to check!');
			return $this->executeRead($rd);
		}

		$this->setAttribute('release', $this->release->toArray() );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('release', $this->release->toArray() );

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

			if (!$this->vm->hasError('release')) {
				$this->release = $resource['releases'][$rd->getParameter('release')];

				if (!$this->release || !$this->release->exists()) {
					$this->release = null;
					$this->vm->setError('release', 'Release not found');
					return false;
				}
			}
		}

		return true;
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		if ($this->vm->hasError('resource') || !$this->release) {
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