<?php
namespace app\modules\core\components\actions;

use yii\base\Action;

/**
 * Class WebAction
 * @package app\modules\core\components\actions
 *
 * @property \app\modules\core\components\WebController $controller
 */
class WebAction extends Action {

    public $view = null;

    public $layout = null;

    public $title = null;

    public $smallTitle = null;

    public function init() {

        if ($this->layout !== null) $this->controller->layout = $this->layout;

        if ($this->view === null) $this->view = $this->id;

        if ($this->title !== null) $this->setTitle($this->title);

        if ($this->smallTitle !== null) $this->setSmallTitle($this->smallTitle);

    }

    public function setTitle($title) {

        if (method_exists($this->controller, 'setTitle')) {

            $this->controller->setTitle($title);
        } else {

            $this->controller->view->title = $title;
        }
    }


    public function setSmallTitle($small_title) {

        if (method_exists($this->controller, 'setSmallTitle')) {

            $this->controller->setSmallTitle($small_title);
        }
    }

    public function render($params) {

        return $this->controller->render($this->view, $params);
    }


}