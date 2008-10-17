<?php


class ResourceChildModel extends RedDoctrineModel
{

	public function setTableDefinition()
	{
		$this->hasColumn('resource_id', 'integer', 6, array(
			'unsigned' => true,
			'notnull' => true,
			'primary' => true
		) );
	}

	public function setUp()
	{
		$this->hasOne('ResourceModel as resource', array(
			'local' => 'resource_id',
			'foreign' => 'id',
			'onDelete' => 'CASCADE'
		) );
	}

}

?>