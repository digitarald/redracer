<?php


class ContributorModel extends RedDoctrineModel
{

	public function setTableDefinition()
	{
		$this->setTableName('contributors');

		$this->hasColumn('id', 'integer', 6, array(
			'autoincrement' => true,
			'unsigned' => true,
			'notnull' => true,
			'primary' => true
		) );

		$this->hasColumn('user_id', 'integer', 6, array(
			'unsigned' => true,
			'notnull' => true
		) );

		$this->hasColumn('resource_id', 'integer', 6, array(
			'unsigned' => true,
			'notnull' => true
		) );

		$this->hasColumn('verified', 'boolean', array(
			'notnull' => true
		) );

		$this->hasColumn('title', 'string', 50);
		$this->hasColumn('text', 'string', 500);
	}

	public function setUp()
	{
		$this->actAs('Timestampable');

		$this->index('user_id', array('fields' => 'user_id') );
		$this->hasOne('UserModel as user', array(
			'local'		=> 'user_id',
			'foreign'	=> 'id',
			'onDelete'	=> 'CASCADE'
		) );

		$this->hasOne('ResourceModel as resource', array(
			'local'		=> 'resource_id',
			'foreign'	=> 'id',
			'onDelete'	=> 'CASCADE'
		) );
	}

	public function toArray($deep = true, $prefixKey = false)
	{
		$ret = parent::toArray($deep, $prefixKey);

		$ret['text_html'] = RedString::format($ret['text'], 4);

		$ret['url_edit'] = $this->context->getRouting()->gen('resources.resource.contributors.contributor.edit', array(
			'ident'	=> $this['resource']['ident'],
			'id'	=> $ret['id']
		) );

		return $ret;
	}

}

?>