<?php
namespace app\modules\user\models;

use app\modules\core\interfaces\SearchModelInterface;
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
            ['access_level', 'in', 'range'=>array_keys(User::getAccessLevelList())],
            ['status', 'in', 'range'=>array_keys(UserStatusHelper::getList())],
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
        $query = ManagerUser::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['like', 'username', $this->username])
            ->orFilterWhere(['like', 'email', $this->info])
            ->orFilterWhere(['like', 'full_name', $this->info])
            ->orFilterWhere(['like', 'phone', $this->info])
            ->andFilterWhere(['access_level'=>$this->access_level])
            ->andFilterWhere(['status'=>$this->status]);

        return $dataProvider;
    }
}