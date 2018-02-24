<?php

namespace app\modules\user\components;

use app\modules\user\helpers\ModuleTrait;
use app\modules\user\helpers\TokenStatusHelper;
use app\modules\user\helpers\TokenTypeHelper;
use app\modules\user\models\query\TokenQuery;
use app\modules\user\models\User;
use app\modules\user\models\Token;
use \Throwable;
use Yii;
use yii\base\Component;
use yii\db\Expression;
use yii\web\ServerErrorHttpException;

/**
 * Class TokenStorage
 * @package app\modules\user\components
 *
 *
 */
class TokenStorage extends Component
{

    use ModuleTrait;

    /**
     * @var TokenQuery
     */
    protected $userTokenQuery;


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->userTokenQuery = Token::find();

        $this->deleteExpired();
    }


    /**
     * @param User $user
     * @param $expire
     * @param $type
     * @return bool|Token
     * @throws \yii\base\Exception
     */
    public function create(User $user, $expire, $type)
    {
        $expire = (int)$expire;

        $model = new Token();
        $model->user_id = $user->id;
        $model->expire = new Expression("DATE_ADD(NOW(), INTERVAL {$expire} SECOND)");

        $model->type = (int)$type;
        $model->status = TokenStatusHelper::STATUS_NEW;
        $model->ip = ip2long(app()->request->userIP);

        $model->token = app()->security->generateRandomString();

        if (!$model->save()) {

            throw new ServerErrorHttpException('Не удалось создать токен');
        }

        return $model;
    }


    /**
     * @return int
     */
    public function deleteExpired()
    {
        $deleted = Token::deleteAll('expire < NOW()');

        Yii::info(sprintf('Удалено %d токенов', $deleted));

        return $deleted;
    }


    /**
     * @param User $user
     *
     * @return Token|bool
     * @throws \yii\base\Exception
     */
    public function createAccountActivationToken(User $user)
    {
        $this->deleteByTypeAndUser(TokenTypeHelper::ACTIVATE, $user);

        return $this->create($user, $this->module->expireTokenActivationLifeHours * 3600, TokenTypeHelper::ACTIVATE);
    }


    /**
     * @param User $user
     * @return Token|bool
     * @throws \yii\base\Exception
     */
    public function createEmailActivationToken(User $user)
    {
        $this->deleteByTypeAndUser(TokenTypeHelper::EMAIL_VERIFY, $user);

        return $this->create($user, $this->module->expireTokenActivationLifeHours * 3600, TokenTypeHelper::EMAIL_VERIFY);
    }


    /**
     * @param string $token
     * @param int $type
     * @param int $status
     *
     * @return Token|null
     */
    public function getToken($token, $type, $status = TokenStatusHelper::STATUS_NEW)
    {
        return $this->userTokenQuery->where(
            'token = :token AND type = :type AND status = :status',
            [
                ':token' => $token,
                ':type' => (int)$type,
                ':status' => (int)$status
            ]
        )->one();
    }


    /**
     * @param Token $token
     * @return false|int
     *
     * @throws \Exception
     * @throws Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(Token $token)
    {
        return $token->delete();
    }


    /**
     * @param $type
     * @param User $user
     *
     * @return int
     */
    public function deleteByTypeAndUser($type, User $user)
    {
        return Token::deleteAll(
            'type = :type AND user_id = :user_id',
            [
                ':type' => (int)$type,
                ':user_id' => $user->id,
            ]
        );
    }


    /**
     * @param User $user
     * @return Token|bool
     * @throws \yii\base\Exception
     */
    public function createPasswordToken(User $user)
    {
        $this->deleteByTypeAndUser(TokenTypeHelper::CHANGE_PASSWORD, $user);

        return $this->create($user, $this->module->expireTokenPasswordLifeHours * 3600, TokenTypeHelper::CHANGE_PASSWORD);
    }
}