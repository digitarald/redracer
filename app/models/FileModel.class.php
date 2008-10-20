<?php


class FileModel extends RedDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('files');

		$this->hasColumn('id', 'integer', 8, array(
			'autoincrement' => true,
			'unsigned' => true,
			'notnull' => true,
			'primary' => true
		) );

		$this->hasColumn('user_id', 'integer', 8, array(
			'unsigned' => true
		) );
		$this->hasColumn('release_id', 'integer', 8, array(
			'unsigned' => true
		) );

		$this->hasColumn('path', 'string', 255);
		$this->hasColumn('name', 'string', 255);
		$this->hasColumn('text', 'string', 500);
		$this->hasColumn('size', 'integer', 8, array(
			'unsigned' => true
		) );

		$this->hasColumn('url', 'string', 255);
		$this->hasColumn('type', 'integer', 1, array(
			'unsigned' => true
		) );
	}

	public function setUp()
	{
		$this->actAs('Timestampable');

		$this->index('user_id', array('fields' => 'user_id') );
		$this->hasOne('UserModel as user', array(
			'local' => 'user_id',
			'foreign' => 'id',
			'onDelete' => 'SET NULL'
		) );

		$this->index('release_id', array('fields' => 'release_id') );
		$this->hasOne('ReleaseModel as release', array(
			'local' => 'release_id',
			'foreign' => 'id',
			'onDelete' => 'SET NULL'
		) );

		$this->hasMany('DependencyModel as dependencies', array(
			'local' => 'id',
			'foreign' => 'file_id'
		) );

	}

}

?>