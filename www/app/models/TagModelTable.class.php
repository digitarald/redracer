<?php


class TagModelTable extends OurDoctrineTable
{

	public function findOneByWord($word)
	{
		$query = $this->getQuery();

		$query->where('tag.word = ?', array($word) );

		return $query->fetchOne();
	}

}

?>