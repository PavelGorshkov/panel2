<?php

namespace app\modules\user\models;

use app\modules\user\helpers\ModuleTrait;
use app\modules\user\models\query\ProfileQuery;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "{{%user_profile}}".
 *
 * @property integer $user_id
 * @property string $full_name
 * @property string $avatar
 * @property string $about
 * @property string $post
 * @property string $phone
 *
 * @property User $user
 */
class Profile extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'full_name'], 'required'],
            [['user_id'], 'integer'],
            [['about'], 'string'],
            [['full_name', 'avatar'], 'string', 'max' => 150],
            [['post'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 30],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'Id пользователя',
            'full_name' => 'ФИО',
            'avatar' => 'Аватар',
            'email' => 'Email',
            'about' => 'Информация',
            'post' => 'Должность',
            'phone' => 'Телефон',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return ProfileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProfileQuery(get_called_class());
    }


    /**
     * @param int $size
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\web\HttpException
     */
    public function getAvatarSrc($size = 64) {

        $avatar = $this->avatar?$this->avatar:$this->module->defaultAvatar;

        return app()->thumbNailer->thumbnail($this->module->avatarDirs. $avatar,
            $this->module->avatarDirs,
            $size, $size
        );
    }
}
