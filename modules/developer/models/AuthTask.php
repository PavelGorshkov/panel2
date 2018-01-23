<?php
namespace app\modules\developer\models;

use yii\base\Model;

/**
 * Class AuthTask
 * @package app\modules\developer\models
 */
class AuthTask extends Model
{
    public $id;

    public $module;

    public $className;

    public $title;


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'module'=>'Модуль',
            'className'=>'Название класса',
            'title'=>'Заголовок',
        ];
    }
}