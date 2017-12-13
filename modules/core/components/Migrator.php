<?php
namespace app\modules\core\components;

use Yii;
use yii\base\Component;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\db\Migration;
use yii\db\Query;
use yii\helpers\ArrayHelper;


class Migrator extends Component{

    public $migrationTable = '{{%migrations}}';

    public $setInstance = false;


    /**
     * Проверяем на незавершённые миграции:
     *
     * @param string $module - required module
     * @param bool $class - migration class
     *
     * @return bool is updated to migration
     **/
    public function checkForBadMigration($module, $class = false)
    {
        echo 'Проверяем на наличие незавершённых миграций.<br />';

        $data = (new Query())
            ->select(['version', 'apply_time'])
            ->distinct()
            ->from($this->migrationTable)
            ->orderBy(['id'=>SORT_DESC])
            ->where('module = :module', [':module' => $module])
            ->all();

        if (($data !== []) || ((strpos($class, '_base') !== false) && ($data[] = [
                    'version' => $class,
                    'apply_time' => 0,
                ]))
        ) {
            foreach ($data as $migration) {

                if ($migration['apply_time'] == 0) {

                    try {

                        echo 'Откат миграции '.$migration['version'].' для модуля '.$module.'.<br />';
                        Yii::trace('Откат миграции '.$migration['version'].' для модуля '.$module.'.', __METHOD__);

                        if ($this->migrateDown($module, $migration['version']) !== false) {

                            (new Migration())->delete(
                                $this->migrationTable,
                                'version = :version AND module=:module',
                                [':version'=>$migration['version'], ':module'=>$module]);

                        } else {

                            Yii::warning('Не удалось выполнить откат миграции '.$migration['version'].' для модуля '.$module.'.', __METHOD__);
                            echo 'Не удалось выполнить откат миграции '.$migration['version'].' для модуля '.$module.'.<br />';

                            return false;
                        }

                    } catch (ErrorException $e) {

                        Yii::error('Произошла ошибка: '.$e, __METHOD__);
                        echo 'Произошла ошибка: '.$e;
                    }
                }
            }
        } else {
            Yii::trace('Для модуля '.$module.' не требуется откат миграции.', __METHOD__);
            echo 'Для модуля '.$module.' не требуется откат миграции.<br />';
        }

        return true;
    }


    /**
     * Создает таблицу историй миграций
     */
    protected function createMigrationHistoryTable() {

        Yii::trace("Создаем таблицу для хранения версий миграций ".__METHOD__);

        echo "Создаем таблицу для хранения версий миграций ".$this->migrationTable;

        $migration = new Migration();

        $migration->createTable(
            $this->table,
            [
                'id'=>$migration->primaryKey(),
                'module'=>$migration->string()->notNull(),
                'version'=>$migration->string()->notNull(),
                'apply_time'=>$migration->integer()->defaultValue('0')
            ],
            $migration->getOptions()
        );

        $migration->createIndex(
            'ix_migrations_module',
            $this->table,
            "module",
            false
        );
    }


    /**
     * @param string $module
     * @param int $limit
     * @return array
     */
    public function getMigrationHistory($module, $limit = 20)
    {
        $data = (new Query())
            ->select(['version', 'apply_time'])
            ->from('{{%migrations}}')
            ->orderBy(['id'=>SORT_DESC])
            ->where('module = :module', [':module'=>$module])
            ->limit($limit)
            ->all();

        return ArrayHelper::map($data, 'version', 'apply_time');
    }


    /**
     * @param string $module
     * @return array
     */
    protected function getNewMigrations($module)
    {
        $applied = [];

        foreach ($this->getMigrationHistory($module, -1) as $version => $time) {
            if ($time) {
                $applied[substr($version, 1, 13)] = true;
            }
        }

        $migrations = [];

        if (($migrationsPath = self::getPathMigration($module)) && is_dir(
                $migrationsPath
            )
        ) {

            $handle = opendir($migrationsPath);

            while (($file = readdir($handle)) !== false) {

                if ($file === '.' || $file === '..') continue;

                $path = $migrationsPath.'/'.$file;

                if (
                    preg_match('/^(m(\d{6}_\d{6})_.*?)\.php$/', $file, $matches)
                    && is_file($path)
                    && !isset($applied[$matches[2]])
                )
                {
                    $migrations[] = $matches[1];
                }
            }

            closedir($handle);
            sort($migrations);
        }

        return $migrations;
    }


    /**
     * @param string $module
     *
     * @return string
     */
    public static function getPathMigration($module) {

        return Yii::getAlias(sprintf("@app/modules/%s/install/migrations", $module));
    }


    public function init() {

        if (app()->db->schema->getTableSchema($this->migrationTable) === null) {

            $this->createMigrationHistoryTable();
        }

        parent::init();
    }


    /**
     * Check each modules for new migrations
     *
     * @param string $module - required module
     * @param string $class - class of migration
     *
     * @return mixed version and apply time
     */
    protected function instantiateMigration($module, $class)
    {
        $file = self::getPathMigration($module).'/'.$class.'.php';

        include_once $file;

        return new $class;
    }


    /**
     * Отмена миграции:
     *
     * @param string $module
     * @param string $class
     *
     * @return bool|void
     * @throws Exception
     */
    public function migrateDown($module, $class)
    {
        Yii::trace('Отменяем миграцию '.$class, __METHOD__);

        $start = microtime(true);
        $migration = $this->instantiateMigration($module, $class);

        if (!($migration instanceof Migration )) return;

        ob_start();
        ob_implicit_flush(false);

        $result = $migration->down();

        Yii::trace($msg = ob_get_clean());

        app()->cache->clear('getMigrationHistory');

        if ($result !== false) {

            (new Migration())->delete(
                $this->migrationTable,
                 "version = :ver AND module = :module",
                [':ver'=>$class, ':module'=>$module]
            );

            $time = microtime(true) - $start;
            Yii::trace('Миграция '.$class.' отменена за '.sprintf("%.3f", $time).' сек.', __METHOD__);

            return true;

        } else {

            $time = microtime(true) - $start;

            Yii::error('Ошибка отмены миграции '.$class.' ('.sprintf("%.3f", $time).' сек.)', __METHOD__);

            throw new Exception('Во время установки возникла ошибка: '.$msg);
        }
    }


    /**
     * @param string $module
     * @param $class
     * @return bool
     * @throws Exception
     */
    protected function migrateUp($module, $class)
    {
        $start = microtime(true);
        $migration = $this->instantiateMigration($module, $class);

        if (!($migration instanceof Migration )) return;

        ob_start();
        ob_implicit_flush(false);

        echo 'Применяем миграцию '.$class.'<br />';



        // Вставляем запись о начале миграции
        /* @var Migration $class */
        (new Migration())->insert(
            $this->migrationTable,
            [
                'version' => $class,
                'module' => $module,
                'apply_time' => 0,
            ]
        );

        $result = $migration->up();

        Yii::trace($msg = ob_get_clean());

        if ($result !== false) {

            // Проставляем "установлено"
            (new Migration())->update(
                $this->migrationTable,
                ['apply_time' => time()],
                "version = :ver AND module = :mod",
                [':ver' => $class, 'mod' => $module]
            );

            $time = microtime(true) - $start;

            Yii::trace("Миграция ".$class." применена за ".sprintf("%.3f", $time)." сек.", __METHOD__);
            echo "Миграция ".$class." применена за ".sprintf("%.3f", $time)." сек...<br />";

        } else {

            $time = microtime(true) - $start;

            Yii::error('Ошибка применения миграции '.$class.' ('.sprintf("%.3f", $time).' сек.)', __METHOD__);

            throw new Exception('Во время установки возникла ошибка: '.$msg);
        }
    }


    /**
     * Обновление миграций модуля
     *
     * @param $module
     * @return bool
     * @throws Exception
     */
    public function updateToLatestModule($module)
    {
        if (($newMigrations = $this->getNewMigrations($module)) !== []) {

            Yii::trace("Обновляем до последней версии базы модуль ".$module, __METHOD__);
            echo "Обновляем до последней версии базы модуль ".$module.'<br />';

            foreach ($newMigrations as $migration) {

                if ($this->migrateUp($module, $migration) === false) {

                    return false;
                }
            }
        }

        return true;
    }


    /**
     * Обновление миграций модулей core и user
     */
    public function updateToLatestSystem() {

        foreach (['core', 'user'] as $module) {

            $this->updateToLatestModule($module);
        }
    }
}