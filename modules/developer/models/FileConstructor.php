<?php
namespace app\modules\developer\models;


use yii\base\BaseObject;

/**
 * Class FileConstructor
 * @package app\modules\developer\models
 */
abstract class FileConstructor extends BaseObject
{
    const ACCESS_FOLDER = 0777;

    const ACCESS_FILE = 0776;

    /**
     *
     * @var array
     */
    protected $_attributes;


    /**
     * MigrationConstructor constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {

        $this->_attributes = $attributes;

        parent::__construct();
    }


    /**
     * @param string $name
     * @return mixed
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {

        return isset($this->_attributes[$name])
            ? $this->_attributes[$name]
            : parent::__get($name);

    }


    /**
     * @param string $name
     * @param mixed $value
     * @throws \yii\base\UnknownPropertyException
     */
    public function __set($name, $value)
    {

        if (isset($this->_attributes[$name])) $this->_attributes[$name] = $value;

        else parent::__set($name, $value);
    }


    /**
     * @return bool
     */
    abstract public function generate();


    /**
     * @param $file
     * @param $content
     * @return bool
     */
    protected function createFile($file, $content)
    {
        if ($isCreate = file_put_contents($file, $content)) chmod($file, self::ACCESS_FILE);

        return (bool) $isCreate;
    }
}