<?php


namespace app\modules\user\models;

/**
 * Class RoleAccess
 * @package app\modules\user\models
 *
 * This is the model class for table "{{%user_access}}".
 *
 * @property string $access
 * @property integer $type
 * @property integer $id
 */
class RoleAccess extends Access
{
    const TYPE = self::TYPE_ROLE;


}