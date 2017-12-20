<?php
namespace app\modules\core\components\actions;


use Yii;

class ErrorAction extends \yii\web\ErrorAction {

    public function init() {

        parent::init();

        $this->view = '@app/modules/core/views/errorHandler/error';
    }

    protected function getExceptionName()
    {
        if ($this->exception instanceof Exception) {
            $name = $this->exception->getName();
        } else {
            $name = $this->defaultName;
        }

        return $name;
    }


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