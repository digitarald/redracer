<?php


class UserIdModel extends RedDoctrineModel
{
	public function setTableDefinition()
	{
		$this->setTableName('user_ids');

		$this->hasColumn('id', 'integer', 6, array(
			'autoincrement' => true,
			'unsigned' => true,
			'notnull' => true,
			'primary' => true
		) );

		$this->hasColumn('user_id', 'integer', 6, array(
			'unsigned' => true,
			'notnull' => true
		) );

		$this->hasColumn('url', 'string', 255, array(
			'unique' => true
		) );
	}

	public function setUp()
	{
		$this->index('user_id', array('fields' => 'user_id') );
		$this->hasOne('UserModel as user', array(
			'local' => 'user_id',
			'foreign' => 'id',
			'onDelete' => 'CASCADE'
		) );
	}

}

?>