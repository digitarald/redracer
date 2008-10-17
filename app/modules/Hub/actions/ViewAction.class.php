<?php

class Hub_ViewAction extends RedBaseAction
{

	/**
	 * @var		ResourceModel
	 */
	protected $resource = null;

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('resource', $this->resource->toArray() );

		return 'Success';
	}

	public function validateRead(AgaviRequestDataHolder $rd)
	{
		if (!$this->vm->hasError('ident') ) {
			$this->resource =& $rd->getParameter('resource');
		}

		return true;
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		if (!$this->resource) {
			return 'Error';
		}

		return 'Success';
	}
}

?>