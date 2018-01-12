<?php
namespace app\modules\user\forms;

use app\modules\core\components\FormModel;
use app\modules\user\helpers\ModuleTrait;
use yii\web\UploadedFile;

/**
 * Class ProfileForm
 * @package app\modules\user\forms
 */
class ProfileForm extends FormModel {

    use ModuleTrait;

    public $full_name;

    public $about;

    public $avatar_file;

    public $phone;

    public $email;


    /**
     * @return array
     */
    public function scenarios() {

        return [
            self::SCENARIO_DEFAULT => [
                'full_name',
                'about',
                'phone',
                'avatar_file',
                '!email'
            ],
        ];
    }


    /**
     * @return array
     */
    public function rules() {

        return [
            [['full_name'], 'required'],
            [['full_name'], 'string', 'max'=>150],

            [['about'], 'string'],

            [['phone'], 'string', 'max'=>30],

            [
                'avatar_file', 'image',
                'extensions'=>$this->module->avatarExtensions,
                'maxSize'=>$this->module->avatarMaxSize,
                'skipOnEmpty'=>true,
            ],

            [
                'phone',
                'match',
                'pattern'=>$this->module->phonePattern,
                'message'=>'Некорректный формат поля {attribute}',
            ],
        ];
    }


    /**
     * @return bool
     */
    public function upload() {

        if (($this->avatar_file = UploadedFile::getInstance($this, 'avatar_file')) !== null) {

            if ($this->validate('avatar_file')) {

                if (
                    $this->avatar_file
                        ->saveAs($this->module->avatarDirs.'/avatar_'.user()->info->id.'.'.$this->avatar_file->extension,1)
                ) {

                    $this->avatar_file = 'avatar_'.user()->info->id.'.'.$this->avatar_file->extension;
                    return true;
                }
            }

            return false;
        }

        return true;
    }


    /**
     * @return string
     */
    public function formName() {

        return 'profile-form';
    }


    /**
     * @return array
     */
    public function attributeLabels() {

        return [
            'full_name'=>'ФИО',
            'about'=>'Должность, место работы',
            'avatar_file'=>'Аватар',
            'phone'=>'Телефон',
        ];
    }
}