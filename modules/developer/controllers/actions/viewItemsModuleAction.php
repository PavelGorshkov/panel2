<?php

namespace app\modules\developer\controllers\actions;

use app\modules\core\components\actions\WebAction;
use yii\web\ServerErrorHttpException;

/**
 * Class viewItemsModuleAction
 * @package app\modules\developer\controllers\actions
 */
class viewItemsModuleAction extends WebAction
{
    public $model;

    public $layout = '@app/modules/developer/views/layouts/modules_menu';

    /**
     * @throws ServerErrorHttpException
     */
    public function init()
    {

        if ($this->model === null) {

            throw new ServerErrorHttpException('В action "' . $this->id . '" контроллера "' . $this->controller->id . '" не привязана модель "model"');
        }

        parent::init();
    }


    /**
     * @param string $module
     * @return string
     */
    public function run($module = '')
    {
        return $this->render(['model' => new $this->model($module)]);
    }
}