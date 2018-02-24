<?php
namespace app\modules\core\helpers;

use app\modules\core\components\Module;

/**
 * Class ModuleMenuTrait
 * @package app\modules\core\components
 *
 * @property string $id
 */
trait ModuleMenuTrait
{
    /**
     * @return array
     */
    public function getMenuAdmin()
    {
        return [];
    }


    /**
     * @return array
     */
    public function getMenuMain()
    {
        return [];
    }


    /**
     * @return array
     */
    public function getMenuRedactor()
    {
        return [];
    }


    /**
     * @param string $url
     * @return array
     */
    public function getMenuUrl($url = Module::OBSERVER_URL)
    {

        return [
            '/' . $this->id . '/' . trim($url, '/')
        ];
    }
}