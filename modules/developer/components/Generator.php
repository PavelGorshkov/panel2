<?php
namespace app\modules\developer\components;

use yii\gii\Generator as YiiGenerator;
use yii\helpers\StringHelper;

/**
 * Class Generator
 * @package app\modules\developer\components
 */
abstract class Generator extends YiiGenerator
{
    /**
     * @param string $class
     * @return string
     */
    public function getClassNamespace($class)
    {
        $name = StringHelper::basename($class);
        return ltrim(substr($class, 0, - (strlen($name) + 1)), '\\');
    }
}