<?php
namespace app\modules\user\helpers;

use app\modules\user\events\FormEvent;
use Yii;
use yii\base\Model;

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

}