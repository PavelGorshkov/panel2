<?php
namespace app\modules\user\forms;

use app\modules\user\helpers\ModuleTrait;
use yii\base\Model;
use yii\helpers\HtmlPurifier;

/**
 * Class ProfileRegistrationForm
 * @package app\modules\user\forms
 */
class ProfileRegistrationForm extends Model {

    use ModuleTrait;

    public $full_name;

    public $phone;

    public $about;

    public function rules() {

        return [
            [['full_name'], 'filter', 'filter' => 'trim',],
            [
                ['full_name'], 'filter',
                'filter' => function ($html) {
                    return HtmlPurifier::process(
                        $html,
                        ['Attr.AllowedFrameTargets' => ['_blank'],]
                    );
                }
            ],
            [['about', 'phone', 'full_name'], 'required'],
            ['about', 'string', 'max' => 300],
            ['phone','match', 'pattern' => $this->module->phonePattern, 'message' => 'Некорректный формат поля {attribute}'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'full_name' => 'ФИО',
            'about' => 'Должность, место работы',
            'phone' => 'Телефон',
        ];
    }

    public function formName() {

        return 'profile-registration-form';
    }
}