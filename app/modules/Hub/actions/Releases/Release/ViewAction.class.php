<?php

class Hub_Releases_Release_ViewAction extends RedBaseAction
{
	/**
	 * @var		Release
	 */
	protected $release = null;

	public function execute(AgaviRequestDataHolder $rd)
	{
		$files_ids = $this->release['files']->getPrimaryKeys();

		$query = new Doctrine_Query();
		$query->select('dependency.*, resource.*, release.*, file.*, release_resource.*, file_release.*, file_release_resource.*')
			->from('Dependency dependency INDEXBY dependency.id')
			->leftJoin('dependency.target_resource AS resource INDEXBY resource.id')
			->leftJoin('dependency.target_release AS release INDEXBY release.id')
			->leftJoin('release.resource AS release_resource INDEXBY release_resource.id')
			->leftJoin('dependency.target_file AS file INDEXBY file.id')
			->leftJoin('file.release AS file_release INDEXBY file_release.id')
			->leftJoin('file_release.resource AS file_release_resource INDEXBY file_release_resource.id')
			->where('dependency.resource_id = ' . $this->release['resource_id'])
			->orWhere('dependency.release_id = ' . $this->release['id']);

		if (count($files_ids)) {
			$query->orWhere('dependency.file_id IN (' . implode(', ', $files_ids) . ')');
		}

		$dependencies = $query->execute();

		foreach ($this->release['dependencies'] as &$dependency) {
			$dependency = $dependencies[$dependency['id']];
		}
		foreach ($this->release['resource']['dependencies'] as &$dependency) {
			$dependency = $dependencies[$dependency['id']];
		}
		foreach ($this->release['files'] as &$file) {
			foreach ($file['dependencies'] as &$dependency) {
				$dependency = $dependencies[$dependency['id']];
			}
		}

		$this->setAttribute('release', $this->release->toArray(true));

		return 'Success';
	}

	public function validate(AgaviRequestDataHolder $rd)
	{
		if (!$this->vm->hasError('release')) {
			$this->release =& $rd->getParameter('release');
		}

		return true;
	}

	public function handleError(AgaviRequestDataHolder $rd)
	{
		if (!$this->release) {
			return 'Error';
		}

		return $this->execute($rd);
	}

}

?>