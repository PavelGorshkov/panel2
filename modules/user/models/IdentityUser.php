<?php
namespace app\modules\user\models;

use yii\web\IdentityInterface;

class IdentityUser extends User implements IdentityInterface
{
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
}