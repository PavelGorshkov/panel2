<?php
namespace app\modules\user\controllers;

use app\modules\core\components\RedactorController;
use yii\filters\AccessControl;


class ProfileController extends RedactorController
{
    protected $actionMenu = [
        '@index'=>'Панель управления',
        '@view'=>'Профиль',
    ];

    public $layout = '@app/modules/user/views/layouts/profile';

    protected $title = 'Профиль';


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

        return $this->render('index', [
            'admin'=>app()->menuManager->admin,
            'main'=>app()->menuManager->main,
            'redactor'=>app()->menuManager->redactor,
            'dictionary'=>app()->moduleManager->isInstallModule('dictionary') ? app()->dictionary->getMenu():[],
        ]);
    }


    public function actionView()
    {
        return $this->render('view', [
            'info'=>user()->info,
            'profile'=>user()->profile,
            'module'=>$this->module,
        ]);
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



}
