<?php


class LinkModel extends OurDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('links');

		$this->hasColumn('user_id', 'integer', 6, array(
			'unsigned'	=> true,
			'notnull'	=> true
		) );

		$this->hasColumn('resource_id', 'integer', 6, array(
			'unsigned'	=> true,
			'notnull'	=> true
		) );

		$this->hasColumn('title', 'string', 50);
		$this->hasColumn('url', 'string', 255);
		$this->hasColumn('text', 'string', 500);

		$this->hasColumn('priority', 'integer', 1, array(
			'unsigned'	=> true,
			'notnull'	=> true
		) );
	}

	public function setUp()
	{
		$this->actAs('Timestampable');

		$this->index('user_id', array('fields' => 'user_id') );
		$this->hasOne('UserModel as user', array(
			'local'		=> 'user_id',
			'foreign'	=> 'id',
			'onDelete'	=> 'CASCADE'
		) );

		$this->index('resource_id', array('fields' => 'resource_id') );
		$this->hasOne('ResourceModel as resource', array(
			'local'		=> 'resource_id',
			'foreign'	=> 'id',
			'onDelete'	=> 'CASCADE'
		) );
	}

}

?>