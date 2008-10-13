<?php

class Hub_ViewAction extends RedBaseAction
{

	/**
	 * @var		ResourceModel
	 */
	protected $model = null;

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('resource', $this->model->toArray() );

		return 'Success';
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
		}
		else
		{
			return false;
		}

		return true;
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		if (!$this->model)
		{
			return 'Error';
		}

		return 'Success';
	}
}

?>