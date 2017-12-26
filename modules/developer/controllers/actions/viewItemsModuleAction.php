<?php
namespace app\modules\developer\controllers\actions;

use app\modules\core\components\actions\WebAction;
use yii\web\ServerErrorHttpException;

class viewItemsModuleAction extends WebAction{

    public $model = null;

    public $layout = '@app/modules/developer/views/layouts/modules_menu';

    /**
     * @throws ServerErrorHttpException
     */
    public function init() {

        if ($this->model === null) {

            throw new ServerErrorHttpException('В action "'.$this->id.'" контроллера "'.$this->controller->id.'" не привязана модель "model"');
        }

        parent::init();
    }

    public function run($module = '') {

        return $this->render(['model'=>new $this->model($module)]);
    }
}