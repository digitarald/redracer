<?php

class Hub_Links_Link_EditAction extends RedBaseAction
{
	/**
	 * @var		Link
	 */
	protected $link = null;

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$link = $this->link;

		$link['user_id'] = $this->us->getAttribute('id', 'org.redracer.user');
		$link['url'] = $rd->getParameter('url');
		$link['title'] = $rd->getParameter('title');
		$link['description'] = $rd->getParameter('description');
		$link->setFlagMask($rd->getParameter('flag', array()));

		if (!$link->trySave() ) {
			$this->vm->setError('link', 'Link was not saved, but the programmer was too lazy to check!');
			return $this->executeRead($rd);
		}

		$this->setAttribute('link', $link->toArray());

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('link', $this->link->toArray());

		return 'Input';
	}

	public function validateWrite(AgaviRequestDataHolder $rd)
	{
		return $this->validateRead($rd);
	}

	public function validateRead(AgaviRequestDataHolder $rd)
	{
		if (!$this->vm->hasError('resource')) {
			$resource = $rd->getParameter('resource');

			if (!$this->vm->hasError('link')) {
				$this->link = $resource['link'][$rd->getParameter('link')];

				if (!$this->link || !$this->link->exists()) {
					$this->link = null;
					$this->vm->setError('link', 'Link not found');
					return false;
				}
			}
		}

		return true;
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		if ($this->vm->hasError('resource') || !$this->link) {
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