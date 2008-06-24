<?php

class Hub_Edit_BookmarkAction extends OurBaseAction
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
		if ($this->model)
		{
			$this->setAttribute('resource', $this->model->toArray() );
		}

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
				/**
				 * @todo q&d, add error handling
				 */
				return false;
			}
		}

		return true;
	}

	public function isSecure()
	{
		return true;
	}


}

?>