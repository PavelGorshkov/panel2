<?php

namespace app\modules\developer\models;

use app\modules\core\interfaces\SearchModelInterface;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;


/**
 * Class SearchMigration
 * @package app\modules\developer\models
 */
class SearchMigration extends Model implements SearchModelInterface
{
    public $className;

    public $module;

    protected $list = [];

    /**
     * @var array|null
     */
    protected $listModules = null;

    /**
     * @param string $module
     * @return null|string
     */
    public function pathForModule($module) {

        $migrationPath = Yii::getAlias("@app/modules/".$module."/install/migrations");

        return is_dir($migrationPath)?$migrationPath:null;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['className', 'module'], 'safe'],
            ['module', 'in', 'range'=>$this->getListModules()],
        ];
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
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function setListData()
    {
        $list = [];
        $order = 0;

        $modules = $this->getListModules();

        if ($this->module === null || !in_array($this->module, $modules)) {

            $modules = $this->getListModules();
        } else {

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
     * @param string $module
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getListClass($module)
    {

        $list = [];
        $m = [];
        $data = [];

        if (($migrationsPath = $this->pathForModule($module)) !== null) {

            $handle = opendir($migrationsPath);

            while (($file = readdir($handle)) !== false) {

                if ($file === '.' || $file === '..') {
                    continue;
                }

                $path = $migrationsPath . '/' . $file;

                if (
                    preg_match('/^(m(\d{6}_\d{6})_.*?)\.php$/', $file, $matches)
                    && is_file($path)
                ) {
                    $m[] = $matches[1];
                    $list[] = $matches;
                }
            }
            closedir($handle);
            ksort($m);

            foreach ($m as $k => $temp) {

                $data[$k] = Yii::createObject([
                    'class'=>Migration::className(),
                    'module'=>$module,
                    'className'=>$list[$k][1],
                    'createTime'=>$list[$k][2],
                ]);
            }
        }

        return $data;
    }


    /**
     * @param array $params
     *
     * @return DataProviderInterface
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
            'key'=>'id',
            'allModels'=>$list,
            'pagination'=>[
                'pageSize'=> 20
            ],
           'modelClass'=>Migration::className(),
        ]);
    }


    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function setFilterData() {

        $list = $this->setListData();

        if (!empty($this->className)) {

            foreach ($list as $key => $model) {

                if (mb_strpos($model->className, $this->className) === false) {

                    unset($list[$key]);
                }
            }
        }

        return $list;
    }
}