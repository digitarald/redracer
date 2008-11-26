<?php

class Hub_Links_AddAction extends RedBaseAction
{
	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$resource = $rd->getParameter('resource');

		$link = new Link();
		$link['resource_id'] = $resource['id'];
		$link['user_id'] = $this->us->getAttribute('id', 'org.redracer.user');
		$link['url'] = $rd->getParameter('url');
		$link['title'] = $rd->getParameter('title');
		$link['description'] = $rd->getParameter('description');

		$link->setFlagMask($rd->getParameter('flag', array()));

		if (!$link->trySave() ) {
			$this->vm->setError('link', 'Link was not saved, but the programmer was too lazy to check!');
			return $this->executeRead($rd);
		}

		$this->setAttribute('resource', $resource->toArray() );
		$this->setAttribute('link', $link->toArray() );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		return array('Hub', 'Links/Link/EditInput');
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