<?php


class ReleaseModel extends RedDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('releases');

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

		$this->hasColumn('version', 'string', 50);
		$this->hasColumn('stage', 'integer', 1, array(
			'unsigned' => true,
			'notnull' => true,
			'default' => 0
		) );
		$this->hasColumn('text', 'string', 500);
		$this->hasColumn('changelog', 'string');

		$this->hasColumn('hit_count', 'integer', 8, array(
			'unsigned' => true,
			'notnull' => true,
			'default' => 0
		) );

		$this->hasColumn('url_package', 'string', 255);
		$this->hasColumn('url_repository', 'string', 255);
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

		$this->index('resource_id', array('fields' => 'resource_id') );
		$this->hasOne('ResourceModel as resource', array(
			'local' => 'resource_id',
			'foreign' => 'id',
			'onDelete' => 'CASCADE'
		) );

		$this->hasMany('DependencyModel as dependencies', array(
			'local' => 'id',
			'foreign' => 'release_id'
		) );

		$this->hasMany('FileModel as files', array(
			'local' => 'id',
			'foreign' => 'release_id'
		) );
	}

	public function toArray($deep = true, $prefixKey = false)
	{
		$ret = parent::toArray($deep, $prefixKey);

		$ret['url_edit'] = $this->context->getRouting()->gen('resources.resource.release.edit', array(
			'ident' => $this['resource']['ident'],
			'id' => $ret['id']
		) );

		return $ret;
	}

}

?>