<?php
namespace app\modules\developer\models;

use app\modules\core\helpers\File;
use app\modules\user\components\BuildAuthManager;


/**
 * Class AuthTaskConstructor
 * @package app\modules\developer\models
 * @property string className
 * @property string module
 * @property string title
 * @property string url
 */
class AuthTaskConstructor extends FileConstructor
{
    /**
     * @return bool
     */
    public function generate()
    {
        $this->className = ucfirst($this->className) . 'Task';
        $this->url = mb_strtolower($this->url);

        return $this->createFileAuthTask();
    }


    /**
     * @return bool
     */
    protected function createFileAuthTask()
    {
        $path = BuildAuthManager::getPathAuthTask($this->module);

        File::checkPath($path, self::ACCESS_FOLDER);

        $buffer = $this->getFileContent();

        if (!$buffer) return false;

        $buffer = str_replace('{ClassName}', $this->className, $buffer);
        $buffer = str_replace('{module}', $this->module, $buffer);
        $buffer = str_replace('{url}', $this->url, $buffer);
        $buffer = str_replace('{title}', $this->title, $buffer);

        return $this->createFile($path . DIRECTORY_SEPARATOR . $this->className . '.php', $buffer);
    }


    /**
     * @return string
     */
    protected function getFileContent()
    {
        ob_start();
        /** @noinspection PhpIncludeInspection */
        include \Yii::getAlias('@app/modules/developer/templates/auth/template.php');
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }
}