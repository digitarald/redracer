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
		$this->hasColumn('unclaimed', 'boolean', array(
			'default'	=> 1
		) );
		$this->hasColumn('author', 'string', 255);

		$this->hasColumn('ident', 'string', 32, array(
			'notnull'	=> true,
			'unique'	=> true
		) );
		$this->hasColumn('title', 'string', 50);
		$this->hasColumn('text', 'string');

		$this->hasColumn('version', 'string', 16);
		$this->hasColumn('license_url', 'string', 255);
		$this->hasColumn('license_text', 'string', 255);

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

		$this->index('type', array('fields' => 'type') );

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

		$this->hasMany('ResourceTagRefModel as tag_refs', array(
			'local'		=> 'id',
			'foreign'	=> 'resource_id'
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

		$this->hasMany('LinkModel as links', array(
			'local'		=> 'id',
			'foreign'	=> 'resource_id'
		) );

	}

	public function toArray($deep = true, $prefixKey = false)
	{
		$ret = parent::toArray($deep, $prefixKey);

		$ret['text_html'] = OurString::format($ret['text']);

		$ret['url'] = $this->context->getRouting()->gen('hub.resource', array(
			'ident'	=> $ret['ident']
		) );
		$ret['url_contributor'] = $this->context->getRouting()->gen('hub.resource.contributor', array(
			'ident'	=> $ret['ident']
		) );
		$ret['url_link'] = $this->context->getRouting()->gen('hub.resource.link', array(
			'ident'	=> $ret['ident']
		) );
		$ret['url_edit'] = $this->context->getRouting()->gen('hub.resource.edit', array(
			'ident'	=> $ret['ident']
		) );


		return $ret;
	}

}

?>