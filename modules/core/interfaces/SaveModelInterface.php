<?php
namespace app\modules\core\interfaces;

use yii\base\Model;

interface SaveModelInterface
{
    /**
     * @param Model $model
     *
     * @return boolean
     */
    public function processingData(Model $model);
}