<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 20.12.2017
 * Time: 14:01
 */

namespace app\modules\developer\models;


interface GenerateFileModuleInterface {

    public function generate();

    public function setModule($module);

    public function getSuccessMessage();
}