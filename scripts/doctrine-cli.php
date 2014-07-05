<?php
require dirname ( __FILE__ ) . '/../application/init.php';
$config = array (
		'data_fixtures_path' => DATA_FIXTURES_PATH,
		'models_path' => MODELS_PATH,
		'migrations_path' => MIGRATIONS_PATH,
		'sql_path' => SQL_PATH,
		'yaml_schema_path' => YAML_SCHEMA_PATH 
);

$cli = new Doctrine_Cli ( $config );

$cli->run ( $_SERVER ['argv'] );