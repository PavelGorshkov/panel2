<?php

namespace app\modules\user\models;

use app\modules\core\interfaces\SearchModelInterface;
use app\modules\user\helpers\UserAccessLevelHelper;
use app\modules\user\helpers\UserStatusHelper;
use app\modules\user\models\query\UserQuery;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class SearchUser
 * @package app\modules\user\models
 */
class SearchUser extends Model implements SearchModelInterface
{
    /**
     * @var string
     */
    public $username;

    public $info;

    public $access_level;

    public $status;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'info', 'access_level', 'status'], 'safe'],
            ['access_level', 'in', 'range' => array_keys(UserAccessLevelHelper::getListUFRole())],
            ['status', 'in', 'range' => array_keys(UserStatusHelper::getList())],
        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'info' => 'Информация',
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        /* @var $query UserQuery */
        $query = ManagerUser::find()
           // ->with('profile')
            ->alias('t')
            ->joinWith('profile p');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['like', 't.username', $this->username])
            ->orFilterWhere(['like', 't.email', $this->info])
            ->orFilterWhere(['like', 'p.full_name', $this->info])
            ->orFilterWhere(['like', 'p.phone', $this->info])
            ->andFilterWhere(['t.access_level' => $this->access_level])
            ->andFilterWhere(['t.status' => $this->status]);

        return $dataProvider;
    }
}