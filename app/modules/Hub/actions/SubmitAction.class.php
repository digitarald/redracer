<?php

class Hub_SubmitAction extends RedBaseAction
{

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$resource = new Resource();

		$resource['ident'] = $rd->getParameter('ident');
		$resource['user_id'] = $this->us->getAttribute('id', 'org.redracer.user');

		$resource['title'] = $rd->getParameter('title');
		$resource['description'] = $rd->getParameter('description');
		$resource['readme'] = $rd->getParameter('readme');

		$resource['category'] = (string) $rd->getParameter('category');
		$resource['stability'] = (string) $rd->getParameter('stability');
		$resource['copyright'] = $rd->getParameter('copyright');

		$resource['url_homepage'] = $rd->getParameter('url_homepage');
		$resource['url_download'] = $rd->getParameter('url_download');
		$resource['url_demo'] = $rd->getParameter('url_demo');
		$resource['url_feed'] = $rd->getParameter('url_feed');
		$resource['url_source'] = $rd->getParameter('url_source');
		$resource['url_support'] = $rd->getParameter('url_support');

		$resource->setLicenceIds($rd->getParameter('licence_ids', array()));
		$resource->setTagIds($rd->getParameter('tag_ids', array()));

		if ($us->hasCredential('resources.flag')) {
			$resource->setFlagMask($rd->getParameter('flag_mask', array()));
		}

		$sub = new Contributor();
		$sub['user_id'] = $this->us->getAttribute('id', 'org.redracer.user');
		if ($rd->getParameter('manager') ) {
			$sub['role'] = '3';
		}
		$resource['contributors'][] = $sub;

		if (!$resource->trySave() ) {
			$this->us->addFlash('Resource was not saved, but the programmer was too lazy to check!', 'error');
			return $this->handleError($rd);
		}

		$this->setAttribute('resource', $resource->toArray(false) );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		return array('Hub', 'EditInput');
	}

	public function validateWrite(AgaviRequestDataHolder $rd)
	{
		$ident = $rd->getParameter('ident');

		if ($ident && !$this->vm->hasError('ident') ) {
			$peer = $this->context->getModel('Resources');
			$check = $peer->findOneByIdent($ident);

			if ($check) {
				$this->vm->setError('ident', 'This ident is already taken, please choose another one!');
				return false;
			}
		}

		return true;
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		return $this->executeRead($rd);
	}

	public function isSecure()
	{
		return true;
	}

}

?>