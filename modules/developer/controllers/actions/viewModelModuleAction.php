<?php
namespace app\modules\developer\controllers\actions;

use app\modules\core\components\actions\WebAction;
use Yii;
use yii\web\HttpException;

class viewModelModuleAction extends WebAction{

    public $model = null;

    public $layout = '@app/modules/developer/views/layouts/modules_menu';

    public function init() {

        if ($this->model === null) {

            throw new HttpException(500, 'В action "'.$this->id.'" контроллера "'.$this->controller->id.'" не привязана модель "model"');
        }

        parent::init();
    }

    public function run($module = '') {

        return $this->render(['model'=>new $this->model($module)]);
    }
}