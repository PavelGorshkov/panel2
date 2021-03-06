<?php

namespace app\modules\user\models;

use app\modules\user\helpers\ModuleTrait;
use app\modules\user\interfaces\IdentityInterface;

/**
 * Class IdentityUser
 * @package app\modules\user\models
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $avatar
 * @property string $about
 * @property string $phone
 */
class IdentityUser extends User implements IdentityInterface
{
    use ModuleTrait;

    /**
     * @param int|string $id
     * @return null|IdentityInterface|static
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return void|IdentityInterface
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    /**
     * @return int|mixed|string
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }


    /**
     * @return mixed|string
     */
    public function getAuthKey()
    {
        return $this->getAttribute('auth_key');
    }


    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    /**
     * @param string $usernameOrEmail
     * @return IdentityInterface
     */
    public static function findByUsernameOrEmail($usernameOrEmail)
    {
        return self::find()->active()->where(
            'username = :user OR email = :user', [':user' => $usernameOrEmail]
        )->one();
    }
}