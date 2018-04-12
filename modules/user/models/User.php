<?php

namespace app\modules\user\models;

use app\modules\user\helpers\AccessLevelTrait;
use app\modules\user\helpers\Password;
use app\modules\user\helpers\UserAccessLevelHelper;
use app\modules\user\helpers\UserStatusHelper;
use app\modules\user\interfaces\UserInterface;
use app\modules\user\models\query\UserQuery;
use app\modules\user\Module;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%user_user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property integer $email_confirm
 * @property string $hash
 * @property string $auth_key
 * @property integer $user_ip
 * @property integer $status
 * @property string $status_change_at
 * @property string $visited_at
 * @property integer $registered_from
 * @property integer $access_level
 * @property integer $logged_in_from
 * @property integer $logged_at
 * @property string $created_at
 * @property string $updated_at
 * @property string $full_name
 * @property string $avatar
 * @property string $about
 * @property string $phone
 *
 * @property-read Access $access
 * @property-read Token $token
 * @property-read Module $module
 * @property-read Profile $profile
 */
class User extends ActiveRecord implements UserInterface
{
    use AccessLevelTrait;

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        Access::deleteAll(['id' => $this->id, 'type' => Access::TYPE_USER]);

        Token::deleteAll(['user_id' => $this->id]);

        parent::afterDelete();
    }


    /**
     * @inheritdoc
     * @param boolean $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        app()->cache->flush();
    }


    /**
     * @inheritdoc
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @inheritdoc
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($insert) {

                $this->auth_key = app()->security->generateRandomString();
                $this->hash = Password::hash(Password::generate(8));
            }
            return true;
        }
        return false;
    }


    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }


    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }


    /**
     * @param string $username
     * @param string $password
     * @return User|null
     */
    public static function findApi($username, $password)
    {
        $model = self::find()
            ->andWhere([
                'or',
                ['username' => $username],
                ['email' => $username]
            ])->andWhere([
                'access_level' => UserAccessLevelHelper::LEVEL_API,
                'status' => UserStatusHelper::STATUS_ACTIVE
            ])->one();

        if ($model !== null) {

            return Password::validate($password, $model->hash) ? $model : null;
        }

        return null;
    }


    /**
     * @return int
     */
    public static function findCountAdmin()
    {
        return self::find()
            ->active()
            ->andWhere(['access_level' => UserAccessLevelHelper::LEVEL_ADMIN])
            ->count();
    }


    /**
     * @return ActiveQuery|Access
     */
    public function getAccess()
    {
        return $this->hasMany(Access::class, ['id' => 'id', 'type' => Access::TYPE_USER]);
    }


    /**
     * @param int $size
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\web\HttpException
     */
    public function getAvatar($size = 64)
    {
        $avatar = $this->avatar ? $this->avatar : $this->module->defaultAvatar;

        return app()->thumbNailer->thumbnail($avatar,
            $this->module->avatarDirs,
            $size, $size
        );
    }


    /**
     * @return ActiveQuery|Profile
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['user_id' => 'id']);
    }


    /**
     * @return int
     */
    public function getRegisterFrom()
    {
        return $this->registered_from;
    }


    /**
     * @return ActiveQuery|Token
     */
    public function getToken()
    {
        return $this->hasOne(Token::class, ['user_id' => 'id']);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['username', 'email'], 'required'],

            [['email_confirm', 'user_ip', 'status', 'registered_from', 'access_level'], 'integer'],

            [['email_confirm', 'user_ip', 'status', 'registered_from', 'access_level'], 'integer'],

            [['status_change_at', 'visited_at', 'created_at', 'updated_at'], 'safe'],

            [['username'], 'string', 'max' => 25],
            [['username'], 'unique'],

            [['email'], 'string', 'max' => 150],
            [['email'], 'unique'],

            [['hash'], 'string', 'max' => 60],

            [['auth_key'], 'string', 'max' => 32],

            ['access_level', 'in', 'range' => array_keys(UserAccessLevelHelper::getListUFRole())],

        ];
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user__info}}';
    }
}
