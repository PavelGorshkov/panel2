<?php

namespace app\modules\user\components;

use yii\rbac\Item;


/**
 * Class Roles
 * @package app\modules\user\components
 */
class Roles extends RbacItem
{
    const ADMIN = 'admin';

    const USER = 'user';

    const LDAP = 'ldap';

    const GUEST = 'guest';


    public $types = [
        self::ADMIN => Item::TYPE_ROLE,
        self::USER => Item::TYPE_ROLE,
        self::GUEST => Item::TYPE_ROLE,
        self::LDAP => Item::TYPE_ROLE,
    ];


    /**
     * @return array
     */
    public function titleList()
    {

        return [
            self::ADMIN => 'Администратор',
            self::USER => 'Пользователь',
            self::LDAP => 'LDAP пользователь',
            self::GUEST => 'Гость',
        ];
    }


    /**
     * @return array
     */
    public function getTree()
    {

        return [
            self::GUEST => [],
            self::LDAP => [
                self::GUEST,
            ],
            self::USER => [
                self::GUEST,
            ],
            self::ADMIN => [
                self::GUEST,
                self::USER,
                self::LDAP,
            ],
        ];
    }


    /**
     * @return null
     */
    public function getTitleTask()
    {

        return null;
    }
}