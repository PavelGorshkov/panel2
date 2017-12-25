<?php
namespace app\modules\user\controllers;

use app\modules\core\components\RedactorController;
use app\modules\user\forms\EmailProfileForm;
use app\modules\user\forms\ProfileForm;
use yii\debug\models\search\Profile;
use yii\filters\AccessControl;
use yii\helpers\Url;


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
                            'change-password',
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
        $this->layout = '@app/modules/user/views/layouts/profile_box';

        $this->setSmallTitle('Изменение E-mail');

        $model = new EmailProfileForm();

        $this->performAjaxValidation($model);

        if ($model->load(app()->request->post()) && $model->validate()) {

            if (app()->userManager->changeEmail(user()->info, $model->email)) {


            }
        }

        return $this->render('email');
    }



    public function actionChangePassword()
    {
        return $this->render('password');
    }


    public function actionUpdate()
    {
        $this->layout = '@app/modules/user/views/layouts/profile_box';

        $this->setSmallTitle('Изменение профиля');

        $model = new ProfileForm();
        $model->setAttributes(user()->profile->getAttributes());
        $model->email = user()->info->email;

        $this->performAjaxValidation($model);

        if (
             $model->load(app()->request->post())
          && $model->validate()
          && $model->upload()
        ) {

            if (app()->userManager->saveProfile($model)) {

                user()->setSuccessFlash('Ваши данные обновлены');
            }

            $this->redirect(Url::to(['view']));
            app()->end();
        }

        return $this->render($this->action->id, ['model'=>$model, 'module'=>$this->module]);
    }



}
