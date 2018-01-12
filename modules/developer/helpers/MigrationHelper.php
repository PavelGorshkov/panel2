<?php
namespace app\modules\developer\helpers;

use Yii;

/**
 * Class MigrationHelper
 * @package app\modules\developer\helpers
 */
class MigrationHelper {

    /**
     * @param string $module
     * @return null|string
     */
    public static function pathForModule($module) {

        $migrationPath = Yii::getAlias("@app/modules/".$module."/install/migrations");

        return is_dir($migrationPath)?$migrationPath:null;
    }
}