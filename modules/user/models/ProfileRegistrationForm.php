<?php
namespace app\modules\user\models;

use app\modules\user\helpers\ModuleTrait;
use yii\base\Model;

class ProfileRegistrationForm extends Model {

    use ModuleTrait;

    public $phone;

    public $about;

    public function rules() {

        return [
            [['about', 'phone'], 'required'],
            ['about', 'string', 'max' => 300],
            ['phone','match', 'pattern' => $this->module->phonePattern, 'message' => 'Некорректный формат поля {attribute}'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'about' => 'Должность, место работы',
            'phone' => 'Телефон',
        ];
    }

    public function formName() {

        return 'profile-registration-form';
    }
}