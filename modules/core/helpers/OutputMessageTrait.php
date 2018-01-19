<?php
namespace app\modules\core\helpers;

use app\modules\core\components\OutputMessage;
use Yii;
use yii\helpers\Html;

/**
 * Trait OutputMessageTrait
 * @package app\modules\core\helpers
 */
trait OutputMessageTrait
{
    private $_messages = [];

    /**
     * @param string $message
     * @param integer|null $type
     * @throws \yii\base\InvalidConfigException
     */
    public function addMessage($message, $type = null) {

        $this->_messages[] = Yii::createObject([
            'class'=>OutputMessage::className(),
            'message'=>$message,
            'type'=>$type,
        ]);
    }


    /**
     * @return string
     */
    public function getHtml() {

        $html = '';

        /* @var OutputMessage $message */
        foreach ($this->_messages as $message) {

            $html .= Html::tag('p', $message->getMessage(), ['style'=>'color: '.$message->getType()]);
        }

        return $html;
    }


    /**
     * @return array
     */
    public function getConsole() {

        return $this->_messages;
    }
}