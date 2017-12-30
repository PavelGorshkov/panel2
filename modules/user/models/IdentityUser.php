<?php
namespace app\modules\user\models;

use app\modules\user\helpers\ModuleTrait;
use app\modules\user\helpers\UserStatusHelper;
use yii\web\IdentityInterface;

/**
 * Class IdentityUser
 * @package app\modules\user\models
 */
class IdentityUser extends User implements IdentityInterface
{
    use ModuleTrait;

    public static function findIdentity($id) {

        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null) {


    }

    public function getId() {

        return $this->getAttribute('id');
    }

    public function getAuthKey() {

        return $this->getAttribute('auth_key');
    }

    public function validateAuthKey($authKey) {

        return $this->getAuthKey() === $authKey;
    }

    /**
     * Проверка заблокированности пользователя
     *
     * @return bool
     */
    public function getIsBlocked()
    {
        return (int) $this->status === UserStatusHelper::STATUS_BLOCK;
    }


    /**
     * @param int $size
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\web\HttpException
     */
    public function getAvatarSrc($size = 64) {

        $avatar = $this->avatar?$this->avatar:$this->module->defaultAvatar;

        return app()->thumbNailer->thumbnail($this->module->avatarDirs. $avatar,
            $this->module->avatarDirs,
            $size, $size
        );
    }
}