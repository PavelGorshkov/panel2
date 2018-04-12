<?php

namespace app\modules\user\helpers;

use app\modules\core\helpers\ListHelper;
use app\modules\user\components\Roles;
use app\modules\user\interfaces\AccessLevelInterface;
use app\modules\user\models\Role;
use yii\helpers\ArrayHelper;


/**
 * Класс helper для работы с уровнями доступа пользователя
 *
 * Class UserAccessLevel
 * @package app\modules\user\helpers
 */
class UserAccessLevelHelper extends ListHelper
{
    const LEVEL_ADMIN = 1;

    const LEVEL_USER = 0;

    const LEVEL_API = 5;

    /**
     * @var array
     */
    protected static $_accessList;


    /**
     * @return array
     */
    public static function getHtmlList()
    {
        return self::getList();
    }


    /**
     * @return array
     */
    public static function getList()
    {
        return [
            self::LEVEL_ADMIN => 'Aдминистраторы',
            self::LEVEL_USER => 'Пользователи',
            self::LEVEL_API => 'REST пользователь',
        ];
    }


    /**
     * @return array
     */
    public static function getListUFRole()
    {

        if (self::$_accessList === null) {

            self::$_accessList = ArrayHelper::merge(
                self::getList(),
                Role::getList()
            );
        }

        return self::$_accessList;
    }


    /**
     * @return array
     */
    public static function listRoles()
    {
        return [
            self::LEVEL_ADMIN => Roles::ADMIN,
            self::LEVEL_USER => Roles::USER,
        ];
    }


    /**
     * @param AccessLevelInterface $user
     * @return bool
     */
    public static function isAdmin(AccessLevelInterface $user)
    {
        return $user->getAccessLevel() === self::LEVEL_ADMIN;
    }


    /**
     * @param AccessLevelInterface $user
     * @return string
     */
    public static function getUFRole(AccessLevelInterface $user)
    {
        $list = self::getListUFRole();

        return $list[$user->getAccessLevel()] ?? '*неизвестно*';
    }


    /**
     * @param AccessLevelInterface $user
     * @return bool
     */
    public static function isUFRole(AccessLevelInterface $user)
    {
        return $user->getAccessLevel() === self::LEVEL_USER || $user->isUFAccessLevel();
    }
}