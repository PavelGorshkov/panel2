<?php
namespace app\modules\core\controllers;

use app\modules\core\auth\ModuleTask;
use app\modules\core\components\RedactorController;
use app\modules\core\helpers\ModulePriority;
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
class ModuleController extends RedactorController
{

    protected $actionMenu = [
        'index' => 'Установленные модули',
        'disabled' => 'Неустановленные модули',
        'flush' => 'Очистить кеш',
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

        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
                'actions' => [
                    'on' => ['POST'],
                    'off' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $modules = app()->moduleManager->getEnabledModules();

        if ($post = app()->request->post('Priority')) {

            $error = false;

            if (!is_array($post)) $error = true;
            else {

                foreach ($post as $module => $v) {

                    if (!isset($modules[$module])) {
                        $error = true;
                        break;
                    } elseif ((bool)$modules[$module]['is_system']) {

                        unset($post[$module]);
                        continue;
                    } else {

                        $post[$module] = trim(HtmlPurifier::process($v, [
                            'HTML.SafeObject' => true,
                            'Output.FlashCompat' => true,
                        ]));
                    }
                }

                if (empty($post)) $error = true;
            }

            if (!$error && ModulePriority::model()->setData($post, true)) {

                user()->setSuccessFlash('Данные сохранены');
            } else {

                user()->setWarningFlash('Данные не обновлены');
            }

            return $this->refresh();
        }

        return $this->render('enabled', ['modules' => $modules]);
    }


    /**
     * @param $module
     * @return \yii\web\Response|string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSettings($module)
    {
        $this->layout = 'admin';

        $modules = app()->moduleManager->getEnabledModules();

        if (!(isset($modules[$module]) && $modules[$module]['paramsCounter'])) {

            return $this->goBack();
        }

        $data = app()->moduleManager->getSettings($module);

        if ($post = app()->request->post('Settings')) {

            foreach ($post as $key => $value) {

                $value = trim(HtmlPurifier::process($value, [
                    'HTML.SafeObject' => true,
                    'Output.FlashCompat' => true,
                ]));

                if ($value == $data[$key]) unset($post[$key]);
                else $post[$key] = $value;

            }

            if (app()->moduleManager->saveSettings($module, $post)) {

                user()->setSuccessFlash('Настройки обновлены!');
            } else {

                user()->setWarningFlash('Нет данных для обновления! Настройки не обновлены.');
            }

            return $this->refresh();
        };

        return $this->render('settings', [
            'module' => app()->getModule($module),
            'slug' => $module,
            'data' => $data,
        ]);
    }


    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDisabled()
    {

        $modules = app()->moduleManager->getDisabledModules();

        return $this->render('disabled', ['modules' => $modules]);
    }


    /**
     * Очистка кеша
     *
     * @return \yii\web\Response
     */
    public function actionFlush()
    {
        /** @var $module \app\modules\core\Module */
        $module = app()->getModule('core');

        if ($module->allFlush()) {

            user()->setSuccessFlash('Кеш успешно очищен');
        } else {

            user()->setErrorFlash('Кеш не удалось очистить');
        }

        return $this->redirect(app()->request->referrer);
    }


    /**
     * @param $module
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionOn($module)
    {

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


    /**
     * @param string $module
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionOff($module)
    {

        $enabledModules = app()->moduleManager->getEnabledModules();

        if (isset($enabledModules[$module])) {

            if (app()->moduleManager->offModule($module)) {

                user()->setSuccessFlash('Модуль успешно отключен!');
                $this->module->allFlush();

            } else {

                user()->setErrorFlash('не удалось отключить модуль!');
            }
        }

        return $this->redirect('index');
    }
}