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
    public function scenarios() {

        return ArrayHelper::merge(parent::scenarios(),
            [
                self::SCENARIO_REGISTER =>[
                    'username', 'email', '!hash', 'full_name', 'about', 'phone'
                ]
            ]);
    }


    /**
     * @return array
     */
    public function rules() {

        return [
            [['username', 'email', 'hash', 'full_name'], 'required'],

            [['username'], 'string', 'max' => 25],
            [['username'], 'unique'],

            [['email'], 'unique'],

            [['hash'], 'string', 'max' => 60],

            [['about'], 'string'],

            [['full_name', 'avatar'], 'string', 'max' => 150],

            [['phone'], 'string', 'max' => 30],
        ];
    }
}