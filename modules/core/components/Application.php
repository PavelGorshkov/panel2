<?php
namespace app\modules\core\components;

use app\modules\user\components\BuildAuthManager;
use app\modules\user\components\PhpManager;
use app\modules\user\components\UserManager;
use app\modules\user\components\WebUser;

/**
 * Класс пустышка app(), для указания IDE вируальных
 * свойств (подключаемых компонент) приложения app()
 *
 * Class Application
 * @package app\modules\core\components
 *
 * @property Migrator $migrator
 * @property WebController $controller
 * @property MenuManager $menuManager
 * @property ModuleManager $moduleManager
 * @property PhpManager $authManager
 * @property WebUser $user
 * @property UserManager $userManager
 * @property BuildAuthManager $buildAuthManager
 * @property Thumbnailer $thumbNailer
 * @property WebService $ws
 * @property ADLdapComponent $ldap
 *
 */
class Application extends \yii\web\Application {}