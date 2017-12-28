<?php

namespace app\modules\user\models;

use app\modules\user\helpers\EmailConfirmStatusHelper;
use app\modules\user\helpers\UserAccessLevelHelper;
use app\modules\user\helpers\UserStatusHelper;
use app\modules\user\models\query\UserQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\IdentityInterface;

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
 *
 * @property UserProfile $userProfile
 */
class User extends ActiveRecord implements IdentityInterface
{
    const SCENARIO_REGISTER = 'register';

    protected static $_accessList = null;

    public function behaviors() {

        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_user}}';
    }


    public function scenarios() {

        return ArrayHelper::merge(parent::scenarios(),
            [
                self::SCENARIO_REGISTER =>[
                    'username', 'email',
                ]
            ]);
    }


    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {

                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }


    public function afterSafe($insert, $changedAttributes) {

        parent::afterSave($insert, $changedAttributes);

        if ($this->isNewRecord) {

            $this->id = app()->db->lastInsertID;
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['username', 'email', 'hash', 'auth_key'], 'required'],

            [['email_confirm', 'user_ip', 'status', 'registered_from', 'access_level', 'logged_in_from'], 'integer'],

            [['email_confirm', 'user_ip', 'status', 'registered_from', 'access_level', 'logged_in_from'], 'integer'],

            [['status_change_at', 'visited_at', 'created_at', 'updated_at'], 'safe'],

            [['username'], 'string', 'max' => 25],
            ['access_level', 'in', 'range' => array_keys(self::getAccessLevelList())],
            [['email'], 'string', 'max' => 150],
            [['hash'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
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
            'logged_in_from' => 'Logged In From',
            'logged_at' => 'Logged At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'id']);
    }
	
	public function getUserToken() 
	{
		return $this->hasOne(UserToken::className(), ['user_id' => 'id']);
	}
	

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }


    public static function findIdentity($id) {

        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null) {


    }

    public function getId() {

        return $this->getAttribute('id');
    }

    public function getAuthKey() {

        return $this->getAttribute('auth_key');
    }

    public function validateAuthKey($authKey) {

        return $this->getAuthKey() === $authKey;
    }


    /**
     * Проверка подтверждения статуса
     *
     * @return bool
     */
    public function isConfirmedEmail() {

        return (int) $this->email_confirm === EmailConfirmStatusHelper::EMAIL_CONFIRM_YES;
    }


    public function isUFAccessLevel() {

        return $this->access_level >= 100;
    }


    /**
     * Проверка заблокированности пользователя
     *
     * @return bool
     */
    public function getIsBlocked()
    {
        return (int) $this->status === UserStatusHelper::STATUS_BLOCK;
    }


    public static function getAccessLevelList()
    {
        if (self::$_accessList === null) {

            self::$_accessList = ArrayHelper::merge(
                UserAccessLevelHelper::getList(),
                UserRole::find()->allListRoles()
            );
        }

        return self::$_accessList;
    }


    public function getAccessGroup() {

        $data = self::getAccessLevelList();

        return isset($data[$this->access_level]) ? $data[$this->access_level] : '*не известна*';
    }


    public function getContact() {

        $text = [
            $this->userProfile->full_name,
            Html::a($this->email, "mailto:".$this->email),
        ];

        if ($this->userProfile->phone)  {

            $text[] = $this->userProfile->phone;
        }

        return implode('<br>', $text);
    }
}
