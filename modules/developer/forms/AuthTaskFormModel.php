<?php

namespace app\modules\developer\forms;

use app\modules\developer\models\AuthTaskConstructor;
use app\modules\user\components\BuildAuthManager;
use yii\helpers\Html;


/**
 * Class AuthTaskFormModel
 * @package app\modules\developer\forms
 */
class AuthTaskFormModel extends FileGeneratorFormModel
{
    public $url;

    public $title;

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {

        return [
            ['className', 'filter', 'filter' => 'trim'],
            ['url', 'filter', 'filter' => 'mb_strtolower'],
            ['className', 'filter', 'filter' => 'ucfirst'],
            [['className', 'module', 'title', 'url'], 'required'],
            [['className', 'module'], 'string', 'max' => 60],
            ['module', 'in', 'range' => array_keys(app()->moduleManager->getListAllModules()), 'skipOnEmpty' => false],
            [['title'], 'string', 'max' => 60],
            ['className', 'classExists'],
        ];
    }


    /**
     * @return bool
     */
    public function classExists()
    {
        if (!$this->hasErrors()) {

            if (file_exists(BuildAuthManager::getPathAuthTask($this->module).'/'.ucfirst($this->className).'Task.php')) {

                $this->addError('className', 'Данный класс уже существует в системе!');
                return false;
            }

            return true;
        }

        return false;
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'className' => 'Название класса',
            'url' => 'Controller URL',
            'module' => 'Модуль',
            'title' => 'Заголовок'
        ];
    }


    /**
     * @return array
     */
    public function attributeDescriptions()
    {
        return [
            'className' => 'Название класса недолжно содержать "Task" ...<br>
                    например:<br>
                    <span class="label label-default">' . Html::encode('test') . '</span>
                ',
            'module' => 'Модуль должен быть определен в системе корректно.',
            'url' => 'Должен быть указан в нижнем регистре',
            'title' => 'Название задачи ограничивается 60 символами'
        ];
    }


    /**
     * Генерация php afqkf
     * @return mixed
     */
    public function generate()
    {
        return (new AuthTaskConstructor($this->getAttributes()))->generate();
    }


    /**
     * @return string
     */
    public function getSuccessMessage()
    {
        return 'Задача успешно создана!';
    }
}