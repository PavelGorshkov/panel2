<?php

namespace app\modules\user\models;

use app\modules\user\helpers\EmailConfirmStatusHelper;
use app\modules\user\helpers\RegisterFromHelper;
use app\modules\user\helpers\UserAccessLevelHelper;
use app\modules\user\models\query\UserQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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
 */
class User extends ActiveRecord
{
    protected static $_accessList = null;

    /**
     * @return array
     */
    public function behaviors()
    {

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

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {

                $this->auth_key = app()->security->generateRandomString();
            }
            return true;
        }
        return false;
    }


    /**
     * @param $insert
     * @param $changedAttributes
     */
    public function afterSafe($insert, $changedAttributes)
    {

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

            [['username', 'email', 'hash', 'full_name'], 'required'],

            [['email_confirm', 'user_ip', 'status', 'registered_from', 'access_level', 'logged_in_from'], 'integer'],

            [['email_confirm', 'user_ip', 'status', 'registered_from', 'access_level', 'logged_in_from'], 'integer'],

            [['status_change_at', 'visited_at', 'created_at', 'updated_at'], 'safe'],

            [['username'], 'string', 'max' => 25],
            [['username'], 'unique'],

            [['email'], 'string', 'max' => 150],
            [['email'], 'unique'],

            [['hash'], 'string', 'max' => 60],

            [['auth_key'], 'string', 'max' => 32],

            ['access_level', 'in', 'range' => array_keys(self::getAccessLevelList())],

            [['about'], 'string'],

            [['full_name', 'avatar'], 'string', 'max' => 150],

            [['phone'], 'string', 'max' => 30],
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
            'full_name' => 'ФИО',
            'avatar' => 'Аватар',
            'about' => 'Должность, место работы',
            'phone' => 'Телефон',
        ];
    }

    /**
     * @return bool
     */
    public function isLdap()
    {

        return $this->registered_from === RegisterFromHelper::LDAP;
    }

    /**
     * @return ActiveQuery|Token
     */
    public function getToken()
    {
        return $this->hasOne(Token::className(), ['user_id' => 'id']);
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
     * @param mixed $id
     * @return User|array|null|ActiveRecord
     */
    public static function findByPk($id)
    {

        return self::find()->findUser('id = :id', [':id' => $id])->one();
    }


    /**
     * @return int
     */
    public static function findCountAdmin()
    {
        return self::find()->findCountAdmin();
    }


    /**
     * Проверка подтверждения статуса
     *
     * @return bool
     */
    public function isConfirmedEmail()
    {

        return (int)$this->email_confirm === EmailConfirmStatusHelper::EMAIL_CONFIRM_YES;
    }


    /**
     * @return bool
     */
    public function isUFAccessLevel()
    {

        return $this->access_level >= 100;
    }


    /**
     * @return array|null
     */
    public static function getAccessLevelList()
    {
        if (self::$_accessList === null) {

            self::$_accessList = ArrayHelper::merge(
                UserAccessLevelHelper::getList(),
                Role::find()->allListRoles()
            );
        }

        return self::$_accessList;
    }


    /**
     * @return bool
     */
    public function isAdmin()
    {

        return $this->access_level === UserAccessLevelHelper::LEVEL_ADMIN;
    }


    /**
     * @return string
     */
    public function getAccessGroup()
    {

        $data = self::getAccessLevelList();

        return isset($data[$this->access_level]) ? $data[$this->access_level] : '*не известна*';
    }


    /**
     * @return string
     */
    public function getContact()
    {

        $text = [
            $this->full_name,
            Html::a($this->email, "mailto:" . $this->email),
        ];

        if ($this->phone) {

            $text[] = $this->phone;
        }

        return implode('<br>', $text);
    }
}
