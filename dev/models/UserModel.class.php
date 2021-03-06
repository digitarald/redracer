<?php


class UserModel extends RedDoctrineModel
{
	public function setTableDefinition()
	{
		$this->setTableName('users');

		$this->hasColumn('id', 'integer', 8, array(
			'autoincrement' => true,
			'unsigned' => true,
			'notnull' => true,
			'primary' => true
		) );

		$this->hasColumn('nickname', 'string', 255);
		$this->hasColumn('password', '64', 256);

		$this->hasColumn('role', 'string', 255);

		$this->hasColumn('email', 'string', 255);
		$this->hasColumn('fullname', 'string', 255);

		$this->hasColumn('dob', 'date');
		$this->hasColumn('postcode', 'string', 24);
		$this->hasColumn('country', 'string', 2);
		$this->hasColumn('language', 'string', 2);
		$this->hasColumn('timezone', 'string', 24);

		$this->hasColumn('github_user', 'string', 64);
		$this->hasColumn('paypal_user', 'string', 255);

		$this->hasColumn('login_at', 'timestamp');
		$this->hasColumn('login_ip', 'string', 15);
	}

	public function setUp()
	{
		$this->actAs('Geographical');
		$this->actAs('Timestampable');
		$this->actAs('SoftDelete');

		$this->hasMany('UserIdModel as user_ids', array(
			'local' => 'id',
			'foreign' => 'user_id'
		) );

		$this->hasMany('UserTokenModel as user_tokens', array(
			'local' => 'id',
			'foreign' => 'user_id'
		) );

		$this->hasMany('LogModel as logs', array(
			'local' => 'id',
			'foreign' => 'user_id'
		) );
	}

	public function setPassword($word) {
		$this->_set('password', hash('sha256', $word));
	}

	public function getDisplayName()
	{
		$nickname = false;
		if ($this['nickname']) {
			$nickname = $nickname;
		}
		if ($this['fullname']) {
			if ($nickname) {
				return $this['fullname'] . ' (' . $nickname . ')';
			}
			return $this['fullname'];
		}
		if ($nickname) {
			return $nickname;
		}

		$url = $this['user_tokens'][0];
		if ($url && $url->exists()) {
			return htmlspecialchars($url['url']);
		}

		return $this['id'];
	}


	public function toArray($deep = true, $prefixKey = false)
	{
		$ret = parent::toArray($deep, $prefixKey);

		$ret['display_name'] = $this->getDisplayName();

		$ret['url'] = $this->context->getRouting()->gen('people.person.view', array(
			'id' => $ret['id']
		) );
		$ret['url_avatar'] = 'http://www.gravatar.com/avatar/' . md5($ret['email']) . '?s=65&amp;d=http%3A%2F%2Four.digitarald.com%2Fimages%2Fgravatar.gif';

		return $ret;
	}

}

?>