<?php
namespace app\modules\core\interfaces;

use yii\base\Model;

/**
 * Interface SaveModelInterface
 * @package app\modules\core\interfaces
 */
interface SaveModelInterface
{
    /**
     * @param Model $model
     *
     * @return boolean
     */
    public function processingData(Model $model);
}