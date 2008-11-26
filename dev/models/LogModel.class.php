<?php


class LogModel extends RedDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('logs');

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

		$this->hasColumn('title', 'string', 50);
		$this->hasColumn('text', 'string', 500);

		$this->hasColumn('priority', 'integer', 1, array(
			'unsigned' => true,
			'notnull' => true,
			'default' => 0
		) );

		$this->hasColumn('status', 'integer', 1, array(
			'unsigned' => true,
			'notnull' => true,
			'default' => 0
		) );
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