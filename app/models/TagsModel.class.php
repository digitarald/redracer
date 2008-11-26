<?php

class TagsModel extends RedRacerBaseModel
{
	/**
	 * Create a new query
	 *
	 * @return     Doctrine_Query
	 */
	public function getQuery()
	{
		$query = new Doctrine_Query();
		$query->from('Tag tag')
			->select('tag.*')
			->addOrderBy('tag.word');
		return $query;
	}

	/**
	 * findAll
	 *
	 * @return     Doctrine_Collection
	 */
	public function findAll()
	{
		$query = $this->getQuery();
		return $query->execute();
	}

	/**
	 * findByCount
	 *
	 * @return     Doctrine_Collection
	 */
	public function findByCount()
	{
		$query = $this->getQuery();
		$query->where('resources_count > 0');
		return $query->execute();
	}

	/**
	 * findByCount
	 *
	 * @param      string Word
	 * @return     Tag
	 */
	public function findOneByWord($word)
	{
		$query = $this->getQuery();
		$query->where('tag.word = ?', array($word) );
		return $query->fetchOne();
	}


}

?>