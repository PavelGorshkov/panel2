<?php
namespace app\modules\developer\models;

use app\modules\core\interfaces\SearchModelInterface;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;

/**
 * Class SearchClassesModule
 * @package app\modules\developer\models
 */
abstract class SearchClassesModule extends Model implements SearchModelInterface
{
    public $module;

    public $className;

    protected $list = [];

    /**
     * @var array|null
     */
    protected $listModules = null;


    /**
     * @return string
     */
    abstract protected function getModelClassName();

    /**
     * @param string $module
     * @return array
     */
    abstract protected function getListClass($module);


    public function rules()
    {
        return [
            [['className', 'module'], 'safe'],
            ['module', 'in', 'range'=>$this->getListModules()],
        ];
    }


    abstract protected function setFilterData();


    /**
     * @return array
     */
    protected function setListData()
    {
        $list = [];
        $order = 0;

        $modules = $this->getListModules();

        if ($this->module !== null && in_array($this->module, $modules)) {

            $modules = [$this->module];
        }


        foreach ($modules as $module) {

            $m = $this->getListClass($module);

            /** @var $class Model */
            foreach ($m as $class) {

                $class->setAttributes(['id'=>'list_'.$order]);
                $list[$order] = $class;
                $order++;
            }
        }

        return $list;
    }


    /**
     * @return array
     */
    public function getListModules()
    {
        if ($this->listModules === null) {

            $this->listModules = app()->moduleManager->getListAllModules();
        }

        return $this->listModules;
    }


    /**
     * @param array $params
     *
     * @return DataProviderInterface
     */
    public function search($params)
    {
        if (($this->load($params) && $this->validate())) {

            $list = $this->setFilterData();
        } else {

            $list = $this->setListData();
        }

        return new ArrayDataProvider([
            'key'=>'id',
            'allModels'=>$list,
            'pagination'=>[
                'pageSize'=> 20
            ],
            'modelClass'=>Migration::className(),
        ]);
    }
}