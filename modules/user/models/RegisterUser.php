<?php

namespace app\modules\user\models;

use yii\helpers\ArrayHelper;

/**
 * Class RegisterUser
 * @package app\modules\user\models
 */
class RegisterUser extends User
{
    const SCENARIO_REGISTER = 'register';

    /**
     * @return array
     */
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(),
            [
                self::SCENARIO_REGISTER => [
                    'username', 'email', '!hash',
                ]
            ]);
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'email', 'hash'], 'required'],

            [['username'], 'string', 'max' => 25],
            [['username'], 'unique'],

            [['email'], 'unique'],

            [['hash'], 'string', 'max' => 60],

            [['avatar'], 'string', 'max' => 150],
        ];
    }
}