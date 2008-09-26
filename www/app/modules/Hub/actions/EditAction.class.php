<?php

class Hub_EditAction extends OurBaseAction
{

	/**
	 * @var		ResourceModel
	 */
	protected $resource = null;

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$model = $this->resource;

		$model['title'] = $rd->getParameter('title');
		$model['text'] = $rd->getParameter('text');
		$model['license_text'] = $rd->getParameter('licence_text');
		$model['license_url'] = $rd->getParameter('licence_url');

		// $model['tags']->delete();

		if (!$model->trySave() )
		{
			$this->vm->setError('id', 'Resource was not saved, but the programmer was too lazy to check!');

			return $this->executeRead($rd);
		}

		$this->setAttributeByRef('resource', $model->toArray() );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$model = $this->resource->toArray(true);
		$this->setAttributeByRef('resource', $model);

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
				return false;
			}
			/**
			 * @todo check credentials
			 */
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