<?php


class ResourceModelTable extends RedDoctrineTable
{

	public function findOneById($id)
	{
		$query = $this->getQuery();

		$query->where('resource.id = ?', array($id) );
		$query->limit(1);

		return $query->fetchOne();
	}

	public function findOneByIdent($ident)
	{
		$query = $this->getQuery();

		$query->where('resource.ident = ?', array($ident) );
		$query->limit(1);

		return $query->fetchOne();
	}

	public function findByType($type)
	{
		$query = $this->getQuery();

		$query->where('resource.type = ?', array($type) );

		return $query->fetchOne();
	}

	public function countByType()
	{
		$query = Doctrine_Query::create();

		$query->select('resource.type, COUNT(resource.id) AS num_type')
			->from('ResourceModel resource')
			->groupBy('resource.type');

		$ret = array();

		foreach ($query->fetchArray() as $row)
		{
			$ret[$row['type']] = $row['num_type'];
		}

		return $ret;
	}


	/**
	 * Create query with from statement.
	 *
	 * @return		Doctrine_Query
	 */
	public function getQuery()
	{
		$query = parent::getQuery();

		$query->leftJoin('resource.tags AS tags INDEXBY tags.id');

		$query->leftJoin('resource.links AS links INDEXBY links.id');

		$query->leftJoin('resource.releases AS releases INDEXBY releases.id');
		$query->leftJoin('releases.files');

		$query->leftJoin('resource.contributors AS contributors INDEXBY contributors.id');
		$query->leftJoin('contributors.user AS contributors_user');

		// $query->addOrderBy('tags.word');
		// $query->addOrderBy('links.priority');

		return $query;
	}

}

?>