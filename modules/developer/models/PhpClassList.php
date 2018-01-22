<?php
namespace app\modules\developer\models;

use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;
use yii\helpers\ArrayHelper;

/**
 * Class PhpClassList
 * @package app\modules\developer\models
 */
class PhpClassList
{
    /**
     * @var array
     */
    protected $list;


    /**
     * @return string
     */
    public static function className()
    {
        return get_called_class();
    }


    public function __construct($module = null)
    {

        $this->list = $this->getInitList($module);
    }


    protected function getInitList($module) {

        $modules = app()->moduleManager->getListAllModules();

        $list = [];

        $order = 0;

        if ($module === null || !in_array($module, $modules)) {

            foreach ($modules as $module) {

                $m = $this->getListClass($module);

                foreach ($m as $cl) {

                    $cl = ArrayHelper::merge([
                        'list_id' => 'list_' . $order,
                        'module' => $module
                    ], $cl);
                    $list[$order] = $cl;
                    $order++;
                }
            }
        } else {

            $m = $this->getListClass($module);

            foreach ($m as $cl) {

                $cl = ArrayHelper::merge([
                    'list_id' => 'list_' . $order,
                    'module' => $module
                ], $cl);
                $list[$order] = $cl;
                $order++;
            }
        }

        return $list;
    }


    /**
     * @param $module
     * @return mixed
     */
    abstract function getListClass($module);


    public function getList() {

        return $this->list;
    }


    /**
     * @return DataProviderInterface
     */
    public function search() {

        return new ArrayDataProvider([
            'key'=>'list_id',
            'allModels'=>$this->list,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    }


    /**
     * @return bool
     */
    public static function getValidators()
    {
        return true;
    }
}