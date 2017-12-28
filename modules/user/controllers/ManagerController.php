<?php
namespace app\modules\user\controllers;

use app\modules\core\components\WebController;
use app\modules\core\helpers\TranslitHelper;
use app\modules\user\auth\ManagerTask;
use app\modules\user\components\Roles;
use app\modules\user\helpers\Password;
use app\modules\user\helpers\UserAccessLevelHelper;
use app\modules\user\helpers\UserStatusHelper;
use app\modules\user\models\SearchUser;
use app\modules\user\models\User;
use app\modules\user\models\UserProfile;
use yii\db\Migration;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class ManagerController extends WebController {

    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => ArrayHelper::merge(
                            ManagerTask::createRulesController(),
                            [
                                [
                                    'allow' => true,
                                    'actions' => ['test'],
                                    'roles' => [Roles::ADMIN],
                                ],
                                [
                                    'allow' => true,
                                    'actions' => ['editable'],
                                    'roles' => [Roles::ADMIN],
                                ],
                            ])
            ],
        ];
    }


    public function actions() {

        return [
            'editable'=>[
                'class' => EditableColumnAction::className(),
                'modelClass' => '',
            ]
        ];
    }


    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
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


    /**
     * @throws \yii\base\Exception
     */
    public function actionTest() {

        $base = [
            10=>'Савин Авдей Филатович',
            'Щукина Нина Филатовна',
            'Федотова Кира Данииловна',
            'Максимов Евгений Якунович',
            'Алексеев Станислав Антонович',
            'Киселёва Светлана Николаевна',
            'Антонов Тихон Демьянович',
            'Егоров Степан Богданович',
            'Родионов Тихон Тихонович',
            'Самсонова Анастасия Константиновна',
            'Рябов Глеб Павлович',
            'Гусев Анатолий Геласьевич',
            'Васильев Лаврентий Кимович',
            'Воронова Агата Валерьяновна',
            'Савельева Елизавета Аркадьевна',
            'Ермакова Милица Игоревна',
            'Дмитриев Владислав Дмитрьевич',
            'Крюков Роман Германнович',
            'Уварова Наталья Владимировна',
            'Дементьева Ирина Геннадьевна',
            'Маркова Элеонора Якуновна',
            'Александров Валерий Егорович',
            'Агафонов Геласий Валерьянович',
            'Рябова Валерия Васильевна',
            'Савельев Валентин Демьянович',
            'Моисеева Галина Николаевна',
            'Ковалёва Феврония Всеволодовна',
            'Ермакова Галина Борисовна',
            'Гущина Мария Егоровна',
            'Поляков Леонид Мэлорович',
            'Наумова Валерия Андреевна',
            'Попова Вероника Владиславовна',
            'Осипова Ольга Гордеевна',
            'Панфилов Мартын Фролович',
            'Тетерина Ульяна Александровна',
            'Кулагин Богдан Евсеевич',
            'Пестова Вера Лукьевна',
            'Баранова Алина Лукьяновна',
            'Ефремова Маргарита Гордеевна',
            'Веселов Никита Евсеевич',
            'Родионова София Филатовна',
            'Тарасова Фаина Авдеевна',
            'Колесников Демьян Глебович',
            'Фадеева Зоя Артёмовна',
            'Игнатова Глафира Куприяновна',
            'Симонов Максим Андреевич',
            'Фролов Куприян Валентинович',
            'Никонов Созон Викторович',
            'Савельев Борис Ильяович',
        ];

        $template = [
            'user'=> array(
                'id'=>10,
                'username'=> '',
                'email'=> '@marsu.ru',
                'email_confirm'=> 1,
                'status'=>UserStatusHelper::STATUS_ACTIVE,
                'hash'=> Password::hash('usertest'),
                'access_level'=> UserAccessLevelHelper::LEVEL_USER,
                'auth_key'=> app()->security->generateRandomKey(),
            ),
            'profile'=>[
                'user_id'=>10,
                'full_name'=>'Администратор',
            ]
        ];

        $list = [];

        foreach ($base as $id=>$full_name) {

            $template['user']['id'] = $id;
            $template['user']['username'] = TranslitHelper::translit($full_name);
            $template['user']['email'] = TranslitHelper::translit($this->transform_fullname($full_name)).'@marsu.ru';
            $template['profile']['user_id'] = $id;
            $template['profile']['full_name'] = $full_name;

            $list[] = $template;
        }

        $m = new Migration();
        foreach ($list as $v) {

            $m->insert(User::tableName(), $v['user']);
            $m->insert(UserProfile::tableName(), $v['profile']);
        }

        printr('yes');
    }


    protected function transform_fullname($string) {

        list($f, $i, $o) = explode(' ', $string);

        return $f.mb_substr($i, 0,1).mb_substr($o, 0,1);
    }
}