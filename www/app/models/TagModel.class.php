<?php


class TagModel extends OurDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('tags');

		$this->hasColumn('id', 'integer', 6, array(
			'autoincrement' => true,
			'unsigned' => true,
			'notnull' => true,
			'primary' => true
		) );

		$this->hasColumn('name', 'string', 255, array(
			'unique' => true
		) );
	}

	public function setUp()
	{
		$this->hasMany('ResourceModel as rescources', array(
			'local'		=> 'tag_id',
			'foreign'	=> 'resource_id',
			'refClass'	=> 'ResourceTagRefModel'
		) );
	}

}

?>