<?php
namespace app\modules\user\controllers;

use app\modules\core\components\WebController;
use app\modules\user\auth\ManagerTask;
use app\modules\user\models\SearchUser;
use yii\filters\AccessControl;

class ManagerController extends WebController {

    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => ManagerTask::createRulesController()
            ],
        ];
    }



    public function beforeAction($action)
    {
        $this->setTitle('Управление пользователями');

        return parent::beforeAction($action);
    }


    public function actionIndex() {

        $searchModel = new SearchUser();

        $dataProvider = $searchModel->search(app()->request->get());

        $this->setSmallTitle('Список');

        return $this->render($this->action->id, [
            'dataProvider'=> $dataProvider,
            'searchModel'=>$searchModel,
        ]);
    }
}