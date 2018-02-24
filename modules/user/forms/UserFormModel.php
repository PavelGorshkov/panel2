<?php

namespace app\modules\user\forms;

use app\modules\core\interfaces\SaveModelInterface;
use app\modules\user\helpers\EmailConfirmStatusHelper;
use app\modules\user\helpers\ModuleTrait;
use app\modules\user\helpers\UserAccessLevelHelper;
use app\modules\user\helpers\UserStatusHelper;
use app\modules\user\models\ManagerUser;
use app\modules\user\models\User;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\HtmlPurifier;

/**
 * Class UserFormModel
 * @package app\modules\user\forms
 */
class UserFormModel extends Model implements SaveModelInterface
{
    use ModuleTrait;

    public $id;

    public $username;

    public $email;

    public $email_confirm = EmailConfirmStatusHelper::EMAIL_CONFIRM_NO;

    public $full_name;

    public $access_level = UserAccessLevelHelper::LEVEL_USER;

    public $status;

    public $about;

    public $phone;

    protected $old_attributes;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'username',
                    'email',
                    'email_confirm',
                    'full_name',
                    'access_level',
                    'status',
                    'about',
                ],
                'required'
            ],
            [
                [
                    'username',
                    'email',
                    'full_name',
                    'about',
                    'phone'
                ], 'trim',],
            [
                ['username', 'email', 'full_name', 'about', 'phone'], 'filter',
                'filter' => function ($html) {
                    return HtmlPurifier::process(
                        $html,
                        ['Attr.AllowedFrameTargets' => ['_blank'],]
                    );
                }
            ],
            [
                'username',
                'unique',
                'targetClass' => User::class,
                'message' => 'Имя пользователя уже занято',
                'filter' => function (Query $query) {

                    if ($this->id !== null) {

                        $query->andWhere('id != :id', [':id' => (int)$this->id]);
                    }

                    return $query;
                }
            ],
            [
                'username',
                'match',
                'pattern' => '/^[A-Za-z0-9_-]{2,50}$/',
                'message' => 'Неверный формат поля "{attribute}" допустимы только буквы и цифры, от 2 до 20 символов'
            ],
            ['email', 'email'],
            ['status', 'in', 'range' => array_keys(UserStatusHelper::getList())],

            ['email_confirm', 'in', 'range' => array_keys(EmailConfirmStatusHelper::getList())],
            ['access_level', 'in', 'range' => array_keys(User::getAccessLevelList())],

            [
                'access_level',
                'isUpdatedAdmin',
                'params' => [
                    'message' => 'Нельзя отредактировать данные администратора',
                    'defaultValue' => UserAccessLevelHelper::LEVEL_ADMIN
                ]
            ],
            [
                'status',
                'isUpdatedAdmin',
                'params' => [
                    'message' => 'Нельзя отредактировать данные администратора',
                    'defaultValue' => UserStatusHelper::STATUS_ACTIVE
                ],
            ],

            ['phone', 'match', 'pattern' => $this->module->phonePattern, 'message' => 'Некорректный формат поля {attribute}',],

            ['id', 'safe'],
        ];
    }


    /**
     * @param string $attribute
     * @param array $params
     *
     * @return bool
     */
    public function isUpdatedAdmin($attribute, $params)
    {

        if ($this->id === null) return true;

        $user = User::findByPk($this->id);

        $message = isset($params['message'])
            ? $params['message']
            : 'Ошибка валидации данных администратора!';

        if ($user->isAdmin() && User::findCountAdmin() < 2) {

            if ($this->$attribute != $params['defaultValue']) {

                $this->addError($attribute, $message);
                return false;
            }
        }

        return true;
    }


    /**
     * @param Model|ManagerUser $model
     * @return bool
     */
    public function processingData(Model $model)
    {

        $model->setAttributes($this->getAttributes());

        return $model->save();
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'email' => 'Email',
            'email_confirm' => 'Подтверждение email',
            'hash' => 'Hash',
            'auth_key' => 'Auth Key',
            'user_ip' => 'IP пользователя',
            'status' => 'Статус',
            'status_change_at' => 'Время изменения статуса',
            'visited_at' => 'Последний визит',
            'registered_from' => 'Тип регистрации',
            'access_level' => 'Группа',
            'logged_in_from' => 'Logged In From',
            'logged_at' => 'Logged At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'full_name' => 'ФИО',
            'avatar' => 'Аватар',
            'about' => 'Должность, место работы',
            'phone' => 'Телефон',
        ];
    }
}