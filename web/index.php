<?php
use app\modules\core\components\ConfigManager;

error_reporting(E_ALL);
ini_set("display_errors", 1);

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

Yii::setAlias('@app', __DIR__.'/../');

$configManager = new ConfigManager(ConfigManager::ENV_WEB);

(new yii\web\Application($configManager->merge($config)))->run();
