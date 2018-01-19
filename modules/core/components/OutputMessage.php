<?php

namespace app\modules\core\components;

use app\modules\core\helpers\OutputMessageListHelper;
use yii\base\BaseObject;

class OutputMessage extends BaseObject
{
    /**
     * @var integer
     */
    protected $type;

    /**
     * @var string
     */
    protected $message;


    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }


    /**
     * @param integer $type
     */
    public function setType($type = null)
    {
       $list = OutputMessageListHelper::getList();

       if (empty($type) || !isset($list[$type])) {

           $type = OutputMessageListHelper::INFO;
       }

        $this->type = $type;
    }


    /**
     * @param bool $console
     *
     * @return int
     */
    public function getType($console = false)
    {
        return OutputMessageListHelper::getValue($this->type, !$console);
    }


    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}