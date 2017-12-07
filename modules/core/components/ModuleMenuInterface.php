<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 07.12.2017
 * Time: 9:14
 */

namespace app\modules\core\components;


interface ModuleMenuInterface {

    public function getMenuAdmin();


    public function getMenuMain();


    public function getMenuRedactor();


}