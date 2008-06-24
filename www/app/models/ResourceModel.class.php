<?php
/**
 * Resource
 *
 * @todo	License as extra relation
 *
 */
class ResourceModel extends OurDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('resource');

		$this->hasColumn('id', 'integer', 6, array(
			'autoincrement' => true,
			'unsigned'	=> true,
			'notnull'	=> true,
			'primary'	=> true
		) );

		$this->hasColumn('user_id', 'integer', 6, array(
			'unsigned'	=> true
		) );

		$this->hasColumn('ident', 'string', 32, array(
			'notnull'	=> true,
			'unique'	=> true
		) );
		$this->hasColumn('title', 'string', 50);
		$this->hasColumn('text', 'string');

		$this->hasColumn('version', 'string', 16);
		$this->hasColumn('author', 'string', 24);
		$this->hasColumn('license', 'string', 255);

		$this->hasColumn('views', 'integer', 6, array(
			'unsigned'	=> true,
			'notnull'	=> true,
			'default'	=> 0
		) );

		$this->hasColumn('type', 'integer', 1, array(
			'unsigned'	=> true,
			'notnull'	=> true
		) );

	}

	public function setUp()
	{
		$this->actAs('Timestampable');
		$this->actAs('SoftDelete');

		$this->index('user_id', array('fields' => 'user_id') );
		$this->hasOne('UserModel as user', array(
			'local'		=> 'user_id',
			'foreign'	=> 'id',
			'onDelete'	=> 'CASCADE'
		) );

		$this->hasMany('UserModel as users', array(
			'local'		=> 'user_id',
			'foreign'	=> 'resource_id',
			'refClass'	=> 'StackModel',
			'onDelete'	=> 'SET NULL'
		) );

		$this->hasMany('TagModel as tags', array(
			'local'		=> 'resource_id',
			'foreign'	=> 'tag_id',
			'refClass'	=> 'ResourceTagRefModel'
		) );

		$this->hasMany('ContributorModel as contributors', array(
			'local'		=> 'id',
			'foreign'	=> 'resource_id'
		) );

		$this->hasMany('ReviewModel as reviews', array(
			'local'		=> 'id',
			'foreign'	=> 'resource_id'
		) );

		$this->hasOne('BookmarkModel as bookmark', array(
			'local'		=> 'id',
			'foreign'	=> 'resource_id'
		) );
	}

}

?>