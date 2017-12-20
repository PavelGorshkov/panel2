<?php
namespace app\modules\user\helpers;

use app\modules\user\events\FormEvent;
use app\modules\user\events\UserEvent;
use Yii;
use yii\base\Model;
use yii\web\IdentityInterface;

trait EventTrait {

    /**
     * @param  Model $form
     * @return FormEvent
     *
     * @throws \yii\base\InvalidConfigException
     */
    protected function getFormEvent(Model $form)
    {
        return Yii::createObject(['class' => FormEvent::className(), 'form' => $form]);
    }


    protected function getUserEvent(IdentityInterface $user) {

        return Yii::createObject(['class' => UserEvent::className(), 'user'=> $user]);
    }

}