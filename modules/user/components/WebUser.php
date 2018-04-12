<?php

namespace app\modules\user\components;

use app\modules\user\helpers\UserAccessLevelHelper;
use app\modules\user\helpers\UserFlashTrait;
use app\modules\user\interfaces\IdentityInterface;
use app\modules\user\interfaces\UserInterface;
use app\modules\user\models\IdentityUser;
use app\modules\user\models\Profile;
use app\modules\user\models\User;
use yii\base\InvalidConfigException;
use yii\web\User as ParentWebUser;

/**
 * Class WebUser
 * @package app\modules\user\components
 *
 * @property IdentityUser $identity
 * @property-read Profile $profile
 */
class WebUser extends ParentWebUser
{
    const SUCCESS_MESSAGE = 'success';

    const INFO_MESSAGE = 'info';

    const WARNING_MESSAGE = 'warning';

    const ERROR_MESSAGE = 'error';

    protected $_access = null;

    use UserFlashTrait;

    /**
     * @var User
     */
    protected $_profile = null;


    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!($this->isGuest || $this->identity instanceof UserInterface)) {

            throw new InvalidConfigException('Identity User must implements \\app\\modules\\user\\interface\\UserInterface');
        }

        if (!($this->isGuest || $this->identity instanceof IdentityInterface)) {

            throw new InvalidConfigException('Identity User must implements \\app\\modules\\user\\interface\\IdentityInterface');
        }
    }


    /**
     * @param array|string $permissionName
     * @param array $params
     * @param bool $allowCaching
     * @return bool
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        $permissionName = (array)$permissionName;

        foreach ($permissionName as $p) {

            if (parent::can($p, $params, $allowCaching)) return true;
        }

        return false;
    }


    /**
     * Получение информации об авторизованном пользователе
     * @return User
     */
    public function getProfile()
    {
        if ($this->isGuest) return null;

        if ($this->_profile === null) $this->_profile = $this->identity->profile;

        return $this->_profile;
    }


    /**
     * Получение роли авторизованного пользователя
     *
     * @return null|string
     */
    public function getRole()
    {
        if ($this->isGuest) return Roles::GUEST;

        $roles = UserAccessLevelHelper::listRoles();

        if (isset($roles[$this->identity->getAccessLevel()])) {

            return $roles[$this->identity->getAccessLevel()];
        }

        return null;
    }


    /**
     * @return array|null
     */
    public function getAccessData()
    {
        if ($this->isGuest) return [];

        if ($this->_access === null) {

            $this->_access = $this->identity->getAccessPermissions();
        }

        return $this->_access;
    }
}
