<?php
/**
 * CommentModel
 */

class CommentModel extends RedDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('comments');

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

		$this->hasColumn('resource_id', 'integer', 8, array(
			'unsigned' => true,
			'notnull' => true
		) );

		$this->hasColumn('status', 'integer', 1, array(
			'unsigned' => true,
			'notnull' => true,
			'default' => 0
		) );
		$this->hasColumn('priority', 'integer', 1, array(
			'unsigned' => true,
			'notnull' => true,
			'default' => 0
		) );
		$this->hasColumn('flags', 'array');

		$this->hasColumn('title', 'string', 50, array(
			'unsigned' => true,
			'notnull' => true
		) );
		$this->hasColumn('text', 'string', 500, array(
			'unsigned' => true,
			'notnull' => true
		) );

		$this->hasColumn('type', 'integer', 1, array(
			'unsigned' => true,
			'notnull' => true,
			'default' => 0
		) );
	}

	public function setUp()
	{
		$this->actAs('Timestampable');
		$this->actAs('NestedSet', array(
			'hasManyRoots' => true, // enable many roots
			'rootColumnName' => 'root_id'
		) );

		$this->index('status', array('fields' => 'status') );
		$this->index('type', array('fields' => 'type') );

		$this->index('user_id', array('fields' => 'user_id') );
		$this->hasOne('UserModel as user', array(
			'local' => 'user_id',
			'foreign' => 'id',
			'onDelete' => 'CASCADE'
		) );

		$this->index('resource_id', array('fields' => 'resource_id') );
		$this->hasOne('ResourceModel as resource', array(
			'local' => 'resource_id',
			'foreign' => 'id',
			'onDelete' => 'CASCADE'
		) );
	}

}

?>