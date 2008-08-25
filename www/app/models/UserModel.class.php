<?php


class UserModel extends OurDoctrineModel
{
	public function setTableDefinition()
	{
		$this->setTableName('users');

		$this->hasColumn('id', 'integer', 6, array(
			'autoincrement' => true,
			'unsigned'	=> true,
			'notnull'	=> true,
			'primary'	=> true
		) );

		$this->hasColumn('nickname', 'string', 255, array(
			'unique'	=> true
		) );

		$this->hasColumn('role', 'string', 255);

		$this->hasColumn('email', 'string', 255);
		$this->hasColumn('fullname', 'string', 255);

		$this->hasColumn('dob', 'date');
		$this->hasColumn('postcode', 'string', 24);
		$this->hasColumn('country', 'string', 2);
		$this->hasColumn('language', 'string', 2);
		$this->hasColumn('timezone', 'string', 24);

		$this->hasColumn('login_at', 'timestamp');
		$this->hasColumn('login_ip', 'string', 15);
	}

	public function setUp()
	{
		$this->actAs('Geographical');
		$this->actAs('Timestampable');
		$this->actAs('SoftDelete');

		$this->hasMany('UserIdModel as user_ids', array(
			'local'		=> 'id',
			'foreign'	=> 'user_id'
		) );
	}

}

?>