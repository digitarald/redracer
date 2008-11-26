<?php


class UserTokenModel extends RedDoctrineModel
{
	public function setTableDefinition()
	{
		$this->setTableName('user_tokens');

		$this->hasColumn('id', 'integer', 8, array(
			'autoincrement' => true,
			'unsigned' => true,
			'notnull' => true,
			'primary' => true
		) );

		$this->hasColumn('user_id', 'integer', 8, array(
			'unsigned' => true,
			'notnull' => true
		) );

		$this->hasColumn('token', 'string', 32, array(
			'unique' => true
		) );

		$this->hasColumn('ip', 'string', 12);
	}

	public function setUp()
	{
		$this->actAs('Timestampable');

		$this->index('user_id', array('fields' => 'user_id') );
		$this->hasOne('UserModel as user', array(
			'local' => 'user_id',
			'foreign' => 'id',
			'onDelete' => 'CASCADE'
		) );
	}

}

?>