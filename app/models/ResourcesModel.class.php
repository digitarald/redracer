<?php

class ResourcesModel extends RedRacerBaseModel
{
	/**
	 * Create a new query
	 *
	 * @return     Doctrine_Query
	 */
	public function getQuery()
	{
		$query = new Doctrine_Query();
		$query->from('Resource resource')
			->select('resource.*, tags.*, licences.*, links.*, releases.*, contributors.*, contributors_user.*')
			->leftJoin('resource.tags AS tags INDEXBY tags.id')
			->leftJoin('resource.licences AS licences INDEXBY licences.id')
			->leftJoin('resource.links AS links INDEXBY links.id')
			->leftJoin('resource.releases AS releases INDEXBY releases.id')
			->leftJoin('resource.contributors AS contributors INDEXBY contributors.id')
			->leftJoin('contributors.user AS contributors_user')
			->addOrderBy('tags.word, contributors.role DESC, releases.recommended DESC, releases.released_at DESC');

		return $query;
	}

	/**
	 * findOneById
	 *
	 * @param      int id
	 *
	 * @return     Resource
	 */
	public function findOneById($id)
	{
		$query = $this->getQuery();
		$query->where('resource.id = ?', array($id));
		return $query->fetchOne();
	}

	/**
	 * findOneByIdent
	 *
	 * @param      string identity
	 *
	 * @return     Resource
	 */
	public function findOneByIdent($ident)
	{
		$query = $this->getQuery();
		$query->where('resource.ident = ?', array($ident));
		return $query->fetchOne();
	}

	public function countByCategory()
	{
		$query = new Doctrine_Query();
		$query->select('resource.category, COUNT(resource.id) AS category_count')
			->from('Resource resource')
			->groupBy('resource.category');

		$ret = array();
		foreach ($query->fetchArray() as $row)
		{
			$ret[$row['category']] = $row['category_count'];
		}
		return $ret;
	}

	public function getDependencies($resource_id, array $release_ids = array(), array $file_ids = array())
	{
		$query = new Doctrine_Query();
		$query->from('Resource resource')
			->select('resource.*, resource.*, files.*, resource_dependencies.id, releases_dependencies.id, files_dependencies.id')
			->leftJoin('resource.releases AS releases INDEXBY releases.id')
			->leftJoin('release.files AS files ON release.id = files.release_id AND files.lft != 1 INDEXBY files.id')
			->leftJoin('resource.dependencies AS resource_dependencies INDEXBY resource_dependencies.id')
			->leftJoin('releases.dependencies AS releases_dependencies INDEXBY releases_dependencies.id')
			->leftJoin('files.dependencies AS files_dependencies INDEXBY files_dependencies.id')
			->addOrderBy('files.lft ASC');

		if (count($release_ids)) {
			$query->andWhereIn('releases.id', $release_ids);
		}
		if (count($file_ids)) {
			$query->andWhereIn('releases.id', $file_ids);
		}

		$query->execute();
	}


}

?>