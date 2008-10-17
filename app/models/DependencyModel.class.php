<?php


class DependencyModel extends RedDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('dependencies');

		$this->hasColumn('user_id', 'integer', 6, array(
			'unsigned' => true,
			'notnull' => true
		) );
		$this->hasColumn('resource_id', 'integer', 6, array(
			'unsigned' => true
		) );
		$this->hasColumn('release_id', 'integer', 6, array(
			'unsigned' => true
		) );
		$this->hasColumn('file_id', 'integer', 6, array(
			'unsigned' => true
		) );

		$this->hasColumn('text', 'string', 500);
		$this->hasColumn('target', 'string', 255);

		$this->hasColumn('target_resource_id', 'integer', 6, array(
			'unsigned' => true
		) );
		$this->hasColumn('target_release_id', 'integer', 6, array(
			'unsigned' => true
		) );
		$this->hasColumn('target_file_id', 'integer', 6, array(
			'unsigned' => true
		) );
		$this->hasColumn('target_url', 'string', 255);
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

		$this->index('release_id', array('fields' => 'release_id') );
		$this->hasOne('ReleaseModel as release', array(
			'local' => 'release_id',
			'foreign' => 'id',
			'onDelete' => 'CASCADE'
		) );

		$this->index('file_id', array('fields' => 'file_id') );
		$this->hasOne('FileModel as file', array(
			'local' => 'file_id',
			'foreign' => 'id',
			'onDelete' => 'CASCADE'
		) );


		$this->index('target_resource_id', array('fields' => 'resource_id') );
		$this->hasOne('ResourceModel as target_resource', array(
			'local' => 'target_resource_id',
			'foreign' => 'id',
			'onDelete' => 'CASCADE'
		) );

		$this->index('target_release_id', array('fields' => 'release_id') );
		$this->hasOne('ReleaseModel as target_release', array(
			'local' => 'target_release_id',
			'foreign' => 'id',
			'onDelete' => 'CASCADE'
		) );

		$this->index('target_file_id', array('fields' => 'file_id') );
		$this->hasOne('FileModel as target_file', array(
			'local' => 'target_file_id',
			'foreign' => 'id',
			'onDelete' => 'CASCADE'
		) );
	}

	public function toArray($deep = true, $prefixKey = false)
	{
		$ret = parent::toArray($deep, $prefixKey);

		$ret['url_edit'] = $this->context->getRouting()->gen('resources.resource.dependency.edit', array(
			'ident' => $this['resource']['ident'],
			'id' => $ret['id']
		) );

		return $ret;
	}

}

?>