<?php


class DownloadModel extends OurDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('downloads');

		$this->hasColumn('user_id', 'integer', 6, array(
			'unsigned' => true
		) );
		$this->hasColumn('resource_id', 'integer', 6, array(
			'unsigned' => true
		) );
		$this->hasColumn('release_id', 'integer', 6, array(
			'unsigned' => true
		) );
	}


	public function setUp()
	{
		$this->actAs('Timestampable');

		$this->index('user_id', array('fields' => 'user_id') );
		$this->hasOne('UserModel as user', array(
			'local'		=> 'user_id',
			'foreign'	=> 'id',
			'onDelete'	=> 'SET NULL'
		) );

		$this->index('resource_id', array('fields' => 'resource_id') );
		$this->hasOne('ResourceModel as resource', array(
			'local'		=> 'resource_id',
			'foreign'	=> 'id',
			'onDelete'	=> 'SET NULL'
		) );

		$this->index('release_id', array('fields' => 'release_id') );
		$this->hasOne('ReleaseModel as release', array(
			'local'		=> 'resource_id',
			'foreign'	=> 'id',
			'onDelete'	=> 'SET NULL'
		) );
	}

}

?>