<?php
namespace app\modules\core\controllers;

use app\modules\core\components\WebController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ModuleController extends WebController {

    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    public function actionIndex() {

        app()->moduleManager->getAllModules();
        app()->moduleManager->getEnabledModules();
        app()->moduleManager->getDisabledModules();

        printr(app()->moduleManager, 1);


    }
}