<?php

namespace app\modules\user\models;

use app\modules\user\helpers\ModuleTrait;
use app\modules\user\helpers\UserStatusHelper;
use yii\web\IdentityInterface;

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
     * Проверка заблокированности пользователя
     *
     * @return bool
     */
    public function getIsBlocked()
    {
        return (int)$this->status === UserStatusHelper::STATUS_BLOCK;
    }


    /**
     * @param int $size
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\web\HttpException
     */
    public function getAvatarSrc($size = 64)
    {
        $avatar = $this->avatar ? $this->avatar : $this->module->defaultAvatar;

        return app()->thumbNailer->thumbnail($this->module->avatarDirs . $avatar,
            $this->module->avatarDirs,
            $size, $size
        );
    }
}