<?php
namespace app\modules\user\controllers;

use app\modules\core\components\RedactorController;
use yii\filters\AccessControl;


class ProfileController extends RedactorController
{
    protected $actionMenu = [
        'index'=>'Панель управления',
        'view'=>'Профиль',
        'password'=>'Редактирование профиля',
    ];

    public $layout = '@app/modules/user/views/layouts/profile';


    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                            'update',
                            'password',
                            'email',
                        ],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex() {

        return $this->render('index');
    }


    public function actionEmail()
    {
        return $this->render('email');
    }



    public function actionPassword()
    {
        return $this->render('password');
    }

    public function actionUpdate()
    {
        return $this->render('update');
    }

    public function actionView()
    {
        return $this->render('view');
    }

}
