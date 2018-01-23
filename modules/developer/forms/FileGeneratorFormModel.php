<?php
namespace app\modules\developer\forms;

use app\modules\core\components\FormModel;
use app\modules\developer\interfaces\GenerateFileModuleInterface;

/**
 * Class FileGeneratorFormModel
 * @package app\modules\developer\forms
 * @property string className
 * @property string module
 */
abstract class FileGeneratorFormModel extends FormModel implements GenerateFileModuleInterface
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
            ['module', 'in', 'range' => array_keys(app()->moduleManager->getListAllModules()), 'skipOnEmpty'=>false],
        ];
    }


    /**
     * Генерация php afqkf
     * @return mixed
     */
    abstract public function generate();


    /**
     * @return string
     */
    abstract public function getSuccessMessage();

    /**
     * @param string $module
     * @return void
     */
    public function setModule($module)
    {
        $this->module = $module;
    }


}