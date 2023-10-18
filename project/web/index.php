<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(is_file(__DIR__.'/../../.env') ? __DIR__.'/../../' : __DIR__.'/../');
$dotenv->load();

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
