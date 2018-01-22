<?php

namespace app\modules\developer\controllers\actions;

use app\modules\core\components\actions\GridViewAction;
use yii\web\ServerErrorHttpException;

/**
 * Class viewItemsModuleAction
 * @package app\modules\developer\controllers\actions
 */
class viewItemsModuleAction extends GridViewAction
{
    public $layout = '@app/modules/developer/views/layouts/modules_menu';


    /**
     * @throws \yii\base\InvalidConfigException
     * @throws ServerErrorHttpException
     */
    public function init()
    {
        parent::init();
    }


    /**
     * @param string $module
     * @return string
     */
    public function run($module = '')
    {
        if ($module !== '') {

            $this->model->setAttributes([
                'module'=>$module
            ]);
        }

        return parent::run();
    }
}