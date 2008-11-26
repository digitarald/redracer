<?php

class Hub_Releases_AddAction extends RedBaseAction
{
	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$resource = $rd->getParameter('resource');

		$release = new Release();
		$release['resource_id'] = $resource['id'];
		$release['user_id'] = $this->us->getAttribute('id', 'org.redracer.user');

		$release->fromRequest($rd);

		if (!$rd->hasParameter('released_at')) {
			$release['released_at'] = date('Y-m-d');
		}

		if (!$release->trySave()) {
			$this->vm->setError('release', 'Release was not saved, but the programmer was too lazy to check!');
			return $this->executeRead($rd);
		}

		$this->setAttribute('resource', $resource->toArray() );
		$this->setAttribute('release', $release->toArray(false) );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		return array('Hub', 'Releases/Release/EditInput');
	}

	public function validateWrite(AgaviRequestDataHolder $rd)
	{
		$ret = $this->validateRead($rd);

		return $ret;
	}

	public function validateRead(AgaviRequestDataHolder $rd)
	{
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