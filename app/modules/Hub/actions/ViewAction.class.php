<?php

class Hub_ViewAction extends RedBaseAction
{

	/**
	 * @var		ResourceModel
	 */
	protected $resource = null;

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$peer = $this->context->getModel('Hits');
		$peer->checkHit($this->resource['id']);

		$this->setAttribute('resource', $this->resource->toArray(true) );

		return 'Success';
	}

	public function validateRead(AgaviRequestDataHolder $rd)
	{
		if (!$this->vm->hasError('resource')) {
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