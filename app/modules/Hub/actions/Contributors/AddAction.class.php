<?php

class Hub_Contributors_AddAction extends RedBaseAction
{

	/**
	 * @var		ResourceModel
	 */
	protected $resource = null;

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		/**
		 * @todo Add different registered user to project.
		 */

		$model = Doctrine::getTable('ContributorModel')->create();

		$model['resource_id'] = $this->resource['id'];
		$model['user_id'] = $this->us->getAttribute('id', 'org.redracer.user');

		if (!$model->trySave() ) {
			$this->vm->setError('id', 'Contributor was not saved, but the programmer was too lazy to check!');
			return $this->executeRead($rd);
		}

		$this->setAttribute('contributor', $model->toArray(true) );
		$this->setAttribute('resource', $this->resource->toArray(true) );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('resource', $this->resource->toArray(true) );

		return 'Input';
	}

	public function validateWrite(AgaviRequestDataHolder $rd)
	{
		$ret = $this->validateRead($rd);

		if ($ret) {
			$user_id = $this->us->getAttribute('id', 'org.redracer.user');

			if ($this->resource->hasContributor($user_id) ) {
				$this->vm->setError('id', 'Contributor is already added');
				return false;
			}
		}

		return $ret;
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

		return $this->executeRead($rd);
	}

	public function isSecure()
	{
		return true;
	}

}

?>