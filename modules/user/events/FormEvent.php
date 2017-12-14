<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 14.12.2017
 * Time: 15:30
 */

namespace app\modules\user\events;

use yii\base\Event;
use yii\base\Model;


class FormEvent extends Event {

    /**
     * @var Model
     */
    private $_form;

    /**
     * @return Model
     */
    public function getForm()
    {
        return $this->_form;
    }

    /**
     * @param Model $form
     */
    public function setForm(Model $form)
    {
        $this->_form = $form;
    }
}