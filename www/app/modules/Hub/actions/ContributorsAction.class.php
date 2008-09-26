<?php

class Hub_ContributorsAction extends OurBaseAction
{

	/**
	 * @var		ResourceModel
	 */
	protected $model = null;

	/**
	 * @var		Doctrine_Collection
	 */
	protected $models = null;

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		if ($rd->hasParameter('id') )
		{

		}

		return $this->executeRead($rd);
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('resource', $this->model->toArray() );

		$this->setAttribute('contributors', $this->models->toArray(true) );

		return 'Input';
	}

	public function validateWrite(AgaviRequestDataHolder $rd)
	{
		$ret = $this->validateRead($rd);

		if ($rd->hasParameter('id') )
		{
			$id = $rd->getParameter('id');

			if (!$this->models[])
		}
	}

	public function validateRead(AgaviRequestDataHolder $rd)
	{
		if ($rd->hasParameter('ident') )
		{
			$table = Doctrine::getTable('ResourceModel');

			$this->model = $table->findOneByIdent($rd->getParameter('ident') );

			if (!$this->model)
			{
				return false;
			}

			$this->models = $this->model['contributors'];
			/**
			 * @todo check credentials
			 */
		}

		return true;
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		if (!$this->model)
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