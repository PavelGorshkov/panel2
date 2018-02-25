<?php
namespace app\modules\core\components\actions;

use yii\base\Exception;
use yii\web\ErrorAction as BaseErrorAction;

/**
 * Class ErrorAction
 * @package app\modules\core\components\actions
 */
class ErrorAction extends BaseErrorAction
{

    /**
     * @inheritdoc
     */
    public function init() {

        parent::init();

        $this->view = '@app/modules/core/views/errorHandler/error';
    }


    /**
     * @return string
     */
    protected function getExceptionName()
    {
        if ($this->exception instanceof Exception) {

            $name = $this->exception->getName();
        } else {
            $name = $this->defaultName;
        }

        return $name;
    }


    /**
     * @return array
     */
    protected function getViewRenderParams()
    {
        return [
            'name' => $this->getExceptionName(),
            'message' => $this->getExceptionMessage(),
            'exception' => $this->exception,
            'code'=>$this->getExceptionCode(),
        ];
    }
}