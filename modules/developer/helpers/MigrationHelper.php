<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 13.12.2017
 * Time: 13:25
 */

namespace app\modules\developer\helpers;

use Yii;

class MigrationHelper {

    public static function pathForModule($module) {

        $migrationPath = Yii::getAlias("@app/modules/".$module."/install/migrations");

        return is_dir($migrationPath)?$migrationPath:null;
    }
}