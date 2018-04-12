<?php

namespace app\modules\finance;

use app\modules\core\components\Module as ParentModule;

/**
 * finance module definition class
 * Class Module
 * @package app\modules\finance
 */
class Module extends ParentModule
{
    /**
     * @return string
     */
    public static function Title()
    {
        return 'Финансовое обеспечение';
    }


    /**
     * @return array
     */
    public function getMenuMain()
    {
        return [
            [
                'icon' => 'fa fa-fw fa-money',
                'label' => 'Финансы',
                'url' => $this->getMenuUrl('finance/index'),
            ]
        ];
    }
}
