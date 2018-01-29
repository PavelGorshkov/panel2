<?php

namespace app\modules\cron\models;

use app\modules\core\interfaces\SearchModelInterface;
use app\modules\cron\helpers\JobStatusListHelper;
use app\modules\cron\models\query\JobQuery;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class SearchJob
 * @package app\modules\cron\models
 */
class SearchJob extends Model implements SearchModelInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $command;

    /**
     * @var int
     */
    public $is_active;

    /**
     * @var string
     */
    public $params;

    /**
     * @var string
     */
    public $module;


    /**
     * @return array
     */
    public function rules(){
        return [
            [['id', 'module', 'command', 'is_active', 'params'], 'safe'],
            ['is_active', 'in', 'range'=>array_keys(JobStatusListHelper::getList())],
        ];
    }


    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params){
        /* @var $query JobQuery */
        $query = Job::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['is_active'=>$this->is_active])
              ->andFilterWhere(['like', 'command', $this->module]);

        return $dataProvider;
    }

}