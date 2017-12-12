<?php

namespace app\modules\core\components;


use yii\helpers\ArrayHelper;
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

            $url_string = explode('/', $url);

            if (count($url_string) > 1) {

                $access = $this->accessRulesList($url_string[0]);
                $isActive = ($url_string[0] == $this->id) && ($url_string[1] == $this->action->id);
            } else {

                $access = $this->accessRuleList();
                $isActive = $url == $this->action->id;
            }


            if (isset($access[$url])) {

                $visible = app()->moduleManager->visibleItemMenu($this->module->id, $access[$url]);

            } else {

                $visible = true;
            }

            $menu[] = [
                'label'=>$label,
                'url'=>[$url],
                'active'=>$url == $isActive,
                'visible'=>$visible,
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