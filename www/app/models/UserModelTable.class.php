<?php


class UserModelTable extends OurDoctrineTable
{

	public function findOneByOpenId($url)
	{
		$q = new Doctrine_Query();

		$q->from('UserModel u')
			->select('u.*, i.*')
			->innerJoin('u.user_ids i')
			->where('i.url = ?', array($url) );

		return $q->fetchOne();
	}

}

?>