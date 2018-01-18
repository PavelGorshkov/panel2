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
     * Передача данных в $model и обработка данных в переданной модели
     * Например, Передаем данные в ActiveRecord и сохранение данных AR в БД
     *
     * @param Model $model
     * @return boolean
     */
    public function processingData(Model $model);
}