<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseResource extends RedRacerDoctrineRecord
{
  public function setTableDefinition()
  {
    $this->setTableName('resources');
    $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'primary' => true, 'autoincrement' => true, 'unsigned' => true, 'length' => '4'));
    $this->hasColumn('ident', 'string', 30, array('type' => 'string', 'notnull' => true, 'unique' => true, 'length' => '30'));
    $this->hasColumn('user_id', 'integer', 4, array('type' => 'integer', 'unsigned' => true, 'length' => '4'));
    $this->hasColumn('flag', 'integer', 3, array('type' => 'integer', 'unsigned' => true, 'notnull' => true, 'default' => 0, 'length' => '3'));
    $this->hasColumn('category', 'integer', 1, array('type' => 'integer', 'unsigned' => true, 'notnull' => true, 'default' => 0, 'length' => '1'));
    $this->hasColumn('stability', 'integer', 1, array('type' => 'integer', 'unsigned' => true, 'notnull' => true, 'default' => 0, 'length' => '1'));
    $this->hasColumn('title', 'string', 50, array('type' => 'string', 'length' => '50'));
    $this->hasColumn('description', 'string', 500, array('type' => 'string', 'length' => '500'));
    $this->hasColumn('readme', 'string', null, array('type' => 'string'));
    $this->hasColumn('copyright', 'string', 50, array('type' => 'string', 'length' => '50'));
    $this->hasColumn('url_homepage', 'string', 250, array('type' => 'string', 'length' => '250'));
    $this->hasColumn('url_feed', 'string', 250, array('type' => 'string', 'length' => '250'));
    $this->hasColumn('url_source', 'string', 250, array('type' => 'string', 'length' => '250'));
    $this->hasColumn('url_download', 'string', 250, array('type' => 'string', 'length' => '250'));
    $this->hasColumn('url_demo', 'string', 250, array('type' => 'string', 'length' => '250'));
    $this->hasColumn('url_support', 'string', 250, array('type' => 'string', 'length' => '250'));
    $this->hasColumn('url_discussion', 'string', 250, array('type' => 'string', 'length' => '250'));
    $this->hasColumn('hits_count', 'integer', 4, array('type' => 'integer', 'unsigned' => true, 'notnull' => true, 'default' => 0, 'length' => '4'));
    $this->hasColumn('downloads_count', 'integer', 4, array('type' => 'integer', 'unsigned' => true, 'notnull' => true, 'default' => 0, 'length' => '4'));
    $this->hasColumn('rating_average', 'integer', 1, array('type' => 'integer', 'unsigned' => true, 'notnull' => true, 'default' => 0, 'length' => '1'));
    $this->hasColumn('ratings_count', 'integer', 4, array('type' => 'integer', 'unsigned' => true, 'notnull' => true, 'default' => 0, 'length' => '4'));


    $this->index('category_idx', array('fields' => 'category'));
    $this->index('flag_idx', array('fields' => 'flag'));

    $this->setAttribute(Doctrine::ATTR_EXPORT, Doctrine::EXPORT_ALL);

    $this->option('collate', 'utf8_unicode_ci');
    $this->option('charset', 'utf8');
  }

  public function setUp()
  {
    $this->hasOne('User as user', array('local' => 'user_id',
                                        'foreign' => 'id',
                                        'onDelete' => 'SET NULL'));

    $this->hasMany('Licence as licences', array('refClass' => 'ResourceLicenceRef',
                                                'local' => 'resource_id',
                                                'foreign' => 'licence_id'));

    $this->hasMany('Tag as tags', array('refClass' => 'ResourceTagRef',
                                        'local' => 'resource_id',
                                        'foreign' => 'tag_id'));

    $this->hasMany('Comment as comments', array('local' => 'id',
                                                'foreign' => 'resource_id'));

    $this->hasMany('Contributor as contributors', array('local' => 'id',
                                                        'foreign' => 'resource_id'));

    $this->hasMany('Dependency as dependencies', array('local' => 'id',
                                                       'foreign' => 'resource_id'));

    $this->hasMany('Dependency as target_dependencies', array('local' => 'id',
                                                              'foreign' => 'target_resource_id'));

    $this->hasMany('Diary as events', array('local' => 'id',
                                            'foreign' => 'resource_id'));

    $this->hasMany('Hit as hits', array('local' => 'id',
                                        'foreign' => 'resource_id'));

    $this->hasMany('Link as links', array('local' => 'id',
                                          'foreign' => 'resource_id'));

    $this->hasMany('Release as releases', array('local' => 'id',
                                                'foreign' => 'resource_id'));

    $this->hasMany('ResourceLicenceRef as resource_licence_ref', array('local' => 'id',
                                                                       'foreign' => 'resource_id'));

    $this->hasMany('ResourceTagRef as resource_tag_ref', array('local' => 'id',
                                                               'foreign' => 'resource_id'));

    $this->hasMany('Snippet as snippets', array('local' => 'id',
                                                'foreign' => 'resource_id'));

    $timestampable0 = new Doctrine_Template_Timestampable();
    $versionable0 = new Doctrine_Template_Versionable();
    $this->actAs($timestampable0);
    $this->actAs($versionable0);
  }
}