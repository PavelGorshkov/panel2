<?php
namespace app\modules\developer\helpers;

use Yii;

class MigrationHelper {

    public static function pathForModule($module) {

        $migrationPath = Yii::getAlias("@app/modules/".$module."/install/migrations");

        return is_dir($migrationPath)?$migrationPath:null;
    }
}