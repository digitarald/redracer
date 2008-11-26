<?php

class HitsModel extends RedRacerBaseModel
{
	/**
	 * Create a new query
	 *
	 * @return     Doctrine_Query
	 */
	public function getQuery()
	{
		$query = new Doctrine_Query();
		$query->from('Hit hit')
			->select('hit.*');
		return $query;
	}

	public function checkHit($resource_id)
	{
		$update = array(
			'updated_at' => date('Y-m-d H:i:s'),
			'resource_id' => $resource_id
		);

		$query = new Doctrine_Query();
		$query->update('Hit hit')
			->set('hit.updated_at', ':updated_at')
			->addWhere('hit.resource_id = :resource_id')
			->addWhere(sprintf("hit.updated_at > '%s'", date('Y-m-d H:i:s', strtotime('-1 day'))));

		$user = $this->getContext()->getUser();
		if ($user->isAuthenticated()) {
			$query->addWhere('user_id = :user_id');
			$update['user_id'] = $user->getAttribute('id', 'org.redracer.user');
		} else {
			$query->addWhere('session = :session');
			$update['session'] = session_id();
		}

		$query->limit(1);
/*
		if ($query->execute($update)) {
			return true;
		}
		$hit = new Hit();
		$hit->merge($update);
		$hit->trySave();
		return false;
*/
	}

}

?>