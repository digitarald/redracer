<?php

class Hub_EditAction extends OurBaseAction
{

	/**
	 * @var		ResourceModel
	 */
	protected $model = null;

	public function executeWrite(AgaviRequestDataHolder $rd)
	{


		return $this->executeRead($rd);
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('resource', $this->model->toArray(true) );

		return 'Input';
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