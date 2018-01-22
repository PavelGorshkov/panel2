<?php
namespace app\modules\developer\models;

use app\modules\developer\helpers\MigrationHelper;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class MigrationList
 * @package app\modules\developer\models
 */
class MigrationList
{
    /**
     * @return string
     */
    public static function className() {

        return get_called_class();
    }


    protected $_migrations;


    /**
     * MigrationList constructor.
     * @param string|null $module
     */
    public function __construct($module = null)
    {

        $this->_migrations = $this->getInitMigrations($module);
    }


    /**
     * @param string|null $module
     * @return array
     */
    protected function getInitMigrations($module = null)
    {

        $modules = app()->moduleManager->getListAllModules();

        $migrations = [];

        $order = 0;

        if ($module === null || !in_array($module, $modules)) {

            foreach ($modules as $module) {

                $m = $this->getListMigrationsClass($module);

                foreach ($m as $cl) {

                    $cl = ArrayHelper::merge([
                        'migration_id' => 'migration_' . $order,
                        'module' => $module
                    ], $cl);
                    $migrations[$order] = $cl;
                    $order++;
                }
            }
        } else {

            $m = $this->getListMigrationsClass($module);

            foreach ($m as $cl) {

                $cl = ArrayHelper::merge([
                    'migration_id' => 'migration_' . $order,
                    'module' => $module
                ], $cl);
                $migrations[$order] = $cl;
                $order++;
            }
        }

        return $migrations;
    }


    /**
     * @param string $module
     * @return array
     */
    protected function getListMigrationsClass($module)
    {

        $migrations = [];
        $m = [];
        $data = [];

        if (($migrationsPath = MigrationHelper::pathForModule($module)) !== null) {

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
                    $migrations[] = $matches;
                }
            }
            closedir($handle);
            ksort($m);

            foreach ($m as $k => $temp) {

                $data[$k] = [
                    'classname' => $migrations[$k][1],
                    'createtime' => \DateTime::createFromFormat('ymd_His', $migrations[$k][2]),
                ];
            }
        }

        return $data;
    }


    /**
     * @return array
     */
    public function getMigrations()
    {

        return $this->_migrations;
    }


    /**
     * @return ArrayDataProvider
     */
    public function search()
    {

        return new ArrayDataProvider([
            'key' => 'migration_id',
            'allModels' => $this->_migrations,
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