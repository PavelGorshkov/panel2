<?php
namespace app\modules\user\components;

use yii\rbac\Item;

/**
 * Class Roles
 * @package app\modules\user\components
 */
class Roles extends RBACItem {

    const ADMIN = 'admin';

    const REDACTOR = 'redactor';

    const OBSERVER = 'observer';

    const USER = 'user';

    const GUEST = 'guest';


    public $types = [
        self::ADMIN => Item::TYPE_ROLE,
        self::REDACTOR => Item::TYPE_ROLE,
        self::OBSERVER => Item::TYPE_ROLE,
        self::USER => Item::TYPE_ROLE,
        self::GUEST => Item::TYPE_ROLE,
    ];


    /**
     * @return array
     */
    public function titleList() {

        return [
            self::ADMIN => 'Администратор',
            self::REDACTOR => 'Редактор',
            self::OBSERVER => 'Наблюдатель',
            self::USER => 'Пользователь',
            self::GUEST => 'Гость',
        ];
    }


    /**
     * @return array
     */
    public function getTree() {

        return [
            self::GUEST => [],
            self::USER => [
                self::GUEST,
            ],
            self::OBSERVER => [
                self::GUEST,
            ],
            self::REDACTOR => [
                self::GUEST,
            ],
            self::ADMIN => [
                self::GUEST,
                self::OBSERVER,
                self::REDACTOR,
                self::USER,
            ],
        ];
    }


    /**
     * @return null
     */
    public function getTitleTask() {

        return null;
    }
}