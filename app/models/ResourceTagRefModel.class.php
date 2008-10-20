<?php


class ResourceTagRefModel extends RedDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('resource_tag_ref');

		$this->hasColumn('id', 'integer', 8, array(
			'autoincrement' => true,
			'unsigned' => true,
			'notnull' => true,
			'primary' => true
		) );

		$this->hasColumn('tag_id', 'integer', 8, array(
			'unsigned' => true,
			'notnull' => true,
			'primary' => true
		) );
		$this->hasColumn('resource_id', 'integer', 8, array(
			'unsigned' => true,
			'notnull' => true,
			'primary' => true
		) );
	}

	public function setUp()
	{
		$this->index('tag_id', array('fields' => 'tag_id') );
		$this->hasOne('TagModel as user', array(
			'local' => 'tag_id',
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

	/**
	 * @param 	Doctrine_Event
	 */
	public function postInsert($event)
	{
		$query = Doctrine_Query::create();

		$query->update('TagModel')
			->set('count', 'count + 1')
			->where('id = ?', array($this['tag_id']) )
			->execute();
	}

	/**
	 * @param 	Doctrine_Event
	 */
	public function postDelete($event)
	{
		$query = Doctrine_Query::create();

		$query->update('TagModel')
			->set('count', 'count - 1')
			->where('id = ?', array($this['tag_id']) )
			->execute();
	}


}

?>