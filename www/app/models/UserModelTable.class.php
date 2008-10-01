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

	public function findOneEmail($email)
	{
		$q = new Doctrine_Query();

		$q->from('UserModel u')
			->select('u.*, i.*')
			->innerJoin('u.user_ids i')
			->where('u.email = ?', array($email) );

		return $q->fetchOne();
	}

	public function findOneByToken($token)
	{
		$q = new Doctrine_Query();
		$q->from('UserTokenModel t')
			->where('created_at < ' . date('Y-m-d H:i:s', strtotime('-' . AgaviConfig::get('core.remember_expire') ) ) );

		$q->delete();

		$q = new Doctrine_Query();
		$token = $q->from('UserTokenModel t')
			->select('t.*')
			->where('t.token = ?', array($token) );

		$token = $token->fetchOne();

		if (!$token)
		{
			return null;
		}

		$q->from('UserModel u')
			->select('u.*')
			->where('u.id = ?', array($token['user_id']) );

		$token->delete();

		return $q->fetchOne();
	}

}

?>