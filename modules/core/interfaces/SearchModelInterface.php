<?php
namespace app\modules\core\interfaces;


use yii\data\DataProviderInterface;

interface SearchModelInterface
{
    /**
     * @param array $params
     *
     * @return DataProviderInterface
     */
    public function search($params);
}