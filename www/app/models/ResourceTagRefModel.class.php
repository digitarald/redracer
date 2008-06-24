<?php


class ResourceTagRefModel extends OurDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('resource_tag_ref');

		$this->hasColumn('tag_id', 'integer', 6, array(
			'unsigned' => true,
			'notnull' => true,
			'primary' => true
		) );

		$this->hasColumn('resource_id', 'integer', 6, array(
			'unsigned' => true,
			'notnull' => true,
			'primary' => true
		) );
	}

	public function setUp()
	{
		$this->index('tag_id', array('fields' => 'tag_id') );
		$this->hasOne('TagModel as user', array(
			'local'		=> 'tag_id',
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