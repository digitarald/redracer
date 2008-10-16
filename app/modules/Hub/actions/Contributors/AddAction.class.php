<?php

class Hub_Contributors_AddAction extends RedBaseAction
{

	/**
	 * @var		ResourceModel
	 */
	protected $model = null;

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$resource = $this->resource;

		/**
		 * @todo Add different registered user to project.
		 */

		$model = new ContributorModel();

		$model['resource_id'] = $resource['id'];
		$model['user_id'] = $this->us->getAttribute('id', 'org.redracer.user');

		$model['text'] = $rd->getParameter('text');
		$model['title'] = $rd->getParameter('title');

		if (!$model->trySave() ) {
			$this->vm->setError('id', 'Contributor was not saved, but the programmer was too lazy to check!');

			return $this->executeRead($rd);
		}

		$this->setAttribute('contributor', $model->toArray() );
		$this->setAttribute('resource', $resource->toArray() );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('resource', $this->resource->toArray(true) );

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