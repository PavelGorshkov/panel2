<?php
namespace app\modules\developer\models;

use Yii;

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
                    'class'=>$this->getModelClassName(),
                    'module'=>$module,
                    'className'=>$list[$k][1],
                    'createTime'=>$list[$k][2],
                ]);
            }
        }

        return $data;
    }

    protected function setFilterData()
    {
        // TODO: Implement setFilterData() method.
    }
}