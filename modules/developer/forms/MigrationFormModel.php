<?php

namespace app\modules\developer\forms;

use app\modules\core\components\FormModel;
use app\modules\developer\interfaces\GenerateFileModuleInterface;
use app\modules\developer\models\MigrationConstructor;
use yii\helpers\Html;


/**
 * Class MigrationFormModel
 * @package app\modules\developer\forms
 */
class MigrationFormModel extends FormModel implements GenerateFileModuleInterface
{
    public $className;

    public $module;

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {

        return [
            ['className', 'filter', 'filter' => 'trim'],
            [['className', 'module'], 'required'],
            [['className', 'module'], 'string', 'max' => 60],
            ['module', 'in', 'range' => array_keys(app()->moduleManager->getListAllModules())],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {

        return [
            'className' => 'Название класса',
            'module' => 'Модуль'
        ];
    }

    /**
     * @return array
     */
    public function attributeDescriptions()
    {

        return [
            'className' => 'Название класса миграции должно содержать название модуля, таблицы
                    и краткое содержание действия, что хотите сделать...<br>
                    например:<br>
                    <span class="label label-default">' . Html::encode('<module>_<table>_<action>') . '</span>
                ',
            'module' => 'Модуль должен быть определен в системе корректно.'
        ];
    }

    /**
     * @param string $module
     */
    public function setModule($module)
    {

        $this->module = $module;
    }


    /**
     * @return bool|mixed
     */
    public function generate()
    {

        return (new MigrationConstructor($this->getAttributes()))->generate();
    }


    /**
     * @return string
     */
    public function getSuccessMessage()
    {

        return 'Миграция успешно создана!';
    }

}