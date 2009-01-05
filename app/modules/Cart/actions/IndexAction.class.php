<?php

class Cart_IndexAction extends RedCartBaseAction
{
	public function executeWrite(AgaviRequestDataHolder $rd)
	{
		$file_id = $rd->getParameter('file_ids', array());
		$release_ids = $rd->getParameter('release_ids', array());
		$resource_ids = $rd->getParameter('resource_ids', array());

		return 'Success';
	}

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		if ($rd->hasParameter('file_ids') || $rd->hasParameter('release_ids') || $rd->hasParameter('resource_ids')) {
			return $this->executeWrite($rd);
		}

		$query = new Doctrine_Query();

		$query->from('Download download')
			->select('download.*, file.*, release.*, resource.*, dependency.*')
			->leftJoin('download.file AS file')
			->leftJoin('file.release AS release')
			->leftJoin('release.resource AS resource')
			->leftJoin('download.dependency AS dependency')
			->andWhere('download.user_id = :user_id');

		$downloads = $query->execute(array(
			'user_id' => $this->us->getAttribute('id', 'org.redracer.user')
		));

		$this->setAttribute('downloads', $downloads->toArray(true));

		return 'Input';
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