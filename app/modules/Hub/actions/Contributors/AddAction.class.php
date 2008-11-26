<?php

class Hub_Contributors_AddAction extends RedBaseAction
{
	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$resource = $rd->getParameter('resource');

		$contributor = new Contributor();
		$contributor['resource_id'] = $resource['id'];
		$contributor['user_id'] = $this->us->getAttribute('id', 'org.redracer.user');
		$contributor['role'] = (string) $rd->getParameter('role');

		if (!$contributor->trySave() ) {
			$this->vm->setError('contributor', 'Contributor was not saved, but the programmer was too lazy to check!');
			return $this->executeRead($rd);
		}

		$this->setAttribute('resource', $resource->toArray() );

		$contributor->loadReference('user');
		$this->setAttribute('contributor', $contributor->toArray(true) );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		return array('Hub', 'Contributors/Contributor/EditInput');
	}

	public function validateWrite(AgaviRequestDataHolder $rd)
	{
		$ret = $this->validateRead($rd);

		return $ret;
	}

	public function validateRead(AgaviRequestDataHolder $rd)
	{
		if (!$this->vm->hasError('resource')) {
			$resource = $rd->getParameter('resource');

			$user_id = $this->us->getAttribute('id', 'org.redracer.user');
			if ($resource->hasContributor($user_id) ) {
				$this->vm->setError('user_id', 'Contributor is already added');
				return false;
			}
		}

		return true;
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		if (!$rd->hasParameter('resource')) {
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