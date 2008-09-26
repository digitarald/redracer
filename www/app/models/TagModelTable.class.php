<?php


class TagModelTable extends OurDoctrineTable
{

	public function findByWord($word)
	{
		$query = $this->getQuery();

		$query->where('tag.word = ?', array($word) );

		return $query->fetchOne();
	}

}

?>