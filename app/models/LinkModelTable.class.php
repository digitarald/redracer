<?php


class LinkModelTable extends OurDoctrineTable
{

	public function findOneById($id)
	{
		$query = $this->getQuery();

		$query->where('link.id = ?', array($id) );

		return $query->fetchOne();
	}

}

?>