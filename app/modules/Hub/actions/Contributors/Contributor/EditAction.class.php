<?php

class Hub_Contributors_Contributor_EditAction extends RedBaseAction
{

	/**
	 * @var		ResourceModel
	 */
	protected $model = null;

	/**
	 * @var		ContributorModel
	 */
	protected $contributor = null;

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$resource = $this->resource;

		$this->contributor['text'] = $rd->getParameter('text');
		$this->contributor['title'] = $rd->getParameter('title');

		if (!$this->contributor->trySave() )
		{
			$this->vm->setError('id', 'Contributor was not saved, but the programmer was too lazy to check!');

			return $this->executeRead($rd);
		}

		$this->setAttribute('contributor', $this->contributor->toArray() );
		$this->setAttribute('resource', $resource->toArray() );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('resource', $this->resource->toArray(true) );

		if ($rd->getParameter('delete') )
		{
			$this->contributor->delete();

			$this->setAttribute('deleted', true);

			return 'Success';
		}
		$this->setAttribute('contributor', $this->contributor->toArray(true) );

		return 'Input';
	}

	public function validateWrite(AgaviRequestDataHolder $rd)
	{
		return $this->validateRead($rd);
	}

	public function validateRead(AgaviRequestDataHolder $rd)
	{
		if ($rd->hasParameter('ident') )
		{
			$table = Doctrine::getTable('ResourceModel');

			$this->resource = $table->findOneByIdent($rd->getParameter('ident') );

			if (!$this->resource)
			{
				$this->vm->setError('ident', 'Resource not found');

				return false;
			}

			if ($rd->hasParameter('id') )
			{
				$this->contributor = $this->resource['contributors'][$rd->getParameter('id')];

				if (!$this->contributor || !$this->contributor->exists() )
				{
					$this->vm->setError('id', 'Contributor not found');

					return false;
				}
			}
		}

		return true;
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		if (!$this->resource)
		{
			return 'Error';
		}

		return 'Input';
	}

	public function isSecure()
	{
		return true;
	}

}

?>