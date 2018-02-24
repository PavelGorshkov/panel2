<?php
namespace app\modules\core\interfaces;

use yii\data\DataProviderInterface;

/**
 * Interface SearchModelInterface
 * @package app\modules\core\interfaces
 */
interface SearchModelInterface
{
    /**
     * @param array $params
     *
     * @return DataProviderInterface
     */
    public function search($params);
}