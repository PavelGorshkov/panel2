<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 07.12.2017
 * Time: 9:15
 */

namespace app\modules\core\components;


trait ModuleMenuTrait {

    public function getMenuAdmin()
    {
        return [];
    }


    public function getMenuMain()
    {
        return [];
    }


    public function getMenuRedactor()
    {
        return [];
    }


    public function getMenuUrl($url = self::OBSERVER_URL) {

        return [
            '/'.$this->id.'/'.trim($url, '/')
        ];
    }
}