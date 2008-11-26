<?php

class LicencesModel extends RedRacerBaseModel
{
	/**
	 * Create a new query
	 *
	 * @return     Doctrine_Query
	 */
	public function getQuery()
	{
		$query = new Doctrine_Query();
		$query->from('Licence licence')
			->select('licence.*')
			->addOrderBy('licence.title');
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
	 * @param      int Id
	 * @return     Licence
	 */
	public function findById($id)
	{
		$query = $this->getQuery();
		$query->where('licence.id = ?', array($id));
		return $query->fetchOne();
	}
}

?>