<?php


class ReleaseModelTable extends RedDoctrineTable
{

	public function findOneById($id)
	{
		$query = $this->getQuery();

		$query->where('release.id = ?', array($id) );
		$query->limit(1);

		return $query->fetchOne();
	}

}

?>