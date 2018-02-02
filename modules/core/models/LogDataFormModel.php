<?php


namespace app\modules\core\models;


use app\modules\core\components\FormModel;
use app\modules\core\helpers\LoggerHelper;
use yii\base\Exception;
use yii\data\ArrayDataProvider;

class LogDataFormModel extends FormModel
{
    public $date;

    public $level;

    public $ip;

    public $message;

    public $name;


    const SCENARIO_SEARCH = 'search';
    const LEVEL_INFO = "info";
    const LEVEL_WARNING = "warning";
    const LEVEL_ERROR = "error";
    const LEVEL_TRACE = "trace";
    const LEVEL_PROFILE = "profile";


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SEARCH] = ['date', 'level', 'ip', 'message', 'name'];
        return $scenarios;
    }


    public function rules()
    {
        return [
            [['name'], 'required'],
            ['level', 'in', 'range' => array_keys(self::getLevelTypes())],
            [['date', 'level', 'ip', 'message'], 'safe'],
        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'date' => 'Дата',
            'ip' => 'ip Адрес',
            'level' => 'Уровень',
            'message' => 'Сообщение',
            'name' => 'Имя Лог-файла'
        ];
    }

    /**
     * @param $params
     * @return ArrayDataProvider
     * @throws Exception
     */
    public function search($params)
    {
        if (empty($params['name'])){
            throw new Exception('Параметр лог-файла недействительный');
        }
        $this->name = $params['name'];
        if (($this->load($params) && $this->validate()))
            $list = $this->setFilterData();
        else
            $list = $this->setListData();

        return new ArrayDataProvider([
            'key' => 'name',
            'allModels' => $list,
            'sort' => [
                'attributes' => ['date', 'level', 'ip', 'message'],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
            'modelClass' => self::className(),
        ]);
    }


    /**
     * @return array
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function setListData()
    {
        $data = LoggerHelper::model()->getLogExist($this->name);
        $objArray = [];
        if (!empty($data)) {
            foreach ($data as $item) {
                $objArray[] = \Yii::createObject([
                    'class' => self::className(),
                    'date' => $item['date'],
                    'ip' => $item['ip'],
                    'level' => $item['level'],
                    'message' => $item['information'],
                    'name' => $this->name,
                ]);
            }
            return $objArray;
        } else
            throw new Exception("Параметр лог-файла неккоректен", '500');
    }


    public function getLevelTypes()
    {
        return [
            self::LEVEL_INFO => 'Info',
            self::LEVEL_ERROR => 'Error',
            self::LEVEL_PROFILE => 'Profile',
            self::LEVEL_TRACE => 'Trace',
            self::LEVEL_WARNING => 'Warning',
        ];
    }

    /**
     * @return array
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    protected function setFilterData()
    {
        $list = $this->setListData();
        $object = $this->getAttributes();
        foreach ($object as $attrName => $attrValue) {
            if (!empty($attrValue)) {
                foreach ($list as $key => $model) {
                    if (mb_strpos($model->$attrName, $attrValue) === false) {
                        unset($list[$key]);
                    }
                }
            }
        }
        return $list;

    }

}