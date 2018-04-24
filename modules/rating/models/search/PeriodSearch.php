<?php

namespace app\modules\rating\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\core\interfaces\SearchModelInterface;
use app\modules\rating\models\Period;
/**
 * PeriodSearch represents the model
 * behind the search form of `\app\modules\rating\models\Period`.
 *
 * Class PeriodSearch
 * @package app\modules\rating\models\search
 */
class PeriodSearch extends Model implements SearchModelInterface
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

        ];
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Period::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {

            return $dataProvider;
        }

        /*
        $query
            ->andFilterWhere(['like', 'field', $this->field]);
        */

        return $dataProvider;
    }
}
