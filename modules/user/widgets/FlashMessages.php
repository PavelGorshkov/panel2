<?php
namespace app\modules\user\widgets;

use yii\bootstrap\Widget;
use yii\bootstrap\Alert;

/**
 * Class FlashMessages
 * @package app\modules\user\widgets
 */
class FlashMessages extends Widget{

    public $alertTypes = [
        'error'   => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning'
    ];

    public $closeButton = [];

    /**
     * @return string|void
     * @throws \Exception
     */
    public function run() {

        $appendClass = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        foreach ($this->alertTypes as $type => $alert) {

            if (!app()->user->hasFlash($type)) continue;

            $alertText = app()->user->getFlash($type);

            if (empty($alertText)) continue;

            echo Alert::widget([
                'body' => $alertText,
                'closeButton' => $this->closeButton,
                'options' => array_merge($this->options, [
                    'id' => $this->getId() . '-' . $type,
                    'class' => $this->alertTypes[$type] . $appendClass,
                ]),
            ]);
        }
    }
}