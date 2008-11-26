<?php

class UsersModel extends RedRacerBaseModel
{
	/**
	 * Create a new query
	 *
	 * @return     Doctrine_Query
	 */
	public function getQuery()
	{
		$query = new Doctrine_Query();
		$query->from('User user')
			->select('user.*, auths.*')
			->leftJoin('user.user_auths auths');
		return $query;
	}

	/**
	 * findOneById
	 *
	 * @param      int id
	 *
	 * @return     User
	 */
	public function findOneById($id)
	{
		$query = $this->getQuery();
		$query->where('user.id = ?', array($id));
		return $query->fetchOne();
	}

	/**
	 * findOneByIdentity
	 *
	 * @param      string identity
	 *
	 * @return     User
	 */
	public function findOneByIdentity($identity)
	{
		$query = $this->getQuery();
		$query->where('auths.identifier = ?', array($identity));
		return $query->fetchOne();
	}

	public function findOneByEmail($email)
	{
		$query = $this->getQuery();
		$query->where('user.email = ?', array($email));
		return $query->fetchOne();
	}

	public function findOneByToken($token)
	{
		$due = date('Y-m-d H:i:s', strtotime('-' . AgaviConfig::get('org.redracer.config.account.autologin_lifetime')));

		$query = new Doctrine_Query();
		$query->from('UserToken token')
			->where('token.created_at < ?', array($due));
		$query->delete();

		$query = new Doctrine_Query();
		$query->from('UserToken token')
			->select('token.*')
			->where('token.token = ?', array($token));
		$token = $query->fetchOne();

		if (!$token) {
			return null;
		}
		$user = $this->findOneById($token['user_id']);
		$token->delete();

		return $user;
	}
}

?>