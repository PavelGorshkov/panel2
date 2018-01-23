<?php
namespace app\modules\developer\models;

use app\modules\user\components\RBACItem;
use Symfony\Component\Finder\SplFileInfo;
use Yii;

/**
 * Class SearchAuthTask
 * @package app\modules\developer\models
 */
class SearchAuthTask extends SearchClassesModule
{
    /**
     * @param string $module
     * @return null|string
     */
    protected function pathForModule($module) {

        $migrationPath = Yii::getAlias("@app/modules/".$module."/auth");

        return is_dir($migrationPath)?$migrationPath:null;
    }


    /**
     * @return string
     */
    protected function getModelClassName()
    {
        return AuthTask::className();
    }


    /**
     * @param string $module
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ServerErrorHttpException
     */
    protected function getListClass($module)
    {
        $data = [];

        /* @var $item SplFileInfo */
        foreach (new \GlobIterator($this->pathForModule($module).'/*Task.php') as $item) {

            $classObject = '\\app\\modules\\' . $module . '\\auth\\' . $item->getBasename('.php');

            /** @var  RBACItem $classObject */
            $classObject = new $classObject;

            $data[] = Yii::createObject([
                'class'=>$this->getModelClassName(),
                'module'=>$module,
                'className'=>$item->getBasename(),
                'title'=>$classObject->getTitleTask(),
            ]);
        }

        return $data;
    }


    /**
     * @return array
     */
    protected function setFilterData()
    {
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