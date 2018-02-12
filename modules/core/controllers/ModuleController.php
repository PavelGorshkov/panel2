<?php
namespace app\modules\core\controllers;

use app\modules\core\auth\ModuleTask;
use app\modules\core\components\RedactorController;
use app\modules\core\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;

/**
 * Class ModuleController
 * @package app\modules\core\controllers
 * @property-read Module $module
 */
class ModuleController extends RedactorController{


    protected $actionMenu = [
        'index'=>'Установленные модули',
        'disabled'=>'Неустановленные модули',
        'flush'=>'Очистить кеш',
    ];


    /**
     * @inheritdoc
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {
        $this->setTitle('Модули');
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => ArrayHelper::merge(ModuleTask::createRulesController(),
                    [
                        [
                            'allow' => true,
                            'actions' => ['off'],
                            'roles' => [ModuleTask::OPERATION_DISABLED],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['on'],
                            'roles' => [ModuleTask::OPERATION_ENABLED],
                        ]
                    ]
                )
            ],
            'verbs' => [
                'class'=>VerbFilter::className(),
                'actions' => [
                    'on'=>['POST'],
                    'off'=>['POST'],
                ],
            ],
        ];
    }


    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex() {

        $modules = app()->moduleManager->getEnabledModules();

        if ($post = app()->request->post('Priority')) {

            $error = false;

            if (!is_array($post)) $error = true;
            else {

                foreach ($post as $module => $v) {

                    if (!isset($modules[$module])) {$error = true; break;}
                    elseif ((bool) $modules[$module]['is_system']) {

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

                if (empty($post)) $error = true;
            }

            printr($post, 1);

            if (!$error  ) {

                app()->session->setFlash('contactFormSubmitted');
            } else {

                app()->session->setFlash('contactFormSubmitted');
            }

            return $this->refresh();
        }

        return $this->render('enabled', ['modules'=>$modules]);
    }


    /**
     * @return string
     */
    public function actionDisabled() {

        $modules = app()->moduleManager->getDisabledModules();

        return $this->render('disabled', ['modules'=>$modules]);
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


    /**
     * @param $module
     * @return \yii\web\Response
     */
    public function actionOn($module) {

        $disabledModules = app()->moduleManager->getDisabledModules();

        if (isset($disabledModules[$module])) {

            if (app()->moduleManager->onModule($module)) {

                user()->setSuccessFlash('Модуль успешно подключен!');
                $this->module->allFlush();
            } else {

                user()->setErrorFlash('не удалось подключить модуль!');
                return $this->redirect('disabled');
            }
        }

        return $this->redirect('index');
    }
}