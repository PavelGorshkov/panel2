<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 14.12.2017
 * Time: 15:43
 */

namespace app\modules\user\widgets;

use app\modules\core\widgets\Widget;
use yii\bootstrap\Alert;

class FlashMessages extends Widget{

    public $alertTypes = [
        'error'   => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning'
    ];

    public $closeButton = [];

    public function run() {

        $webUser = app()->user;

        $appendClass = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        foreach ($this->alertTypes as $type => $alert) {

            if (!$webUser->hasFlash($type)) continue;

            $alertText = $webUser->getFlash($type);

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