<?php

use yii\db\Connection;

$host = getenv('MARIADB_HOST', true);
$database = getenv('MARIADB_DATABASE', true);
$user = getenv('MARIADB_USER', true);
$password = getenv('MARIADB_PASSWORD', true);

return [
    'class' => Connection::class,
    'dsn' => "mysql:host={$host};dbname={$database}",
    'username' => $user,
    'password' => $password,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
