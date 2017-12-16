<?php

namespace app\modules\core\components;


use app\modules\core\helpers\RouterUrlHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\HttpException;

class RedactorController extends WebController {

    protected $actionMenu = null;

    public $layout = '@app/modules/core/views/layouts/redactor_menu';

    public function beforeAction($action) {

        parent::beforeAction($action);

        if (!count($this->actionMenu)) {

            throw new HttpException('500', 'In class '.__CLASS__.' not found property actionMenu');
        }

        $this->view->params['actionMenu'] = $this->getActionsMenu();

        return true;
    }


    public function getActionsMenu() {


        foreach ($this->actionMenu as $url => $label) {

            $menu[] = [
                'label'=>$label,
                'url'=>[$url],
                'active'=>RouterUrlHelper::isActiveRoute($url),
                'visible'=>app()->moduleManager->can($this->module->id, RouterUrlHelper::to($url)),
            ];
        }

        return $menu;
    }


    protected function accessRuleList($controller = null) {

        $access = [];
        $rules = [];

        if ($controller === null) {

            $firstString = '';

            $rules = $this->getRulesController($this);

        } else {

            $firstString = $controller.'/';

            $class = $this->module->controllerNamespace . ucfirst($controller).'Controller';

            if (class_exists($class)) {

                $obj = new $class($controller, $this->module, []);

                $rules = $this->getRulesController($obj);

                unset($obj);
            }
        }

        if (count($rules)) {
            foreach ($rules as $rule) {

                if (isset($rule['allow']) && $rule['allow'] && isset($rule['roles'])) {

                    foreach ($rule['actions'] as $action) {

                        if (isset($this->actionMenu[$firstString.$action])) {

                            if (!isset($access[$firstString.$action]))  $access[$firstString.$action] = [];

                            $access[$firstString.$action] = ArrayHelper::merge($access[$firstString.$action],$rule['roles']);
                        }
                    }
                }
            }

        }

        return $access;
    }


    protected function getRulesController(RedactorController $controller) {

        if (!method_exists($controller, 'behaviors')) return [];

        $behaviors = $controller->behaviors();

        if (!isset($behaviors['access']['rules'])) return [];

        return $behaviors['access']['rules'];
    }





}