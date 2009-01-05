<?php

class Hub_Releases_Release_EditAction extends RedBaseAction
{
	/**
	 * @var		Release
	 */
	protected $release = null;

	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$release = $this->release;

		$release['user_id'] = $this->us->getAttribute('id', 'org.redracer.user');

		$release->fromRequest($rd);

		if (!$release->trySave()) {
			$this->vm->setError('release', 'Release was not saved, but the programmer was too lazy to check!');
			return $this->executeRead($rd);
		}

		$this->setAttribute('release', $release->toArray(false) );

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('release', $this->release->toArray());

		/**
		 * @todo Clean-up "do", to Write?
		 */
		if ($rd->getParameter('do') == 'import' && $this->release['url_source']) {

			$peer = $this->context->getModel('Curl');
			$map = null;

			try {
				$map = $peer->fetchDirectories($this->release['url_source'], array('raw' => 'true'));
				$this->us->addFlash(sprintf('Fetched %d entries from repository for import.', count($map, COUNT_RECURSIVE)));
			} catch (Exception $e) {
				$this->vm->setError('url_source', 'Fetching the repository data failed.');
			}

			if ($map) {
				$peer = $this->context->getModel('Releases');
				try {
					$updated = $peer->updateFilesMap($this->release['id'], $this->release['url_source'], $map);
					$this->release['imported_at'] = date('Y-m-d H:i:s');
					$this->release->save();
					$this->us->addFlash(sprintf('Imported and updated %s files from the repository.', $updated), 'success');
				} catch (Exception $e) {
					$this->vm->setError('url_source', 'Creating the repository files failed.');
				}
			}

			return 'Success';

		} elseif ($rd->getParameter('do') == 'resolve') {
			$peer = $this->context->getModel('Releases');
			$created = $peer->updateDependencyMap($this->release['id']);
			$this->us->addFlash(sprintf('Imported %d dependencies from scripts.json.', $created), 'success');

			return 'Success';
		}


		return 'Input';
	}

	public function validateWrite(AgaviRequestDataHolder $rd)
	{
		return $this->validateRead($rd);
	}

	public function validateRead(AgaviRequestDataHolder $rd)
	{
		if (!$this->vm->hasError('resource')) {
			$resource =& $rd->getParameter('resource');

			if (!$this->vm->hasError('release')) {
				$this->release = $resource['releases'][$rd->getParameter('release')];

				if (!$this->release || !$this->release->exists()) {
					$this->release = null;
					$this->vm->setError('release', 'Release not found');
					return false;
				}
			}
		}

		return true;
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		if ($this->vm->hasError('resource') || !$this->release) {
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