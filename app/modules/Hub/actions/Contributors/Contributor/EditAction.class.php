<?php

class Hub_Contributors_Contributor_EditAction extends RedBaseAction
{

	/**
	 * @var		ResourceModel
	 */
	protected $resource = null;

	/**
	 * @var		ContributorModel
	 */
	protected $contributor = null;

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$this->contributor['text'] = $rd->getParameter('text');
		$this->contributor['title'] = $rd->getParameter('title');

		if (!$this->contributor->trySave() ) {
			$this->vm->setError('id', 'Contributor was not saved, but the programmer was too lazy to check!');

			return $this->executeRead($rd);
		}

		$this->setAttribute('contributor', $this->contributor->toArray(true) );
		$this->setAttribute('resource', $this->resource->toArray(true) );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('resource', $this->resource->toArray(true) );
		$this->setAttribute('contributor', $this->contributor->toArray(true) );

		return 'Input';
	}

	public function validateWrite(AgaviRequestDataHolder $rd)
	{
		return $this->validateRead($rd);
	}

	public function validateRead(AgaviRequestDataHolder $rd)
	{
		if (!$this->vm->hasError('ident') ) {
			$this->resource =& $rd->getParameter('resource');

			if (!$this->vm->hasError('id') ) {
				$this->contributor = $this->resource['contributors'][$rd->getParameter('id')];

				if (!$this->contributor || !$this->contributor->exists() ) {
					$this->contributor = null;
					$this->vm->setError('id', 'Contributor not found');
					return false;
				}
			}
		}

		return true;
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		if (!$this->resource || !$this->contributor) {
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