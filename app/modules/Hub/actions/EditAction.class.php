<?php

class Hub_EditAction extends RedBaseAction
{

	/**
	 * @var		Resource
	 */
	protected $resource = null;

	protected $licences;

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$resource = $this->resource;

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

		if ($this->us->hasCredential('resources.flag')) {
			$resource->setFlagMask($rd->getParameter('flag_mask', array()));
		}

		try {
			$resource->save();
		} catch (Doctrine_Exception $e) {
			$this->us->addFlash('Resource was not saved, but the programmer was too lazy to check!', 'error');
			return $this->executeRead($rd);
		}
		$resource->refreshRelated();

		$this->setAttribute('resource', $resource->toArray(false));

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('resource', $this->resource->toArray(true));

		return 'Input';
	}

	public function validateWrite(AgaviRequestDataHolder $rd)
	{
		$ret = $this->validateRead($rd);

		return $ret;
	}

	public function validateRead(AgaviRequestDataHolder $rd)
	{
		if (!$this->vm->hasError('resource') ) {
			$this->resource =& $rd->getParameter('resource');
			/**
			 * @todo check credentials
			 */
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