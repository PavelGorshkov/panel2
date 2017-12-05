<?php
use \app\components\ConfigManager;

defined('YII_DEBUG') or define('YII_DEBUG', false);
//defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

Yii::setAlias('@app', __DIR__.'/../');

$confManager = new ConfigManager(ConfigManager::ENV_WEB);

$confManager->merge($config);
printr($confManager, 1);

(new yii\web\Application($config))->run();
