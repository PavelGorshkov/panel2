<?php
namespace app\modules\core\controllers;

use app\modules\core\auth\ModuleTask;
use app\modules\core\components\RedactorController;
use app\modules\core\helpers\ModulePriority;
use yii\filters\AccessControl;
use yii\helpers\HtmlPurifier;

/**
 * Class ModuleController
 * @package app\modules\core\controllers
 */
class ModuleController extends RedactorController{


    protected $actionMenu = [
        'index'=>'Установленные модули',
        'disabled'=>'Неустановленные модули',
        'flush'=>'Очистить кеш',
    ];

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => ModuleTask::createRulesController(),
            ],
        ];
    }


    /**
     * @return string|\yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function actionIndex() {

        $modules = app()->moduleManager->getEnabledModules();

        if ($post = app()->request->post('States')) {

            $error = false;

            if (!is_array($post)) $error = true;
            else {

                foreach ($post as $module => $v) {

                    if (!isset($modules[$module])) {$error = true; break;}
                    elseif ($modules[$modules]['is_system']) {

                        unset($post[$module]);
                        continue;
                    }
                    else {

                        $v = trim(HtmlPurifier::process($v, [
                            'HTML.SafeObject'=>true,
                             'Output.FlashCompat'=>true,
                        ]));

                        if ($v == $modules[$module]['priority'])  unset($post[$module]);
                    }
                }
            }

            if (!$error && ModulePriority::model()->setData($post)) {

                app()->session->setFlash('contactFormSubmitted');
            } else {

                app()->session->setFlash('contactFormSubmitted');
            }

            return $this->refresh();
        }

        return $this->render('enabled', ['modules'=>$modules]);
    }


    public function actionDisabled() {

        
    }


    /**
     * Очистка кеша
     */
    public function actionFlush() {

        /** @var $module \app\modules\core\Module  */
        $module = app()->getModule('core');

        if ($module->allFlush()) {

            user()->setSuccessFlash('Кеш успешно очищен');
        } else {

            user()->setErrorFlash('Кеш не удалось очистить');
        }

        $this->redirect(app()->request->referrer);
        app()->end();
    }
}