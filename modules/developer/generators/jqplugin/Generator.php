<?php

namespace app\modules\developer\generators\jqplugin;

use yii\gii\CodeFile;

/**
 * Class Generator
 * @package app\modules\developer\generators\jqplugin
 */
class Generator extends \yii\gii\Generator
{
    public $pluginName;

    public $pluginPath;

    public $pluginFileName;


    /**
     * @return string name of the code generator
     */
    public function getName()
    {
        return 'JQuery plugin generator';
    }


    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator helps you to generate the skeleton code needed by a JQuery plugin.';
    }


    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['pluginName', 'pluginPath', 'pluginFileName'], 'filter', 'filter' => 'trim'],
            [['pluginName', 'pluginPath',], 'required'],
            [['pluginName'], 'match', 'pattern' => '/^[\w]+$/', 'message' => 'Only word characters and dashes are allowed.'],
            [['pluginFileName'], 'match', 'pattern' => '/^[\w\.]+$/', 'message' => 'Only word characters and dashes are allowed.'],
            [['pluginPath'], 'match', 'pattern' => '/^@app\/[\w\/]*$/', 'message' => 'Only word characters and slashes are allowed.'],
        ]);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pluginName' => 'Plugin name',
            'pluginPath' => 'Plugin path',
            'pluginFileName' => 'Plugin file name',
        ];
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return [
            'pluginName' => 'This refers to the name of the plugin, e.g., <code>plugin</code>.',
            'pluginPath' => '<code>@app/modules/developer/assets</code>.',
            'pluginFileName' => '<code>jquery.plugin</code>.',

        ];
    }


    /**
     * Generates the code based on the current user input and the specified code template files.
     * This is the main method that child classes should implement.
     * Please refer to [[\yii\gii\generators\controller\Generator::generate()]] as an example
     * on how to implement this method.
     * @return CodeFile[] a list of code files to be created.
     */
    public function generate()
    {
        $files = [];


        $files[] = new CodeFile(
            \Yii::getAlias($this->pluginPath).'/'.$this->pluginFileName.'.js',
            $this->render("plugin.php")
        );


        return $files;
    }
}