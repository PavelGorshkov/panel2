<?php
namespace app\modules\developer\models;

use app\modules\core\components\Migrator;
use app\modules\core\helpers\File;
use Yii;

/**
 * Class MigrationConstructor
 * @package app\modules\developer\models
 * @property string className
 * @property string module
 */
class MigrationConstructor extends FileConstructor
{
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




}