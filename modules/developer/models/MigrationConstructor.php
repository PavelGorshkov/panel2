<?php
namespace app\modules\developer\models;

use app\modules\core\components\Migrator;
use app\modules\core\helpers\File;
use Yii;
use yii\base\BaseObject;

/**
 * Class MigrationConstructor
 * @package app\modules\developer\models
 * @property string className
 * @property string module
 */
class MigrationConstructor extends BaseObject
{

    const ACCESS_FOLDER = 0777;

    const ACCESS_FILE = 0776;

    /**
     *
     * @var array
     */
    private $_attributes;

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
    public function generate()
    {

        $this->className = 'm' . date('ymd_His') . '_' . $this->className;

        return $this->createFileMigration();
    }


    /**
     * @return string
     */
    protected function getFileContent()
    {
        ob_start();
        include Yii::getAlias('@app/modules/developer/templates/migration/template.php');
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }


    /**
     * @return bool
     */
    protected function createFileMigration()
    {
        $path = Migrator::getPathMigration($this->module);

        File::checkPath($path, self::ACCESS_FOLDER);

        $buffer = $this->getFileContent();

        if (!$buffer) return false;

        $buffer = str_replace('{ClassName}', $this->className, $buffer);
        $buffer = str_replace('{module}', $this->module, $buffer);

        return $this->createFile($path . DIRECTORY_SEPARATOR . $this->className . '.php', $buffer);
    }


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