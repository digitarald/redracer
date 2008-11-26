<?php

// Backup argv, otherwise stripped by agavi
$args = $_SERVER['argv'];

require('../vendor/agavi/agavi.php');
require('../app/config.php');

Agavi::bootstrap('development-digitarald');

spl_autoload_register(array('Doctrine', 'autoload'));

$con = AgaviContext::getInstance('console')->getDatabaseConnection();

$dir = dirname(__FILE__);

// Configure Doctrine Cli
$cli = new Doctrine_Cli(array(
	'data_fixtures_path'  => AgaviConfig::get('doctrine.fixture_dir', $dir . '/doctrine/fixtures'),
	'models_path' => AgaviConfig::get('core.lib_dir') . '/doctrine/models',
	'migrations_path' =>  AgaviConfig::get('doctrine.migration_dir', $dir . '/doctrine/migrations'),
	'sql_path' => AgaviConfig::get('doctrine.migration_dir', $dir . '/doctrine/sql'),
	'yaml_schema_path' =>  AgaviConfig::get('doctrine.schema_dir', $dir . '/doctrine/schema.yml'),
	'generate_models_options' => array(
		'base_class_name' => 'RedRacerDoctrineRecord',
		'suffix' => '.class.php'
	)
));
$cli->run($args);

?>