<?php
namespace app\modules\user\forms;

use app\modules\core\interfaces\SaveModelInterface;
use app\modules\user\models\User;
use yii\base\Model;
use yii\helpers\HtmlPurifier;

/**
 * Class UserFormModel
 * @package app\modules\user\forms
 */
class UserFormModel extends Model implements SaveModelInterface
{
    public $id;

    public $username;

    public $email;

    public $email_confirm;

    public $full_name;

    public $access_level;

    public $status;

    public $about;

    public $phone;


    /**
     * @return array
     */
    public function rules() {

        return [
            [
                ['username', 'email', 'email_confirm', 'full_name', 'access_level', 'status', 'about', 'phone'],
                'required'
            ],
            [['username', 'email', 'full_name', 'about', 'phone'], 'filter', 'filter' => 'trim',],
            [
                ['username', 'email', 'full_name', 'about', 'phone' ], 'filter',
                'filter' => function ($html) {
                    return HtmlPurifier::process(
                        $html,
                        ['Attr.AllowedFrameTargets' => ['_blank'],]
                    );
                }
            ],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => 'Имя пользователя уже занято', 'skipOnEmpty'=>$this->id === null],
            [
                'username',
                'match',
                'pattern' => '/^[A-Za-z0-9_-]{2,50}$/',
                'message' => 'Неверный формат поля "{attribute}" допустимы только буквы и цифры, от 2 до 20 символов'
            ],
            ['email', 'email'],
            ['id', 'safe'],
        ];
    }


    /**
     * @param Model $model
     * @return bool|void
     */
    public function processingData(Model $model) {


    }
}