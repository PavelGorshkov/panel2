<?php

namespace app\modules\developer\models;


/**
 * Class SearchMigration
 * @package app\modules\developer\models
 */
class SearchMigration extends SearchClassesModule
{
    /**
     * @param string $module
     * @return null|string
     */
    protected function pathForModule($module) {

        $migrationPath = \Yii::getAlias("@app/modules/".$module."/install/migrations");

        return is_dir($migrationPath)?$migrationPath:null;
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

                $data[$k] = \Yii::createObject([
                    'class'=>$this->getModelClassName(),
                    'module'=>$module,
                    'className'=>$list[$k][1],
                    'createTime'=>$list[$k][2],
                ]);
            }
        }

        return $data;
    }


    /**
     * @return string
     */
    public function getModelClassName() {

        return Migration::class;
    }


    /**
     * @return array
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