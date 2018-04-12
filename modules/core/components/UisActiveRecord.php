<?php

namespace app\modules\core\components;

use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;

/**
 * необходим компонент dbUis со следующими настройками
 *
 * return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=host;dbname=prod_uis',
    'username' => login,
    'password' => password,
    'charset' => 'utf8',
    'tablePrefix'=>''
];
 *
 * Class UisActiveRecord
 * @package app\modules\core\components
 */
abstract class UisActiveRecord extends ActiveRecord
{
    /**
     * @return mixed|\yii\db\Connection
     * @throws ServerErrorHttpException
     */
    public static function getDb()
    {
        if (!app()->has('dbUis')) {

            throw new ServerErrorHttpException('Need component dbUis');
        }

        return app()->dbUis;
    }
}