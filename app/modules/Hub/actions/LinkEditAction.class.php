<?php

class Hub_LinkEditAction extends RedBaseAction
{

	/**
	 * @var		ResourceModel
	 */
	protected $resource = null;

	/**
	 * @var		LinkModel
	 */
	protected $link = null;

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$resource = $this->resource;

		if ($this->link) {
			$model = $this->link;
		} else {
			$model = new LinkModel();

			$model['resource_id'] = $resource['id'];
			$model['user_id'] = $this->us->getAttribute('id', 'org.redracer.user');
		}

		$model['text'] = $rd->getParameter('text');
		$model['title'] = $rd->getParameter('title');
		$model['url'] = $rd->getParameter('url');

		$model['priority'] = $rd->getParameter('priority');

		if (!$model->trySave() ) {
			$this->vm->setError('id', 'Link was not saved, but the programmer was too lazy to check!');

			return $this->executeRead($rd);
		}

		$this->setAttributeByRef('link', $model->toArray() );
		$this->setAttributeByRef('resource', $resource->toArray() );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('resource', $this->resource->toArray(true) );

		if ($this->link) {
			if ($rd->getParameter('delete') ) {
				$this->link->delete();

				$this->setAttribute('deleted', true);

				return 'Success';
			}
			$this->setAttribute('link', $this->link->toArray(true) );
		}

		return 'Input';
	}

	public function validateWrite(AgaviRequestDataHolder $rd)
	{
		/**
		 * Check if url exists already
		 */

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

			if ($rd->hasParameter('id') ) {
				$this->link = $this->resource['links'][$rd->getParameter('id')];

				if (!$this->link || !$this->link->exists() ) {
					$this->vm->setError('id', 'Link not found');

					return false;
				}
			}
		}

		return true;
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		if (!$this->resource) {
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