<?php


namespace app\modules\core\models;


use app\modules\core\components\FormModel;
use app\modules\core\helpers\LoggerHelper;
use app\modules\core\interfaces\SearchModelInterface;
use yii\data\ArrayDataProvider;

/**
 * Class LogSourceFormModel
 * @package app\modules\core\models
 */
class LogSourceFormModel extends FormModel implements SearchModelInterface
{

    const SCENARIO_SEARCH = 'search';

    public $name;

    public $source;

    public $mtime;

    public $fpath;

    /**
     * @inheritdoc
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SEARCH]= ['name','source'];
        return $scenarios;
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['source', 'in', 'range' => array_keys(self::getSourceType())],
            [['name', 'source', 'mtime', 'fpath'], 'safe'],

        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя файла',
            'source' => 'Источник',
            'mtime' => 'Дата изменения',
            'fpath' => 'Путь',
        ];
    }

    /**
     * @param string|null $param
     * @return mixed
     */
    public static function getSourceType($param = null)
    {
        $types = LoggerHelper::model()->getSourceType();
        if ($param != null)
            return $types[$param];
        else return $types;
    }

    /**
     * @param array $params
     * @return \yii\data\DataProviderInterface
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params)
    {
        if (($this->load($params) && $this->validate())) {
            $list = $this->setFilterData();
        } else {
            $list = $this->setListData();
        }
        return new ArrayDataProvider([
            'key' => 'name',
            'allModels' => $list,
            'sort' => [
                'attributes' => ['name', 'source', 'mtime'],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
            'modelClass' => self::className(),
        ]);

    }

    /**
     * @return array LogSourceFormModel objects
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

    /**
     * @return array LogSourceFormModel objects
     * @throws \yii\base\InvalidConfigException
     */
    protected function setListData()
    {
        $logs = LoggerHelper::model()->getData();
        $objArray = [];
        foreach ($logs as $source => $info) {
            foreach ($info as $log) {
                $objArray[] = \Yii::createObject([
                    'class' => LogSourceFormModel::className(),
                    'name' => $log['name'],
                    'fpath' => $log['filePath'],
                    'mtime' => $log['modTime'],
                    'source' => $source,
                ]);
            }
        }
        return $objArray;
    }


}