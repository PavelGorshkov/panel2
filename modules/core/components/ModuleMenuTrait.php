<?php
namespace app\modules\core\components;

/**
 * Class ModuleMenuTrait
 * @package app\modules\core\components
 *
 * @property string $id
 */
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


    public function getMenuUrl($url = Module::OBSERVER_URL) {

        return [
            '/'.$this->id.'/'.trim($url, '/')
        ];
    }
}