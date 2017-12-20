<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 20.12.2017
 * Time: 13:59
 */

namespace app\modules\developer\models;

use app\modules\core\components\FormModel;
use yii\helpers\Html;

class MigrationFormModel extends FormModel implements GenerateFileModuleInterface
{
    public $className;

    public $module;

    public function rules() {

        return [
            ['className', 'filter', 'filter'=>'trim'],
            [['className', 'module'], 'required'],
            [['className', 'module'], 'string', 'max'=>60],
            ['module', 'in', 'range'=>array_keys(app()->moduleManager->getListAllModules())],
        ];
    }

    public function attributeLabels() {

        return [
            'className'=>'Название класса',
            'module' => 'Модуль'
        ];
    }

    public function attributeDescriptions() {

        return [
            'className'=>'Название класса миграции должно содержать название модуля, таблицы
                    и краткое содержание действия, что хотите сделать...<br>
                    например:<br>
                    <span class="label label-default">'.Html::encode('<module>_<table>_<action>').'</span>
                ',
            'module'=>'Модуль должен быть определен в системе корректно.'
        ];
    }

    public function setModule($module) {

        $this->module = $module;
    }

    public function generate() {

        return (new MigrationConstructor($this->getAttributes()))->generate() ;
    }


    public function getSuccessMessage() {

        return 'Миграция успешно создана!';
    }

}