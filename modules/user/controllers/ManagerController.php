<?php
namespace app\modules\user\controllers;

use app\modules\core\components\WebController;
use app\modules\user\auth\ManagerTask;
use yii\filters\AccessControl;

class ManagerController extends WebController {

    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => self::createRulesFromTask(ManagerTask::className())
            ],
        ];
    }


    public function actionIndex() {


    }
}