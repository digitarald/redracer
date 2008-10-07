<?php

class Hub_ContributorEditAction extends OurBaseAction
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

		if ($this->contributor)
		{
			$model = $this->contributor;
		}
		else
		{
			$model = new ContributorModel();

			$model['resource_id'] = $resource['id'];
			$model['user_id'] = $this->us->getAttribute('id', 'our.user');
		}

		$model['text'] = $rd->getParameter('text');
		$model['title'] = $rd->getParameter('title');

		if (!$model->trySave() )
		{
			$this->vm->setError('id', 'Contributor was not saved, but the programmer was too lazy to check!');

			return $this->executeRead($rd);
		}

		$this->setAttributeByRef('contributor', $model->toArray() );
		$this->setAttributeByRef('resource', $resource->toArray() );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('resource', $this->resource->toArray(true) );

		if ($this->contributor)
		{
			if ($rd->getParameter('delete') )
			{
				$this->contributor->delete();

				$this->setAttribute('deleted', true);

				return 'Success';
			}
			$this->setAttribute('contributor', $this->contributor->toArray(true) );
		}

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