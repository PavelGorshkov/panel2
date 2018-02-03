<?php

namespace app\modules\user\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\core\interfaces\SearchModelInterface;

/**
 * SearchRole represents the model
 * behind the search form of `\app\modules\user\models\Role`.
 *
 * Class SearchRole
 * @package app\modules\user\models
 */
class SearchRole extends Model implements SearchModelInterface
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
        $query = Role::find();

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
