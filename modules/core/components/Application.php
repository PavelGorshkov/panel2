<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 08.12.2017
 * Time: 11:52
 */

namespace app\modules\core\components;

/**
 * Class Application
 * @package app\modules\core\components
 *
 * @property Migrator $migrator
 * @property WebController $controller
 * @property ModuleManager $moduleManager
 * @property \app\modules\user\components\PhpManager $authManager
 * @property \app\modules\user\components\WebUser $user
 * @property \app\modules\user\components\Usermanager $userManager
 * @property \app\modules\user\components\BuildAuthManager $buildAuthManager
 *
 */
class Application extends \yii\web\Application {}