<?php

namespace app\modules\core\components\actions;

use app\modules\core\components\WebController;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\InvalidRouteException;

use yii\base\Module;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class ObserverAction
 * @package app\modules\core\components\actions
 */
class ObserverAction extends Action
{
    public $view = null;

    /**
     * @var WebController
     */
    public $controller;

    public $actions = [];

    public $smallTitle = '';

    protected $_controllers = [];


    /**
     * @throws ServerErrorHttpException
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!is_array($this->actions) && !count($this->actions)) {

            throw new ServerErrorHttpException('Введите действия в параметре "actions"');
        }

        foreach ($this->actions as $action => $temp) {

            $data = $this->getActionController($action);

            if (empty($data[0])) continue;

            $actionObj = $data[0]->createAction($data[1]);

            if (empty($actionObj)) continue;

            $this->_controllers[$action] = $data;
        }

        if ($this->view === null) {

            $this->view = '@app/modules/core/views/actions/observer';
        }
    }


    /**
     * @return string
     * @throws InvalidConfigException
     * @throws InvalidRouteException
     * @throws BadRequestHttpException
     */
    public function run()
    {
        $content = [];

        foreach ($this->_controllers as $action => $data) {

            /** @var WebController $controller */
            $controller = $data[0];
            /** @var string $actionId */
            $actionId = $data[1];

            $text = $controller->runAction($actionId);

            if (!empty($text)) {

                $text  =  $this->runAction($controller, $actionId);

                if ($text === null) continue;

                $content[$action] = [
                    'title' => $this->actions[$action],
                    'content' => $text,
                ];
            }
        }

        if ($this->smallTitle) $this->controller->setSmallTitle($this->smallTitle);

        return $this->controller->render($this->view, ['content' => $content]);
    }

    /**
     * @param string $action
     * @return array
     * @throws InvalidConfigException
     */
    protected function getActionController($action)
    {
        $pos = strpos($action, '/');

        $actionId = '';
        if ($pos === false) {

            $controller = $this->controller->module->createControllerByID($this->controller->id);
            $actionId = $action;

        } elseif ($pos > 0) {

            list($controller, $actionId) = $this->controller->module->createController($action);
        }

        if (empty($controller)) {

            return [null, $action];
        }

        /* @var WebController controller */
        $controller->layout = false;
        /** @noinspection PhpUndefinedMethodInspection */
        $controller->setSmallTitle($this->actions[$action]);

        return [$controller, $actionId];
    }


    /**
     * @param WebController $controller
     * @param string $actionId
     * @return null|string
     * @throws InvalidConfigException
     * @throws BadRequestHttpException
     */
    protected function runAction($controller, $actionId)
    {
        $action = $controller->createAction($actionId);

        if ($action === null) {

            return null;
        }

        $result = null;
        $modules = [];
        $runAction = true;

        /** @var Module $module */
        foreach ($controller->getModules() as $module) {

            if ($module->beforeAction($action)) {

                array_unshift($modules, $module);

            } else {
                $runAction = false;
                break;
            }
        }

        if ($runAction && $controller->beforeAction($action)) {
            // run the action
            $result = $action->runWithParams([]);

            $result = $controller->afterAction($action, $result);

            // call afterAction on modules
            foreach ($modules as $module) {
                /* @var $module Module */
                $result = $module->afterAction($action, $result);
            }
        }

        return $result;
    }
}